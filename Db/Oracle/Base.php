<?php

namespace Numbers\Backend\Db\Oracle;
class Base extends \Numbers\Backend\Db\Common\Base implements \Numbers\Backend\Db\Common\Interface2\Base {

	/**
	 * Error overrides
	 *
	 * @var array
	 */
	public $error_overrides = [
		'23503' => 'The record you are trying to delete is used in other areas, please unset it there first.',
		'23505' => 'Duplicate key value violates unique constraint.',
	];

	/**
	 * SQL keyword overrides
	 *
	 * @var string 
	 */
	public $sql_keywords_overrides = [
		'like' => 'LIKE'
	];

	/**
	 * Column overrides
	 *
	 * @var array
	 */
	public $sql_column_overrides = [
		'geometry' => 'ST_AsGeoJSON'
	];

	/**
	 * Backend
	 *
	 * @var string
	 */
	public $backend = 'Oracle';

	/**
	 * Schema initialized
	 *
	 * @var bool
	 */
	private $schema_initilized = false;
	private $schema_script_initilized = false;

	/**
	 * Connect to database
	 *
	 * @param array $options
	 * @return array
	 */
	public function connect(array $options) : array {
		$result = [
			'version' => null,
			'status' => 0,
			'error' => [],
			'errno' => 0,
			'success' => false
		];
		$dbname = $options['__original_dbname'] ?? $options['dbname'];
		$connection_string = '//' . $options['host'] . ':' . $options['port'] . '/' . $dbname;
		$connection = oci_connect($options['username'], $options['password'], $connection_string);
		if ($connection !== false) {
			$this->db_resource = $connection;
			$this->commit_status = 0;
			oci_internal_debug(true);
			$result['version'] = oci_server_version($connection);
			$result['status'] = 1;
			// set default schema
			$this->initialzeWhenNeeded();
			// original database
			if (!empty($options['__original_dbname'])) {
				$container_result = $this->query("ALTER SESSION SET container = {$options['dbname']}");
				if (!$container_result['success']) goto processError;
			}
			// success
			$result['success'] = true;
		} else {
processError:
			$temp = oci_error();
			if (!empty($temp)) {
				trigger_error('Db Link ' . $this->db_link . ': Errno: ' . $temp['code'] . ': ' . $temp['message']);
			}
			// return generic error message
			$result['error'][] = 'db::connect() : Could not connect to database server!';
			$result['errno'] = 1;
		}
		return $result;
	}

	/**
	 * initialize when needed
	 *
	 * @param array $options
	 */
	public function initialzeWhenNeeded(array $options = []) {
		if ($this->schema_initilized) return;
		if (\Helper\Cmd::isCli()) {
			// from cli we allow god mode
			if (!$this->schema_script_initilized) {
				$this->query('ALTER SESSION SET "_ORACLE_SCRIPT" = TRUE');
				$this->schema_script_initilized = true;
			}
			if (empty($options['import'])) return;
		}
		$this->query("ALTER SESSION SET current_schema = PUBLIC2 nls_date_format = 'YYYY-MM-DD' nls_time_format = 'HH24:MI:SS.FF' nls_timestamp_format = 'YYYY-MM-DD HH24:MI:SS.FF'");
		$this->schema_initilized = true;
	}

	/**
	 * Closes a connection
	 *
	 * @return array
	 */
	public function close() {
		if (!empty($this->db_resource)) {
			oci_close($this->db_resource);
			unset($this->db_resource);
		}
		return ['success' => true, 'error' => []];
	}

	/**
	 * Structure of our fields (type, length and null)
	 *
	 * @param resource $resource
	 * @return array
	 */
	public function fieldStructures($resource) {
		$result = [];
		if ($resource) {
			for ($i = 1; $i <= oci_num_fields($resource); $i++) {
				$name = strtolower(oci_field_name($resource, $i));
				$result[$name]['type'] = strtolower(oci_field_type($resource, $i));
				$result[$name]['null'] = oci_field_is_null($resource, $i);
				$result[$name]['length'] = oci_field_size($resource, $i);
				$result[$name]['precision'] = oci_field_precision($resource, $i);
				$result[$name]['scale'] = oci_field_scale($resource, $i);
			}
		}
		return $result;
	}

