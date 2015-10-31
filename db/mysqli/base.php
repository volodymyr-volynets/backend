<?php

class numbers_backend_db_mysqli_base extends numbers_backend_db_class_base implements numbers_backend_db_interface_base {

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
			'sql' => & $sql,
			'error' => [],
			'errno' => 0,
			'num_rows' => 0,
			'affected_rows' => 0,
			'rows' => [],
			'key' => & $key,
			'structure' => [],
			'time' => null
		];

		// start time
		$result['time'] = debug::get_microtime();

		// cache id
		$crypt_object = new crypt();
		$cache_id = !empty($options['cache_id']) ? $options['cache_id'] : 'db_query_' . $crypt_object->hash($sql);

		// if we cache this query
		if (!empty($options['cache'])) {
			$cached_result = cache::get($cache_id, $options['cache_link']);
			if ($cached_result !== false) {
				return $cached_result;
			}
		}

		// quering regular query first
		if (empty($options['multi_query'])) {
			$resource = mysqli_query($this->db_resource, $sql);
			if (!$resource) {
				$result['error'][] = 'Db Link ' . $this->db_link . ': ' . mysqli_error($this->db_resource);
				$result['errno'] = mysqli_errno($this->db_resource);
				// todo: process log policy here
			} else {
				$result['affected_rows']+= mysqli_affected_rows($this->db_resource);
				if ($resource !== true) {
					$result['num_rows']+= mysqli_num_rows($resource);
					$result['structure'] = $this->field_structures($resource);
				}
				if ($result['num_rows'] > 0) {
					while ($rows = mysqli_fetch_assoc($resource)) {
						// casting types
						foreach ($rows as $k => $v) {
							// todo: add all types here!!!
							if (in_array($result['structure'][$k]['type'], ['longlong'])) {
								$rows[$k] = (int) $v;
							} else if ($result['structure'][$k]['type'] == 'numeric') {
								$rows[$k] = (float) $v;
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
				// todo: process log policy here
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
						}
						if ($num_rows > 0) {
							while ($rows = mysqli_fetch_assoc($result_multi)) {
								// casting types
								foreach ($rows as $k => $v) {
									// todo: add all types here!!!
									if (in_array($result['structure'][$k]['type'], ['longlong'])) {
										$rows[$k] = (int) $v;
									} else if ($result['structure'][$k]['type'] == 'numeric') {
										$rows[$k] = (float) $v;
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
						mysqli_free_result($result_multi);
					}
				} while (mysqli_more_results($this->db_resource) && mysqli_next_result($this->db_resource));
				if (empty($result['error'])) {
					$result['success'] = true;
				}
			}
		}

		// caching if no error
		if (!empty($options['cache']) && empty($result['error'])) {
			if (!isset($options['cache_tags'])) {
				$options['cache_tags'] = null;
			}
			cache::set($cache_id, $result, null, ['tags' => $options['cache_tags']], $options['cache_link']);
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
		if (!isset($this->commit_status)) {
			$this->commit_status = 0;
		}
		if ($this->commit_status == 0) {
			$this->commit_status++;
			return $this->query('BEGIN');
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
			return $this->query('COMMIT');
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
		return $this->query('ROLLBACK');
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
		$sql = "INSERT INTO $table (" . $this->prepare_expression($headers) . ") VALUES ";
		$sql_values = [];
		foreach ($rows as $k => $v) {
			$sql_values[] = "(" . $this->prepare_values($v) . ")";
		}
		$sql.= implode(', ', $sql_values);
		// if we need to return updated/inserted rows
		if (!empty($options['returning'])) {
			$sql.= ' RETURNING *';
		}
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
		$where = [];
		foreach ($keys as $key) {
			$where[$key] = array_key_exists($key, $data) ? $data[$key] : null;
			unset($data[$key]);
		}
		// assembling query
		$sql = "UPDATE $table SET " . $this->prepare_condition($data, ', ') . ' WHERE ' . $this->prepare_condition($where, 'AND');
		if (!empty($options['returning'])) {
			$sql.= ' RETURNING *';
		}
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
		$where = [];
		foreach ($keys as $key) {
			$where[$key] = array_key_exists($key, $data) ? $data[$key] : null;
			unset($data[$key]);
		}
		// assembling query
		$sql = "DELETE FROM $table WHERE " . $this->prepare_condition($where, 'AND');
		if (!empty($options['returning'])) {
			$sql.= ' RETURNING *';
		}
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
				// we insert
				$sql = "INSERT INTO $table (" . $this->prepare_expression(array_keys($data)) . ') VALUES (' . $this->prepare_values($data) . ')';
			}
			$result = $this->query($sql, $this->prepare_keys($keys));
			if ($result['success']) {
				$result['inserted'] = $flag_inserted;
			}
			// processing returning clause last
			// todo: process last_insert_id here!!!
			$temp = $this->query("SELECT * FROM $table WHERE " . $this->prepare_condition($where, 'AND'));
			if ($temp['success']) {
				$result['rows'] = $temp['rows'];
				$result['num_rows'] = $temp['num_rows'];
			}
		} while (0);
		return $result;
	}

	/**
	 * Backend specific sequence queries
	 *
	 * @param string $sequence_name
	 * @return string
	 */
	public function sequence($sequence_name, $sequence_table, $type) {
		$sql = <<<TTT
			SET @next_sequence = {$type}('{$sequence_name}');
			SELECT
				*,
				@next_sequence counter
			FROM
				{$sequence_table}
			WHERE 1=1
					AND sm_sequence_name = '{$sequence_name}';
TTT;
		return $this->query($sql, null, ['multi_query' => true]);
	}
}