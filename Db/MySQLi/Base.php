<?php

namespace Numbers\Backend\Db\MySQLi;
class Base extends \Numbers\Backend\Db\Common\Base implements \Numbers\Backend\Db\Common\Interface2\Base {

	/**
	 * Error overrides
	 *
	 * @var array
	 */
	public $error_overrides = [
		'1451' => 'The record you are trying to delete is used in other areas, please unset it there first.',
		'1062' => 'Duplicate key value violates unique constraint.',
	];

	/**
	 * Backend
	 *
	 * @var string
	 */
	public $backend = 'MySQLi';

	/**
	 * Constructing database object
	 *
	 * @param string $db_link
	 */
	public function __construct($db_link) {
		$this->db_link = $db_link;
	}

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
		// we could pass an array or connection string right a way
		$connection = mysqli_connect($options['host'], $options['username'], $options['password'], $options['dbname'], $options['port']);
		if ($connection) {
			$this->db_resource = $connection;
			$this->connect_options = $options;
			$this->commit_status = 0;
			mysqli_set_charset($connection, 'utf8');
			$result['version'] = mysqli_get_server_version($connection);
			$result['status'] = 1;
			// set settings
			//$this->query("SET time_zone = '" . \Application::get('php.date.timezone') . "';");
			// success
			$result['success'] = true;
		} else {
			$result['error'][] = mysqli_connect_error();
			$result['errno'] = mysqli_connect_errno();
		}
		return $result;
	}

	/**
	 * Closes a connection
	 *
	 * @return array
	 */
	public function close() {
		if (!empty($this->db_resource)) {
			mysqli_close($this->db_resource);
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
			while ($finfo = mysqli_fetch_field($resource)) {
				$result[$finfo->name]['type'] = $this->fieldType($finfo->type);
				$result[$finfo->name]['null'] = ($finfo->flags & 1 ? false : true);
				$result[$finfo->name]['length'] = $finfo->length;
			}
		}
		return $result;
	}

	/**
	 * Determine field type
	 *
	 * @staticvar array $types
	 * @param int $type_id
	 * @return string
	 */
	public function fieldType($type_id) {
		static $types;
		if (!isset($types)) {
			$types = [];
			$constants = get_defined_constants(true);
			foreach ($constants['mysqli'] as $k => $v) {
				if (preg_match('/^MYSQLI_TYPE_(.*)/', $k, $m)) {
					$types[$v] = strtolower($m[1]);
				}
			}
		}
		return array_key_exists($type_id, $types) ? $types[$type_id] : null;
	}

	/**
	 * Process value as per type
	 *
	 * @param mixed $value
	 */
	private function processValueAsPerType($value, $type) {
		if (in_array($type, ['char', 'short', 'long', 'longlong'])) {
			if (!is_null($value)) {
				return (int) $value;
			} else {
				return null;
			}
		} else if (in_array($type, ['float', 'double'])) {
			if (!is_null($value)) {
				return (float) $value;
			} else {
				return null;
			}
		} else if (in_array($type, ['numeric', 'decimal', 'newdecimal'])) {
			if (!is_null($value)) {
				return (string) $value;
			} else {
				return null;
			}
		} else if ($type == 'json') {
			// we must convert to PHP json
			return json_encode(json_decode($value, true));
		} else {
			return $value;
		}
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
			'sql' => $sql,
			'error' => [],
			'errno' => 0,
			'num_rows' => 0,
			'affected_rows' => 0,
			'rows' => [],
			'key' => $key,
			'structure' => [],
			'time' => microtime(true),
			'last_insert_id' => 0
		];
		// if query caching is enabled
		if (!empty($this->options['cache_link'])) {
			$cache_id = !empty($options['cache_id']) ? $options['cache_id'] : 'Db_Query_' . sha1($sql . serialize($key));
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
		// quering regular query first
		if (empty($options['multi_query'])) {
			$resource = mysqli_query($this->db_resource, $sql);
			if (!$resource) {
				$this->errorOverrides($result, mysqli_errno($this->db_resource), mysqli_error($this->db_resource));
			} else {
				$result['affected_rows']+= mysqli_affected_rows($this->db_resource);
				if ($resource !== true) {
					$result['num_rows']+= mysqli_num_rows($resource);
					$result['structure'] = $this->fieldStructures($resource);
				}
				if ($result['num_rows'] > 0) {
					while ($rows = mysqli_fetch_assoc($resource)) {
						// casting types
						$data = [];
						foreach ($rows as $k => $v) {
							$data[$k] = $this->processValueAsPerType($v, $result['structure'][$k]['type']);
						}
						// assigning keys
						if (!empty($key)) {
							array_key_set_by_key_name($result['rows'], $key, $data);
						} else {
							$result['rows'][] = $data;
						}
					}
				}
				if ($resource !== true) {
					mysqli_free_result($resource);
				}
				$result['success'] = true;
			}
		} else {
			// multi query
			$resource = mysqli_multi_query($this->db_resource, $sql);
			if (!$resource) {
				$result['error'][] = 'Db Link ' . $this->db_link . ': ' . mysqli_error($this->db_resource);
				$result['errno'] = mysqli_errno($this->db_resource);
				// we log this error message
				// todo: process log policy here
				error_log('Query error: ' . implode(' ', $result['error']) . ' [' . $sql . ']');
			} else {
				$result['affected_rows']+= mysqli_affected_rows($this->db_resource);
				do {
					if ($result_multi = mysqli_store_result($this->db_resource)) {
						if ($result_multi) {
							$result['num_rows']+= $num_rows = mysqli_num_rows($result_multi);
							$result['structure'] = $this->fieldStructures($result_multi);
						} else {
							$num_rows = 0;
							$result['error'][] = 'Db Link ' . $this->db_link . ': Multi query error!';
							$result['errno'] = 1;
							// we log this error message
							// todo: process log policy here
							error_log('Query error: ' . implode(' ', $result['error']) . ' [' . $sql . ']');
						}
						if ($num_rows > 0) {
							while ($rows = mysqli_fetch_assoc($result_multi)) {
								// casting types
								$data = [];
								foreach ($rows as $k => $v) {
									$data[$k] = $this->processValueAsPerType($v, $result['structure'][$k]['type']);
								}
								// assigning keys
								if (!empty($key)) {
									array_key_set_by_key_name($result['rows'], $key, $data);
								} else {
									$result['rows'][] = $data;
								}
							}
						}
						mysqli_free_result($result_multi);
					}
				} while (mysqli_more_results($this->db_resource) && mysqli_next_result($this->db_resource));
				if (empty($result['error'])) {
					$result['success'] = true;
				}
			}
		}
		// last insert id for auto increment columns
		$result['last_insert_id'] = mysqli_insert_id($this->db_resource);
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
			$result = $this->query('BEGIN');
			if (!$result['success']) {
				Throw new \Exception('Could not start transaction: ' . implode(', ', $result['error']));
			}
			return $result;
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
		return mysqli_real_escape_string($this->db_resource, $value);
	}

	/**
	 *	@see db::sequence();
	 */
	public function sequence($sequence_name, $type = 'nextval') {
		$query = new \Object\Query\Builder($this->db_link);
		// extended sequence
		if (isset($tenant) || isset($module)) {
			$tenant = (int) $tenant;
			$module = (int) $module;
			$query->columns([
				'counter' => "{$type}_extended('{$sequence_name}', {$tenant}, {$module})"
			]);
		} else { // regular sequence
			$query->columns([
				'counter' => "{$type}('{$sequence_name}')"
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
				$result = 'GROUP_CONCAT(' . $options['expression'] . ' SEPARATOR \'' . ($options['delimiter'] ?? ';') . '\')';
				break;
			case 'fetch_databases':
				$result = 'SELECT schema_name AS database_name FROM information_schema.schemata ORDER BY database_name ASC';
				break;
			case 'fetch_tables':
				$result = <<<TTT
					SELECT
						'' schema_name,
						table_name
					FROM information_schema.tables
					WHERE 1=1
						AND table_schema = (SELECT DATABASE())
						AND engine IS NOT NULL
					ORDER BY schema_name, table_name
TTT;
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
		$type = str_replace(['character varying'], ['char'], $type);
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
		$mode = $options['mode'] ?? 'IN NATURAL LANGUAGE MODE';
		$str = trim($str);
		$flag_do_not_escape = false;
		if (!empty($fields)) {
			$sql = '';
			if (is_array($fields)) {
				$sql = implode(', ', $fields);
			} else {
				$sql = $fields;
			}
			$escaped = preg_replace('/\s\s+/', ' ', $str);
			$escaped = str_replace(' ', ',', $escaped);
			$where = "MATCH ({$sql}) AGAINST ('" . $this->escape($escaped) . "' {$mode})";
			$temp = [];
			foreach ($fields as $f) {
				$temp[] = "{$f} LIKE '%" . $this->escape($str) . "%'";
			}
			$sql2 = ' OR (' . implode(' OR ', $temp) . ')';
			$result['where'] = "(" . $where . $sql2 . ")";
			$result['orderby'] = 'ts_rank';
			$result['rank'] = '(' . $where . ') ts_rank';
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
		$ddl_object = new \Numbers\Backend\Db\MySQLi\DDL();
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
			$columns_sql[] = "PRIMARY KEY (" . implode(', ', $pk) . ")";
		}
		$columns_sql = implode(', ', $columns_sql);
		$sql = "CREATE TEMPORARY TABLE {$table} ({$columns_sql})";
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
							$temp2.= " AS $k";
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
				// where
				if (!empty($object->data['where'])) {
					$sql.= "\nWHERE";
					$sql.= $object->renderWhere($object->data['where']);
				}
				// limit
				if (!empty($object->data['limit'])) {
					$sql.= "\nLIMIT " . $object->data['limit'];
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
						$sql.= "VALUES";
						$temp = [];
						foreach ($object->data['values'] as $v) {
							$temp[] = "\n\t(" . $this->prepareValues($v) . ")";
						}
						$sql.= implode(",", $temp);
					} else {
						// regular sql query
						$sql.= $object->data['values'];
					}
				}
				break;
			case 'delete':
				$sql.= "DELETE FROM ";
				// from
				if (empty($object->data['from'])) {
					$result['error'][] = 'From?';
				} else {
					$temp = [];
					foreach ($object->data['from'] as $k => $v) {
						$temp2 = $v;
						if (!is_numeric($k)) {
							$temp2.= " AS $k";
						}
						$temp[] = $temp2;
					}
					$sql.= implode(",", $temp);
				}
				// where
				if (!empty($object->data['where'])) {
					$sql.= "\nWHERE";
					$sql.= $object->renderWhere($object->data['where']);
				}
				// limit
				if (!empty($object->data['limit'])) {
					$sql.= "\nLIMIT " . $object->data['limit'];
				}
				// returning
				if (!empty($object->data['returning'])) {
					$sql.= "\nRETURNING *";
				}
				break;
			case 'select':
			default:
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
							$temp2.= " AS `$k`";
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
							$temp2.= " AS $k";
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
				// where
				if (!empty($object->data['where'])) {
					$sql.= "\nWHERE";
					$sql.= $object->renderWhere($object->data['where']);
				}
				// group by
				if (!empty($object->data['groupby'])) {
					$sql.= "\nGROUP BY " . implode(",\n\t", $object->data['groupby']);
				}
				// orderby
				if (!empty($object->data['orderby'])) {
					$sql.= "\nORDER BY " . array_key_sort_prepare_keys($object->data['orderby'], true);
				}
				// offset
				if (!empty($object->data['offset'])) {
					$sql.= "\nOFFSET " . $object->data['offset'];
				}
				// limit
				if (!empty($object->data['limit'])) {
					$sql.= "\nLIMIT " . $object->data['limit'];
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
		}
		// final processing
		if (empty($result['error'])) {
			$result['success'] = true;
			$result['sql'] = $sql;
		}
		return $result;
	}
}