	/**
	 * This will run SQL query and return structured data
	 *
	 * @param string $sql
	 * @param mixed $key
	 * @param array $options
	 * @return array
	 */
	public function query(string $sql, $key = null, array $options = []) : array {
		$result = [
			'success' => false,
			'error' => [],
			'errno' => 0,
			'rows' => [],
			'num_rows' => 0,
			'affected_rows' => 0,
			'structure' => [],
			// debug attributes
			'cache' => false,
			'cache_tags' => [],
			'time' => microtime(true),
			'sql' => $sql,
			'key' => $key,
			'backtrace' => null
		];
		// if query caching is enabled
		if (!empty($this->options['cache_link'])) {
			$cache_id = !empty($options['cache_id']) ? $options['cache_id'] : 'Db_Query_' . trim(sha1($sql . serialize($key)));
			// if we cache this query
			if (!empty($options['cache'])) {
				$cache_object = new \Cache($this->options['cache_link']);
				$cached_result = $cache_object->get($cache_id, true);
				if ($cached_result !== false) {
					// if we are debugging
					if (\Debug::$debug) {
						\Debug::$data['sql'][] = $cached_result;
					}
					return $cached_result;
				}
			}
		} else {
			$options['cache'] = false;
		}
		// quering
		$resource = oci_parse($this->db_resource, $sql);
		// handle autocommit
		$status = OCI_COMMIT_ON_SUCCESS;
		if ($this->commit_status > 0) {
			$status = OCI_NO_AUTO_COMMIT;
		}
		// execute
		$result['status'] = oci_execute($resource, $status);
		if (!$resource || !$result['status']) {
			$last_error = oci_error($resource);
			if (empty($last_error)) {
				$errno = 1;
				$error = 'DB Link ' . $this->db_link . ': ' . 'Unspecified error!';
			} else {
				$errno = $last_error['code'];
				$error = $last_error['message'];
			}
			$this->errorOverrides($result, $errno, $error);
		} else {
			$result['affected_rows'] = oci_num_rows($resource);
			$result['structure'] = $this->fieldStructures($resource);
			if (!empty($result['structure'])) {
				while ($rows = oci_fetch_assoc($resource)) {
					foreach ($rows as $k => $v) {
						$k2 = strtolower($k);
						unset($rows[$k]);
						$rows[$k2] = $v;
						// we must fix empty string issue
						if (is_string($v) && $v == '__EMPTY_STR__') {
							$rows[$k2] = '';
						} else if ($result['structure'][$k2]['type'] == 'number') { // numbers
							if (!is_null($v)) {
								if ((intval($v) . '') == $v) {
									$rows[$k2] = (int) $v;
								} else {
									$rows[$k2] = (float) $v;
								}
							}
						} else if (in_array($result['structure'][$k2]['type'], ['clob', 'blob', 'nclob'])) { // texts
							if (is_object($v)) {
								$large_object = $v->read($v->size());
							} else {
								$large_object = $v;
							}
							// we must get json vallues to PHP format
							if ($large_object[0] == '{' && is_json($large_object)) {
								$rows[$k2] = json_encode(json_decode($large_object, true));
							} else {
								// we must fix empty string issue
								if ($large_object == '__EMPTY_STR__') {
									$rows[$k2] = '';
								} else {
									$rows[$k2] = $large_object;
								}
							}
						}
					}
					// assigning keys
					if (!empty($key)) {
						array_key_set_by_key_name($result['rows'], $key, $rows);
					} else {
						$result['rows'][] = $rows;
					}
					$result['num_rows']++;
				}
			}
			oci_free_statement($resource);
			$result['success'] = true;
		}
		// time before caching
		$result['time'] = microtime(true) - $result['time'];
		// prepend backtrace in debug mode to know where it was cached
		if (\Debug::$debug) {
			$result['backtrace']  = implode("\n", \Object\Error\Base::debugBacktraceString());
			$result['cache_tags'] = $options['cache_tags'] ?? null;
		}
		// caching if no error
		if (!empty($options['cache']) && empty($result['error'])) {
			$result['cache'] = true;
			$cache_object->set($cache_id, $result, null, $options['cache_tags'] ?? []);
		}
		// if we are debugging
		if (\Debug::$debug) {
			\Debug::$data['sql'][] = $result;
		}
		return $result;
	}

