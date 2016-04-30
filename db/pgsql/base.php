<?php

class numbers_backend_db_pgsql_base extends numbers_backend_db_class_base implements numbers_backend_db_interface_base {

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
		if (is_array($options)) {
			$str = 'host=' . $options['host'] . ' port=' . $options['port'] . ' dbname=' . $options['dbname'] . ' user=' . $options['username'] . ' password=' . $options['password'];
		} else {
			$str = $options;
		}
		$connection = pg_connect($str);
		if ($connection !== false) {
			$this->db_resource = $connection;
			$this->connect_options = $options;
			$this->commit_status = 0;
			pg_set_error_verbosity($connection, PGSQL_ERRORS_VERBOSE);
			pg_set_client_encoding($connection, 'UNICODE');
			$result['version'] = pg_version($connection);
			$result['status'] = pg_connection_status($connection) === PGSQL_CONNECTION_OK ? 1 : 0;
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
			'time' => null,
			'last_insert_id' => 0
		];

		// start time
		$result['time'] = debug::get_microtime();

		// cache id
		$crypt_object = new crypt();
		$cache_id = !empty($options['cache_id']) ? $options['cache_id'] : 'db_query_' . $crypt_object->hash($sql);

		// if we cache this query
		if (!empty($options['cache'])) {
			$cached_result = cache::get($cache_id, @$options['cache_link']);
			if ($cached_result !== false) {
				return $cached_result;
			}
		}

		// quering
		$resource = @pg_query($this->db_resource, $sql);
		$result['status'] = pg_result_status($resource);
		if (!$resource || $result['status'] > 4) {
			$last_error = pg_last_error($this->db_resource);
			if (empty($last_error)) {
				$result['errno'] = 1;
				$result['error'][] = 'DB Link ' . $this->db_link . ': ' . 'Unspecified error!';
			} else {
				preg_match("|ERROR:\s(.*?):|i", $last_error, $matches);
				$result['errno'] = !empty($matches[1]) ? $matches[1] : 1;
				$result['error'][] = 'Db Link ' . $this->db_link . ': ' . $last_error;
			}
			// we log this error message
			// todo: process log policy here
			error_log('Query error: ' . implode(' ', $result['error']) . ' [' . $sql . ']');
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
							$rows[$k] = (int) $v;
						} else if ($result['structure'][$k]['type'] == 'numeric') {
							$rows[$k] = (float) $v;
						} else if ($result['structure'][$k]['type'] == 'bytea') {
							$rows[$k] = pg_unescape_bytea($v);
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

		// last_insert_id is a last element in the last row
		if (!empty($options['flag_came_from_insert']) && !empty($options['returning'])) {
			$temp = end($result['rows']);
			$result['last_insert_id'] = end($temp);
		}

		// caching if no error
		if (!empty($options['cache']) && empty($result['error'])) {
			cache::set($cache_id, $result, null, ['tags' => @$options['cache_tags']], @$options['cache_link']);
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
		return pg_escape_string($this->db_resource, $value);
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
			if (is_array($options['returning'])) {
				$sql.= ' RETURNING ' . implode(', ', $options['returning']);
			} else {
				$sql.= ' RETURNING *';
			}
		}
		$options['flag_came_from_insert'] = true;
		return $this->query($sql, $this->prepare_keys($keys), $options);
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

			// if we need to return updated/inserted rows
			$sql_addon = '';
			if (!empty($options['returning'])) {
				$sql_addon = ' RETURNING *';
			}

			// if row found we update
			if ($row_found) {
				$flag_inserted = false;
				$sql = "UPDATE $table SET " . $this->prepare_condition($data, ', ') . ' WHERE ' . $this->prepare_condition($where, 'AND') . $sql_addon;
			} else {
				$flag_inserted = true;
				// we need to unset key fields
				if ($empty) {
					foreach ($keys as $key) {
						unset($data[$key]);
					}
				}
				// we insert
				$sql = "INSERT INTO $table (" . $this->prepare_expression(array_keys($data)) . ') VALUES (' . $this->prepare_values($data) . ')' . $sql_addon;
			}
			$result = $this->query($sql, $this->prepare_keys($keys));
			if ($result['success']) {
				$result['inserted'] = $flag_inserted;
			}
		} while (0);
		return $result;
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
	public function sequence($sequence_name, $type) {
		$sequence_model = new numbers_backend_db_class_model_sequences();
		$sql = <<<TTT
			SELECT
				*,
				{$type}('{$sequence_name}') counter
			FROM
				{$sequence_model->name}
			WHERE 1=1
					AND sm_sequence_name = '{$sequence_name}';
TTT;
		return $this->query($sql);
	}
}