<?php

/**
 * Memcached Connection
 */
class numbers_backend_cache_memcached_connection {

	/**
	 * Complete result
	 */
	const complete_result = [
		'success' => false,
		'error' => [],
		'errno' => null,
		/* statistics */
		'statistics' => [
			'query_string' => null,
			'query_start' => null,
			'response_string' => '',
			'response_parts' => [],
			'response_duration' => null,
		]
	];

	/**
	 * Socket connection
	 *
	 * @var resource
	 */
	private $socket_connection;

	/**
	 * Flags
	 */
	const flag_tags = 0b0010000000000000; // 8192

	/**
	 * Tags separator
	 */
	const flag_tag_separator = '[[[MEMCACHED-TAGS]]]';

	/**
	 * Destructor
	 */
	public function __destruct() {
		// we need to make sure we are closing the resource
		if (!empty($this->socket_connection)) {
			fclose($this->socket_connection);
		}
	}

	/**
	 * Connect
	 *
	 * @param array $options
	 *		host
	 *		port
	 * @return array
	 */
	public function connect(array $options = []) : array {
		$result = self::complete_result;
		$result['statistics']['query_start'] = microtime(true);
		$result['statistics']['query_string'] = 'connect';
		// open socket connection
		$this->socket_connection = stream_socket_client($options['host'] . ':' . $options['port'], $errno, $error);
		if (!$this->socket_connection) {
			$result['error'][] = "Memcached: {$errno} {$error}";
		} else {
			$result['success'] = true;
		}
		$result['statistics']['response_duration'] = microtime(true) - $result['statistics']['query_start'];
		return $result;
	}

	/**
	 * Close
	 *
	 * @return array
	 */
	public function close() : array {
		$result = self::complete_result;
		$result['statistics']['query_start'] = microtime(true);
		$result['statistics']['query_string'] = 'close';
		if (!empty($this->socket_connection)) {
			if (!fclose($this->socket_connection)) {
				$result['error'][] = 'Memcached: Could not close the connection!';
			}
		}
		if (empty($result['error'])) {
			$result['success'] = true;
		}
		$result['statistics']['response_duration'] = microtime(true) - $result['statistics']['query_start'];
		return $result;
	}

	/**
	 * Query
	 *
	 * @param array $query
	 * @param array $options
	 *		success - array of success codes
	 *		error - array of known error codes
	 * @return array
	 */
	private function query(array $query, array $options = []) : array {
		$query_start = microtime(true);
		// send query to the server
		if (fwrite($this->socket_connection, implode("\r\n", $query) . "\r\n") !== false) {
			$result = $this->fgets($options);
		} else {
			$result = [
				'success' => false,
				'error' => ['Memcached: Failed to send query!'],
				'errno' => 'MEMCACHED_FWRITE_ERROR',
				'statistics' => []
			];
		}
		// populate statistics
		$result['statistics']['query_string'] = $query[0];
		$result['statistics']['query_start'] = $query_start;
		$result['statistics']['response_duration'] = microtime(true) - $query_start;
		return $result;
	}

	/**
	 * Get
	 *
	 * @param string $key
	 * @return array
	 */
	public function get(string $key) : array {
		$result = $this->query(["get $key"], ['success' => ['VALUE'], 'error' => ['END']]);
		// data keys
		$result['data'] = null;
		$result['flags'] = null;
		$result['cas'] = null;
		// if sucecss
		if ($result['success']) {
			// if we have data
			if ($result['statistics']['response_parts'][0] == 'VALUE') {
				$value = fread($this->socket_connection, $result['statistics']['response_parts'][3] + 2);
				if ($value === false) {
					$result['success'] = false;
					$result['error'][] = 'Memcached: Failed to read value!';
					$result['errno'] = 'MEMCACHED_FREAD_ERROR';
				} else { // set data, flags and cas
					$flags = (int) ($result['statistics']['response_parts'][2] ?? 0);
					// tags needs to be stripped
					$start_position = 0;
					if ($flags & self::flag_tags) {
						$length = strlen(self::flag_tag_separator);
						if (($temp = strpos($value, self::flag_tag_separator, $length)) !== false) {
							$start_position = $temp + $length;
						}
					}
					$result['data'] = substr($value, $start_position, strlen($value) - 2);
					$result['flags'] = $result['statistics']['response_parts'][2] ?? null;
					$result['cas'] = $result['statistics']['response_parts'][4] ?? null;
				}
				// read end command
				$this->fgets(['success' => ['END']]);
			} else { // no data - no success
				$result['success'] = false;
			}
		}
		$result['statistics']['response_duration'] = microtime(true) - $result['statistics']['query_start'];
		return $result;
	}

	/**
	 * Set
	 *
	 * @param string $key
	 * @param string $data
	 * @param int $expire
	 * @param int $flags
	 * @return array
	 */
	public function set(string $key, string $data, int $expire = 0, int $flags = 0, array $tags = []) : array {
		// prepend tags to data if present so its in a first chunck
		if ($flags & self::flag_tags) {
			if (!empty($tags)) {
				$data = self::flag_tag_separator . implode(' ', $tags) . self::flag_tag_separator . $data;
			} else {
				$flags = $flags & (~self::flag_tags);
			}
		}
		return $this->query(["set $key $flags $expire " . strlen($data), $data], ['success' => ['STORED'], 'error' => ['NOT_STORED', 'EXISTS', 'NOT_FOUND']]);
	}

	/**
	 * Flush all
	 *
	 * @param int $expire
	 * @return array
	 */
	public function flush_all(int $expire = 0) : array {
		return $this->query(["flush_all $expire"], ['success' => ['OK', 'END'], 'error' => []]);
	}

	/**
	 * Get response string
	 *
	 * @param array $options
	 *		success - array of success codes
	 *		error - array of known error codes
	 * @return array
	 */
	private function fgets(array $options = []) : array {
		$result = self::complete_result;
		$raw = fgets($this->socket_connection);
		if ($raw === false) {
			$result['error'][] = 'Memcached: Failed to read from resource!';
			$result['errno'] = 'MEMCACHED_FGETS_ERROR';
			return $result;
		}
		$result['statistics']['response_string'] = substr($raw, 0, strlen($raw) - 2);
		$result['statistics']['response_parts'] = $parts = explode(' ', $result['statistics']['response_string']);
		// process fatal errors first
		if (in_array($parts[0], ['ERROR', 'CLIENT_ERROR', 'SERVER_ERROR'])) {
			$message = 'Memcached: ' . $parts[0];
			if (count($parts) == 1) {
				$message.= ' Unspecified fatal error!';
			} else {
				unset($parts[0]);
				$message.= implode(' ', $parts);
			}
			$result['errno'] = $parts[0];
			$result['error'][] = $message;
		} else if (in_array($parts[0], $options['success'])) { // success messages
			$result['success'] = true;
		} else if (in_array($parts[0], $options['error'])) { // known errors
			$result['success'] = true;
		} else { // unknown errors
			$message = 'Memcached: ';
			if (!empty($parts[0])) {
				$message.= implode(' ', $parts) . ' ';
				$result['errno'] = $parts[0];
			} else {
				$message.= ' Unspecified error!';
				$result['errno'] = 'MEMCACHED_UNSPECIFIED_ERROR';
			}
			$result['error'][] = $message;
		}
		return $result;
	}
}