	/**
	 * Begin transaction
	 *
	 * @return array
	 */
	public function begin() {
		if ($this->commit_status == 0) {
			$this->commit_status++;
			return [
				'success' => true,
				'error' => []
			];
		}
		$this->commit_status++;
	}

	/**
	 * Commit transaction
	 *
	 * @return array
	 */
	public function commit() {
		if ($this->commit_status == 1) {
			$this->commit_status = 0;
			$result = $this->query('COMMIT');
			if (!$result['success']) {
				Throw new \Exception('Could not commit transaction: ' . implode(', ', $result['error']));
			}
			return $result;
		}
		$this->commit_status--;
	}

	/**
	 * Roll back transaction
	 *
	 * @return array
	 */
	public function rollback() {
		$this->commit_status = 0;
		$result = $this->query('ROLLBACK');
		if (!$result['success']) {
			Throw new \Exception('Could not rollback transaction: ' . implode(', ', $result['error']));
		}
		return $result;
	}

	/**
	 * Escape takes a value and escapes the value for the database in a generic way
	 *
	 * @param string $value
	 * @return string
	 */
	public function escape($value) {
        return str_replace(
			["'"],		// ["\\",  "\x00", "\n",  "\r",  "'",  '"', "\x1a"],
			["''"],		// ["\\\\","\\0","\\n", "\\r", "''", '\"', "\\Z"],
			$value
		);
	}

	/**
	 * @see db::sequence();
	 */
	public function sequence($sequence_name, $type = 'nextval', $tenant = null, $module = null) {
		$query = new \Object\Query\Builder($this->db_link);
		$query->from('dual');
		// extended sequence
		if (isset($tenant) || isset($module)) {
			$tenant = (int) $tenant;
			$module = (int) $module;
			// determine name
			if ($type == 'nextval') {
				$model = new \Numbers\Backend\Db\Oracle\Model\Sequence\Nextval();
				$name = $model->full_function_name;
			} else {
				$model = new \Numbers\Backend\Db\Oracle\Model\Sequence\Currval();
				$name = $model->full_function_name;
			}
			$query->columns([
				'counter' => "{$name}('{$sequence_name}', {$tenant}, {$module})"
			]);
		} else { // regular sequence
			$query->columns([
				'counter' => "{$sequence_name}.{$type}"
			]);
		}
		return $query->query();
	}

