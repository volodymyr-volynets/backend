<?php

class numbers_backend_db_pgsql_base extends numbers_backend_db_class_base implements numbers_backend_db_interface_base {

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
		'like' => 'ILIKE'
	];

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
		if (is_array($options)) {
			$str = 'host=' . $options['host'] . ' port=' . $options['port'] . ' dbname=' . $options['dbname'] . ' user=' . $options['username'] . ' password=' . $options['password'];
		} else {
			$str = $options;
		}
		$connection = @pg_connect($str);
		if ($connection !== false) {
			$this->db_resource = $connection;
			$this->commit_status = 0;
			pg_set_error_verbosity($connection, PGSQL_ERRORS_VERBOSE);
			pg_set_client_encoding($connection, 'UNICODE');
			$result['version'] = pg_version($connection);
			$result['status'] = pg_connection_status($connection) === PGSQL_CONNECTION_OK ? 1 : 0;
			// set settings
			$this->query("SET TIME ZONE '" . Application::get('php.date.timezone') . "';");
			// success
			$result['success'] = true;
		} else {
			$result['error'][] = 'db::connect() : Could not connect to database server!';
			$result['errno'] = 1;
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
			pg_close($this->db_resource);
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
	public function field_structures($resource) {
		$result = [];
		if ($resource) {
			for ($i = 0; $i < pg_num_fields($resource); $i++) {
				$name = pg_field_name($resource, $i);
				$result[$name]['type'] = pg_field_type($resource, $i);
				$result[$name]['null'] = pg_field_is_null($resource, $i);
				$result[$name]['length'] = pg_field_size($resource, $i);
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
			'time' => microtime(true),
			'sql' => $sql,
			'key' => $key,
			'backtrace' => null
		];
		// if query caching is enabled
		if (!empty($this->options['cache_link'])) {
			$cache_id = !empty($options['cache_id']) ? $options['cache_id'] : 'db_query_' . sha1($sql . serialize($key));
			// if we cache this query
			if (!empty($options['cache'])) {
				$cache_object = new cache($this->options['cache_link']);
				$cached_result = $cache_object->get($cache_id, true);
				if ($cached_result !== false) {
					// if we are debugging
					if (debug::$debug) {
						debug::$data['sql'][] = $cached_result;
					}
					return $cached_result;
				}
			}
		} else {
			$options['cache'] = false;
		}
		// quering
		$resource = @pg_query($this->db_resource, $sql);
		$result['status'] = pg_result_status($resource);
		if (!$resource || $result['status'] > 4) {
			$last_error = pg_last_error($this->db_resource);
			if (empty($last_error)) {
				$errno = 1;
				$error = 'DB Link ' . $this->db_link . ': ' . 'Unspecified error!';
			} else {
				preg_match("|ERROR:\s(.*?):|i", $last_error, $matches);
				$errno = !empty($matches[1]) ? $matches[1] : 1;
				$error = $last_error;
			}
			$this->error_overrides($result, $errno, $error);
		} else {
			$result['affected_rows'] = pg_affected_rows($resource);
			$result['num_rows'] = pg_num_rows($resource);
			$result['structure'] = $this->field_structures($resource);
			if ($result['num_rows'] > 0) {
				while ($rows = pg_fetch_assoc($resource)) {
					// transforming pg arrays to php arrays and casting types
					foreach ($rows as $k => $v) {
						if ($result['structure'][$k]['type'][0] == '_') {
							$rows[$k] = $this->pg_parse_array($v);
						} else if (in_array($result['structure'][$k]['type'], ['int2', 'int4', 'int8'])) {
							if (!is_null($v)) {
								$rows[$k] = (int) $v;
							}
						} else if (in_array($result['structure'][$k]['type'], ['real', 'double precision'])) {
							if (!is_null($v)) {
								$rows[$k] = (float) $v;
							}
						} else if ($result['structure'][$k]['type'] == 'bytea') {
							$rows[$k] = pg_unescape_bytea($v);
						} else if ($result['structure'][$k]['type'] == 'jsonb') {
							// we must get json vallues to PHP format
							$rows[$k] = json_encode(json_decode($v, true));
						}
					}
					// assigning keys
					if (!empty($key)) {
						array_key_set_by_key_name($result['rows'], $key, $rows);
					} else {
						$result['rows'][] = $rows;
					}
				}
			}
			pg_free_result($resource);
			$result['success'] = true;
		}
		// time before caching
		$result['time'] = microtime(true) - $result['time'];
		// prepend backtrace in debug mode to know where it was cached
		if (debug::$debug) {
			$result['backtrace']  = implode("\n", \Object\Error\Base::debug_backtrace_string());
		}
		// caching if no error
		if (!empty($options['cache']) && empty($result['error'])) {
			$result['cache'] = true;
			$cache_object->set($cache_id, $result, null, $options['cache_tags'] ?? null);
		}
		// if we are debugging
		if (debug::$debug) {
			debug::$data['sql'][] = $result;
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
				Throw new Exception('Could not start transaction: ' . implode(', ', $result['error']));
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
				Throw new Exception('Could not commit transaction: ' . implode(', ', $result['error']));
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
			Throw new Exception('Could not rollback transaction: ' . implode(', ', $result['error']));
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
		return pg_escape_string($this->db_resource, $value);
	}

	/**
	 * Parsing pg array string into array
	 *
	 * @param string $arraystring
	 * @param boolean $reset
	 * @return array
	 */
	public function pg_parse_array($arraystring, $reset = true) {
		static $i = 0;
		if ($reset) {
			$i = 0;
		}
		$matches = [];
		$indexer = 0; // by default sql arrays start at 1
		// handle [0,2]= cases
		if (preg_match('/^\[(?P<index_start>\d+):(?P<index_end>\d+)]=/', substr($arraystring, $i), $matches)) {
			$indexer = (int) $matches['index_start'];
			$i = strpos($arraystring, '{');
		}
		if ($arraystring[$i] != '{') {
			return [];
		}
		$i++;
		$work = [];
		$curr = '';
		$length = strlen($arraystring);
		$count = 0;
		while ($i < $length) {
			switch ($arraystring[$i]) {
				case '{':
					$sub = $this->pg_parse_array($arraystring, false);
					if (!empty($sub)) {
						$work[$indexer++] = $sub;
					}
					break;
				case '}':
					$i++;
					//if ($curr<>'')
					$work[$indexer++] = $curr;
					return $work;
					break;
				case '\\':
					$i++;
					$curr.= $arraystring[$i];
					$i++;
					break;
				case '"':
					$openq = $i;
					do {
						$closeq = strpos($arraystring, '"', $i + 1);
						if ($closeq > $openq && $arraystring[$closeq - 1] == '\\') {
							$i = $closeq + 1;
						} else {
							break;
						}
					} while (true);
					if ($closeq <= $openq) {
						die;
					}
					$curr.= substr($arraystring, $openq + 1, $closeq - ($openq + 1));
					$i = $closeq + 1;
					break;
				case ',':
					//if ($curr<>'')
					$work[$indexer++] = $curr;
					$curr = '';
					$i++;
					break;
				default:
					$curr.= $arraystring[$i];
					$i++;
			}
		}
	}

	/**
	 * @see db::sequence();
	 */
	public function sequence($sequence_name, $type = 'nextval', $tenant = null, $module = null) {
		$query = new object_query_builder($this->db_link);
		// extended sequence
		if (isset($tenant) || isset($module)) {
			$tenant = (int) $tenant;
			$module = (int) $module;
			$query->columns([
				'counter' => "{$type}_extended('{$sequence_name}'::character varying, {$tenant}, {$module})"
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
	public function sql_helper($statement, $options = []) {
		$result = '';
		switch ($statement) {
			case 'string_agg':
				$result = 'string_agg(' . $options['expression'] . ', \'' . ($options['delimiter'] ?? ';') . '\')';
				break;
			case 'fetch_databases':
				$result = 'SELECT datname database_name FROM pg_database WHERE datistemplate = false ORDER BY database_name ASC';
				break;
			case 'fetch_tables':
				$result = <<<TTT
					SELECT
						CASE WHEN schemaname = 'public' THEN '' ELSE schemaname END schema_name,
						tablename table_name
					FROM pg_tables a
					WHERE 1=1
						AND schemaname NOT IN ('pg_catalog', 'information_schema')
					ORDER BY schema_name, table_name
TTT;
				break;
			default:
				Throw new Exception('Statement?');
		}
		return $result;
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
	public function full_text_search_query($fields, $str, $operator = '&', $options = []) {
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
	public function create_temp_table($table, $columns, $pk = null, $options = []) {
		$ddl_object = Factory::model(str_replace('_base_123', '_ddl', get_called_class() . '_123'));
		$columns_sql = [];
		foreach ($columns as $k => $v) {
			$temp = $ddl_object->is_column_type_supported($v, $table);
			// default
			$default = $temp['column']['default'] ?? null;
			if (is_string($default) && $default != 'now()') {
				$default = "'" . $default . "'";
			}
			// we need to cancel serial types
			if (!empty($options['skip_serials']) && strpos($temp['column']['type'], 'serial') !== false) {
				$temp['column']['type'] = str_replace('serial', 'int', $temp['column']['type']);
				$default = 0;
			}
			$columns_sql[] = $k . ' ' . $temp['column']['type'] . ($default !== null ? (' DEFAULT ' . $default) : '') . (!($temp['column']['null'] ?? false) ? ' NOT NULL' : '');
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
	 * @param numbers_backend_db_class_query_builder $object
	 * @return array
	 */
	public function query_builder_render(numbers_backend_db_class_query_builder $object) : array {
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
					$sql.= $this->prepare_condition($object->data['set'], ",\n\t");
				}
				// where
				if (!empty($object->data['where'])) {
					$sql.= "\nWHERE";
					$sql.= $object->render_where($object->data['where']);
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
					$sql.= " (\n\t" . $this->prepare_expression($object->data['columns'], ",\n\t") . "\n)\n";
				}
				// values
				if (empty($object->data['values'])) {
					$result['error'][] = 'Values?';
				} else {
					if (is_array($object->data['values'])) {
						$sql.= "VALUES";
						$temp = [];
						foreach ($object->data['values'] as $v) {
							$temp[] = "\n\t(" . $this->prepare_values($v) . ")";
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
					$sql.= $object->render_where($object->data['where']);
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
							$where = $object->render_where($v['conditions']);
						}
						$sql.= "\n{$type}JOIN {$v['table']}{$alias}{$v['on']}{$where}";
					}
				}
				// where
				if (!empty($object->data['where'])) {
					$sql.= "\nWHERE";
					$sql.= $object->render_where($object->data['where']);
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
		}
		// final processing
		if (empty($result['error'])) {
			$result['success'] = true;
			$result['sql'] = $sql;
		}
		return $result;
	}
}