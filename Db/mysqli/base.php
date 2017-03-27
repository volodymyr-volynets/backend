<?php

class numbers_backend_db_mysqli_base extends numbers_backend_db_class_base implements numbers_backend_db_interface_base {

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
	public function connect($options) {
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
			$this->query("SET time_zone = '" . Application::get('php.date.timezone') . "';");
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
	public function field_structures($resource) {
		$result = [];
		if ($resource) {
			while ($finfo = mysqli_fetch_field($resource)) {
				$result[$finfo->name]['type'] = $this->field_type($finfo->type);
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
	public function field_type($type_id) {
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
	private function process_value_as_per_type($value, $type) {
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
	public function query($sql, $key = null, $options = []) {
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
			'time' => null,
			'last_insert_id' => 0
		];

		// start time
		$result['time'] = debug::get_microtime();

		// if query caching is enabled
		if (!empty($this->connect_options['cache_link'])) {
			$cache_id = !empty($options['cache_id']) ? $options['cache_id'] : 'db_query_' . sha1($sql . serialize($key));
			// if we cache this query
			if (!empty($options['cache'])) {
				$cache_object = new cache($this->connect_options['cache_link']);
				$cached_result = $cache_object->get($cache_id);
				if ($cached_result !== false) {
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
				$this->error_overrides($result, mysqli_errno($this->db_resource), mysqli_error($this->db_resource));
			} else {
				$result['affected_rows']+= mysqli_affected_rows($this->db_resource);
				if ($resource !== true) {
					$result['num_rows']+= mysqli_num_rows($resource);
					$result['structure'] = $this->field_structures($resource);
				}
				if ($result['num_rows'] > 0) {
					while ($rows = mysqli_fetch_assoc($resource)) {
						// casting types
						$data = [];
						foreach ($rows as $k => $v) {
							$data[$k] = $this->process_value_as_per_type($v, $result['structure'][$k]['type']);
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
							$result['structure'] = $this->field_structures($result_multi);
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
									$data[$k] = $this->process_value_as_per_type($v, $result['structure'][$k]['type']);
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
		// caching if no error
		if (!empty($options['cache']) && empty($result['error'])) {
			$cache_object->set($cache_id, $result, ['tags' => $options['cache_tags'] ?? null]);
		}

		// end time
		$result['time'] = debug::get_microtime() - $result['time'];

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
		return mysqli_real_escape_string($this->db_resource, $value);
	}

	/**
	 * Insert multiple rows to database
	 *
	 * @param string $table
	 * @param array $rows
	 * @return array
	 */
	public function insert($table, $rows, $keys = null, $options = []) {
		$temp = current($rows);
		$headers = $this->prepare_keys(array_keys($temp));
		$sql = "INSERT INTO $table (" . $this->prepareExpression($headers) . ") VALUES ";
		$sql_values = [];
		foreach ($rows as $k => $v) {
			$sql_values[] = "(" . $this->prepareValues($v) . ")";
		}
		$sql.= implode(', ', $sql_values);
		return $this->query($sql, $this->prepare_keys($keys));
	}

	/**
	 * Update table
	 *
	 * @param string $table
	 * @param array $data
	 * @param mixed $keys
	 * @param array $options
	 * @return array
	 */
	public function update($table, $data, $keys, $options = []) {
		// fixing keys
		$keys = array_fix($keys);
		// where clause
		if (!empty($options['where'])) {
			$where = $options['where'];
		} else {
			$where = [];
			foreach ($keys as $key) {
				$where[$key] = array_key_exists($key, $data) ? $data[$key] : null;
				unset($data[$key]);
			}
		}
		// assembling query
		$sql = "UPDATE $table SET " . $this->prepare_condition($data, ', ') . ' WHERE ' . $this->prepare_condition($where, 'AND');
		return $this->query($sql, $this->prepare_keys($keys));
	}

	/**
	 * Delete rows from table
	 *
	 * @param string $table
	 * @param array $data
	 * @param mixed $keys
	 * @param array $options
	 * @return array
	 */
	public function delete($table, $data, $keys, $options = []) {
		// fixing keys
		$keys = array_fix($keys);
		// where clause
		if (!empty($options['where'])) {
			$where = $options['where'];
		} else {
			$where = [];
			foreach ($keys as $key) {
				$where[$key] = array_key_exists($key, $data) ? $data[$key] : null;
				unset($data[$key]);
			}
		}
		// assembling query
		$sql = "DELETE FROM $table WHERE " . $this->prepare_condition($where, 'AND');
		return $this->query($sql, $this->prepare_keys($keys));
	}

	/**
	 * Save row to database
	 *
	 * @param string $table
	 * @param array $data
	 * @param mixed $keys
	 * @param array $options
	 * @return boolean
	 */
	public function save($table, $data, $keys, $options = []) {
		do {
			// fixing keys
			$keys = array_fix($keys);

			// where clause
			$where = [];
			$empty = true;
			foreach ($keys as $key) {
				if (!empty($data[$key])) {
					$empty = false;
				}
				$where[$key] = array_key_exists($key, $data) ? $data[$key] : null;
			}

			// if keys are empty we must insert
			$row_found = false;
			if (!$empty) {
				$result = $this->query("SELECT * FROM $table WHERE " . $this->prepare_condition($where, 'AND'));
				if (!$result['success']) {
					break;
				} else if ($result['num_rows']) {
					$row_found = true;
				}
			}

			// if we are in inser mode we exit
			if ($row_found && !empty($options['flag_insert_only'])) {
				$result['success'] = true;
				break;
			}

			// if row found we update
			if ($row_found) {
				$flag_inserted = false;
				$sql = "UPDATE $table SET " . $this->prepare_condition($data, ', ') . ' WHERE ' . $this->prepare_condition($where, 'AND');
			} else {
				$flag_inserted = true;
				// we need to unset key fields
				if ($empty) {
					foreach ($keys as $key) {
						unset($data[$key]);
					}
				}
				// if we have a sequence
				if (!empty($options['sequences'])) {
					foreach ($options['sequences'] as $k => $v) {
						if (empty($data[$k])) {
							$temp = $this->sequence($v['sequence_name']);
							$data[$k] = $temp['rows'][0]['counter'];
						}
					}
				}
				// we insert
				$sql = "INSERT INTO $table (" . $this->prepareExpression(array_keys($data)) . ') VALUES (' . $this->prepareValues($data) . ')';
			}
			$result = $this->query($sql, $this->prepare_keys($keys));
			if ($result['success']) {
				$result['inserted'] = $flag_inserted;
			}
			// processing returning clause last
			$temp = $this->query("SELECT * FROM $table WHERE " . $this->prepare_condition($where, 'AND'));
			if ($temp['success']) {
				$result['rows'] = $temp['rows'];
				$result['num_rows'] = $temp['num_rows'];
			}
		} while (0);
		return $result;
	}

	/**
	 *	@see db::sequence();
	 */
	public function sequence($sequence_name, $type = 'nextval') {
		$sequence_model = new \Numbers\Backend\Db\Common\Model\Sequences();
		$sql = <<<TTT
			SET @next_sequence = {$type}('{$sequence_name}');
			SELECT
				*,
				@next_sequence counter
			FROM
				{$sequence_model->name}
			WHERE 1=1
					AND sm_sequence_name = '{$sequence_name}';
TTT;
		return $this->query($sql, null, ['multi_query' => true]);
	}

	/**
	 * SQL helper
	 *
	 * @param string $statement
	 * @param array $options
	 * @return string
	 */
	public function sql_helper($statement, $options) {
		$result = '';
		switch ($statement) {
			case 'string_agg':
				$result = 'GROUP_CONCAT(' . $options['expression'] . ' SEPARATOR \'' . ($options['delimiter'] ?? ';') . '\')';
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
			if (!empty($options['skip_serials']) && strpos($temp['column']['type_original'] ?? $temp['column']['type'], 'serial') !== false) {
				$default = 0;
			}
			$columns_sql[] = $k . ' ' . $temp['column']['type'] . ($default !== null ? (' DEFAULT ' . $default) : '') . (!($temp['column']['null'] ?? false) ? ' NOT NULL' : '');
		}
		// pk
		if ($pk) {
			$columns_sql[] = "PRIMARY KEY (" . implode(', ', $pk) . ")";
		}
		$columns_sql = implode(', ', $columns_sql);
		$sql = "CREATE TEMPORARY TABLE {$table} ({$columns_sql})";
		return $this->query($sql);
	}
}