	/**
	 * SQL helper
	 *
	 * @param string $statement
	 * @param array $options
	 * @return string
	 */
	public function sqlHelper($statement, $options = []) {
		$result = '';
		switch ($statement) {
			case 'string_agg':
				$result = 'string_agg(' . $options['expression'] . ', \'' . ($options['delimiter'] ?? ';') . '\')';
				break;
			case 'fetch_databases':
				$result = <<<TTT
					SELECT
						LOWER(a.pdb_name) database_name,
						LOWER(b.open_mode) database_open_mode
					FROM dba_pdbs a
					INNER JOIN v\$pdbs b ON b.name = a.pdb_name
					WHERE INSTR(a.pdb_name, '$') = 0
					ORDER BY database_name ASC
TTT;
				break;
			case 'fetch_tables':
				$result = <<<TTT
					SELECT
						LOWER(a.owner) schema_name,
						LOWER(a.table_name) table_name
					FROM dba_tables a
					WHERE a.owner IN (
							SELECT
								x.username
							FROM dba_users x
							WHERE EXISTS (
								SELECT
									1
								FROM dba_role_privs y
								WHERE y.grantee = x.username
									AND LOWER(y.granted_role) = 'numbers_applications'
									AND y.admin_option = 'NO'
							)
						)
TTT;
				break;
			case 'concat':
				$result = [];
				foreach ($options as $v) {
					$result[] = $v;
				}
				$result = implode(' || ', $result);
				break;
			case 'random':
				if (empty($options['min'])) $options['min'] = 1000;
				if (empty($options['max'])) $options['max'] = 9999;
				$result = "({$options['min']} + RANDOM() * ({$options['max']} - {$options['min']}))::integer";
				break;
			case 'numbers_applications_schemas':
				return $schema_users = <<<TTT
					SELECT
						x.username
					FROM dba_users x
					WHERE EXISTS (
						SELECT
							1
						FROM dba_role_privs y
						WHERE y.grantee = x.username
							AND LOWER(y.granted_role) = 'numbers_applications'
							AND y.admin_option = 'NO'
					)
TTT;
				break;
			// geo functions
			case 'ST_Point':
				$result = "ST_Point({$options['latitude']}, {$options['longitude']})";
				break;
			case 'ST_Contains':
				$result = "ST_Contains({$options['from']}, {$options['to']})";
				break;
			case 'distance_in_meters':
				if (!isset($options['latitude_1'])) $options['latitude_1'] = 0;
				if (!isset($options['longitude_1'])) $options['longitude_1'] = 0;
				if (!isset($options['latitude_2'])) $options['latitude_2'] = 0;
				if (!isset($options['longitude_2'])) $options['longitude_2'] = 0;
				// use geo extension
				if (\Can::submoduleExists('Numbers.Backend.Db.Extension.PostgreSQL.PostGIS')) {
					$result = "ST_Distance(ST_Point({$options['latitude_1']}, {$options['longitude_1']}), ST_Point({$options['latitude_2']}, {$options['longitude_2']}), true)";
				} else {
					$result = "(ACOS(SIN({$options['latitude_1']}) * SIN({$options['latitude_2']}) + COS({$options['latitude_1']}) * COS({$options['latitude_2']}) * COS({$options['longitude_2']} - {$options['longitude_1']})) * 6378.70)";
				}
				break;
			default:
				Throw new \Exception('Statement?');
		}
		return $result;
	}

	/**
	 * Cast
	 *
	 * @param string $column
	 * @param string $type
	 * @return string
	 */
	public function cast(string $column, string $type) : string {
		$type = str_replace(['varchar'], ['varchar2'], $type);
		return "CAST({$column} AS {$type})";
	}

	/**
	 * Full text filtering
	 *
	 * @param mixed $fields
	 * @param string $str
	 * @param string $operator
	 * @param array $options
	 * @return string
	 */
	public function fullTextSearchQuery($fields, $str, $operator = '&', $options = []) {
		$result = [
			'where' => '',
			'orderby' => '',
			'rank' => ''
		];
		$str = trim($str);
		$str_escaped = $this->escape($str);
		$flag_do_not_escape = false;
		if (!empty($fields)) {
			$sql = '';
			$sql2 = '';
			if (is_array($fields)) {
				$sql = "concat_ws(' ', " . implode(', ', $fields) . ')';
				$temp = [];
				foreach ($fields as $f) {
					$temp[] = "$f::text ILIKE '%" . $str_escaped . "%'";
				}
				$sql2 = ' OR (' . implode(' OR ', $temp) . ')';
			} else {
				if (strpos($fields, '::tsvector') !== false) {
					$flag_do_not_escape = true;
				}
				$sql = $fields;
				$sql2 = " OR $fields::text ILIKE '%" . $str_escaped . "%'";
			}
			$escaped = preg_replace('/\s\s+/', ' ', $str);
			if ($escaped == '') {
				$escaped = '*';
			}
			$escaped = str_replace(' ', ":*$operator", $this->escape($escaped)) . ":*";
			if ($flag_do_not_escape) {
				$result['where'] = "($sql @@ to_tsquery('simple', '" . $escaped . "') $sql2)";
				$result['orderby'] = "ts_rank";
				$result['rank'] = "(ts_rank_cd($sql, to_tsquery('simple', '" . $escaped . "'))) ts_rank";
			} else {
				$result['where'] = "(to_tsvector('simple', $sql) @@ to_tsquery('simple', '" . $escaped . "') $sql2)";
				$result['orderby'] = "ts_rank";
				$result['rank'] = "(ts_rank_cd(to_tsvector($sql), to_tsquery('simple', '" . $escaped . "'))) ts_rank";
			}
		}
		return $result;
	}

	/**
	 * Create temporary table
	 *
	 * @param string $table
	 * @param array $columns
	 * @param array $pk
	 * @param array $options
	 *		skip_serials
	 * @return array
	 */
	public function createTempTable($table, $columns, $pk = null, $options = []) {
		$ddl_object = new \Numbers\Backend\Db\Oracle\DDL();
		$columns_sql = [];
		$columns = $temp = \Object\Data\Common::processDomainsAndTypes($columns);
		foreach ($columns as $k => $v) {
			$temp = $ddl_object->columnSqlType($v);
			// default
			$default = $temp['default'] ?? null;
			if (is_string($default) && $default != 'now()') {
				$default = "'" . $default . "'";
			}
			// we need to cancel serial types
			if (!empty($options['skip_serials']) && strpos($temp['sql_type'], 'serial') !== false) {
				$temp['sql_type'] = str_replace('serial', 'int', $temp['sql_type']);
				$default = 0;
			}
			$columns_sql[] = $k . ' ' . $temp['sql_type'] . ($default !== null ? (' DEFAULT ' . $default) : '') . (!($temp['null'] ?? false) ? ' NOT NULL' : '');
		}
		// pk
		if ($pk) {
			$columns_sql[] = "CONSTRAINT {$table}_pk PRIMARY KEY (" . implode(', ', $pk) . ")";
		}
		$columns_sql = implode(', ', $columns_sql);
		$sql = "CREATE TEMP TABLE {$table} ({$columns_sql})";
		return $this->query($sql);
	}

	/**
	 * Query builder - render
	 *
	 * @param \Numbers\Backend\Db\Common\Query\Builder $object
	 * @return array
	 */
	public function queryBuilderRender(\Numbers\Backend\Db\Common\Query\Builder $object) : array {
		$result = [
			'success' => false,
			'error' => [],
			'sql' => ''
		];
		$sql = '';
		// comments always first
		if (!empty($object->data['comment'])) {
			$sql.= "/* " . $object->data['comment'] . " */\n";
		}
		switch ($object->data['operator']) {
			case 'update':
				$sql.= "UPDATE ";
				// from
				if (empty($object->data['from'])) {
					$result['error'][] = 'From?';
				} else {
					$temp = [];
					foreach ($object->data['from'] as $k => $v) {
						// todo - $v can be subquery
						$temp2 = $v;
						if (!is_numeric($k)) {
							$temp2.= " $k";
						}
						$temp[] = $temp2;
					}
					$sql.= implode(",\n", $temp);
				}
				// set
				if (empty($object->data['set'])) {
					$result['error'][] = 'Set?';
				} else {
					$sql.= "\nSET ";
					$sql.= $this->prepareCondition($object->data['set'], ",\n\t");
				}
				// limit
				if (!empty($object->data['limit'])) {
					array_push($object->data['where'], ['AND', '', "ROWNUM <= {$object->data['limit']}", '']);
				}
				// where
				if (!empty($object->data['where'])) {
					$sql.= "\nWHERE";
					$sql.= $object->renderWhere($object->data['where']);
				}
				break;
			case 'insert':
				$sql.= "INSERT INTO ";
				// from
				if (empty($object->data['from'])) {
					$result['error'][] = 'From?';
				} else {
					$temp = [];
					foreach ($object->data['from'] as $k => $v) {
						$temp[] = $v;
					}
					$sql.= implode(",\n", $temp);
				}
				// columns
				if (empty($object->data['columns'])) {
					$result['error'][] = 'Columns?';
				} else {
					$sql.= " (\n\t" . $this->prepareExpression($object->data['columns'], ",\n\t") . "\n)\n";
				}
				// values
				if (empty($object->data['values'])) {
					$result['error'][] = 'Values?';
				} else {
					if (is_array($object->data['values'])) {
						// regular insert
						if (count($object->data['values']) == 1) {
							$sql.= "VALUES";
							$temp = [];
							foreach ($object->data['values'] as $v) {
								$temp[] = "\n\t(" . $this->prepareValues($v) . ")";
							}
							$sql.= implode(",", $temp);
						} else {
							$sql = 'INSERT ALL';
							$table = [];
							foreach ($object->data['from'] as $k => $v) {
								$table[] = $v;
							}
							$table = implode(", ", $table);
							foreach ($object->data['values'] as $v) {
								$sql.= "\n\t INTO " . $table . " (" . $this->prepareExpression($object->data['columns'], ", ") . ")";
								$sql.= " VALUES (" . $this->prepareValues($v) . ")";
							}
							$sql.= "\nSELECT 1 FROM DUAL";
						}
					} else {
						// regular sql query
						$sql.= $object->data['values'];
					}
				}
				break;
			case 'delete':
				// from
				if (empty($object->data['from'])) {
					$result['error'][] = 'From?';
				} else {
					$sql.= "DELETE FROM " . current($object->data['from']);
					$sql2 = 'SELECT ' . implode(', ', $object->data['primary_key']) . ' FROM ' . current($object->data['from']);
					// where
					if (!empty($object->data['where'])) {
						$sql2.= ' WHERE ' . $object->renderWhere($object->data['where']);
					}
					// orderby
					if (!empty($object->data['orderby'])) {
						$sql2.= ' ORDER BY ' . array_key_sort_prepare_keys($object->data['orderby'], true);
					}
					// limit
					if (!empty($object->data['limit'])) {
						$sql2.= ' FETCH FIRST ' . $object->data['limit'] . ' ROWS ONLY';
					}
					$sql.= "\nWHERE (" . implode(', ', $object->data['primary_key']) . ") IN (" . $sql2 . ")";
				}
				break;
			case 'truncate':
				if (empty($object->data['from'])) {
					$result['error'][] = 'From?';
				} else {
					$sql.= "TRUNCATE TABLE " . current($object->data['from']);
				}
				break;
			case 'select':
				// temporary table first
				if (!empty($object->data['temporary_table'])) {
					$sql.= "CREATE TEMPORARY TABLE {$object->data['temporary_table']} AS\n";
					// Oracle supports PRIVATE since 18c
				}
				// select with distinct
				$sql.= "SELECT" . (!empty($object->data['distinct']) ? ' DISTINCT ' : '') . "\n";
				// columns
				if (empty($object->data['columns'])) {
					$sql.= "\t*";
				} else {
					$temp = [];
					foreach ($object->data['columns'] as $k => $v) {
						// todo - $v can be subquery
						$temp2 = "\t" . $v;
						if (!is_numeric($k)) {
							$temp2.= " AS $k";
						}
						$temp[] = $temp2;
					}
					$sql.= implode(",\n", $temp);
				}
				// from
				if (!empty($object->data['from'])) {
					$sql.= "\nFROM ";
					$temp = [];
					foreach ($object->data['from'] as $k => $v) {
						// todo - $v can be subquery
						$temp2 = $v;
						if (!is_numeric($k)) {
							$temp2.= " $k";
						}
						$temp[] = $temp2;
					}
					$sql.= implode(",\n", $temp);
				}
				// join
				if (!empty($object->data['join'])) {
					foreach ($object->data['join'] as $k => $v) {
						$type = $v['type'];
						if (!empty($type)) $type.= ' ';
						$alias = $v['alias'];
						if (!empty($alias)) {
							$alias = ' ' . $alias . ' ';
						} else {
							$alias = ' ';
						}
						$where = '';
						if (!empty($v['conditions'])) {
							$where = $object->renderWhere($v['conditions']);
						}
						$sql.= "\n{$type}JOIN {$v['table']}{$alias}{$v['on']}{$where}";
					}
				}
				// for update with limit we must use ROWNUM in where
				if (!empty($object->data['for_update']) && !empty($object->data['limit'])) {
					array_push($object->data['where'], ['AND', '', "ROWNUM <= {$object->data['limit']}", '']);
					$object->data['limit'] = 0;
				}
				// where
				if (!empty($object->data['where'])) {
					$sql.= "\nWHERE";
					$sql.= $object->renderWhere($object->data['where']);
				}
				// group by
				if (!empty($object->data['groupby'])) {
					$sql.= "\nGROUP BY " . implode(",\n\t", $object->data['groupby']);
				}
				// having
				if (!empty($object->data['having'])) {
					$sql.= "\nHAVING";
					$sql.= $object->renderWhere($object->data['having']);
				}
				// orderby
				if (!empty($object->data['orderby'])) {
					$sql.= "\nORDER BY " . array_key_sort_prepare_keys($object->data['orderby'], true);
				}
				// offset
				if (!empty($object->data['offset'])) {
					$sql.= "\nOFFSET " . $object->data['offset'] . " ROWS";
				}
				// limit
				if (!empty($object->data['limit'])) {
					$sql.= "\nFETCH FIRST " . $object->data['limit'] . " ROWS ONLY";
				}
				// for update
				if (!empty($object->data['for_update'])) {
					$sql.= "\nFOR UPDATE";
				}
				// union
				if (!empty($object->data['union'])) {
					foreach ($object->data['union'] as $k => $v) {
						$sql.= "\n\n";
						$sql.= $v['type'];
						$sql.= "\n\n";
						$sql.= $v['select'];
					}
				}
			default:
				/* nothing */
		}
		// final processing
		if (empty($result['error'])) {
			$result['success'] = true;
			$result['sql'] = $sql;
		}
		return $result;
	}

	/**
	 * @see parent::prepareValues()
	 */
	public function prepareValues($options) {
		$this->prepareOracleFixes($options);
		return parent::prepareValues($options);
	}

	/**
	 * @see parent::prepareCondition()
	 */
	public function prepareCondition($options, $delimiter = 'AND') {
		if (strpos($delimiter, ',') !== false) {
			$this->prepareOracleFixes($options);
		}
		return parent::prepareCondition($options, $delimiter);
	}

	/**
	 * Prepare Oracle fixes
	 *
	 * @param array $options
	 */
	private function prepareOracleFixes(& $options) {
		foreach ($options as $k => $v) {
			$key = explode(';', $k);
			if (is_array($v)) {
				$encoded_v = json_encode($v);
			} else {
				$encoded_v = $v;
			}
			if (is_string($v) && $v === '') {
				$options[$k] = '__EMPTY_STR__';
			} else if ((is_string($v) || is_array($v)) && strlen($encoded_v) > 1000) {
				$result = [];
				$key[1] = $key[1] ?? '';
				$key[2] = '~~';
				$temp2 = str_split($encoded_v, 1000);
				foreach ($temp2 as $v2) {
					$result[] = "to_clob('" . $this->escape($v2) . "')";
				}
				// we must set it to old key and then change key
				$options[$k] = implode(' || ', $result);
				$options = array_change_key_name($options, $k, implode(';', $key));
			}
		}
	}
}