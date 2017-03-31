<?php

/**
 * Memcached
 */
class numbers_backend_cache_memcached_base extends numbers_backend_cache_class_base {

	/**
	 * Connection Object
	 *
	 * @var object
	 */
	private $connection_object;

	/**
	 * Constructor
	 *
	 * @param string $cache_link
	 * @param array $options
	 */
	public function __construct(string $cache_link, array $options = []) {
		parent::__construct($cache_link, $options);
		// create connection object
		$this->connection_object = new numbers_backend_cache_memcached_connection();
	}

	/**
	 * Connect
	 *
	 * @param array $options
	 *		host
	 *		port
	 * @return array
	 */
	public function connect(array $options) : array {
		return $this->connection_object->connect($options);
	}

	/**
	 * Close
	 *
	 * @return array
	 */
	public function close() : array {
		return $this->connection_object->close();
	}

	/**
	 * Get
	 *
	 * @param string $cache_id
	 * @return array
	 */
	public function get(string $cache_id) : array {
		$result = $this->connection_object->get($cache_id);
		if ($result['success']) {
			$result['data'] = $this->storage_convert('get', $result['data']);
		}
		return $result;
	}

	/**
	 * Set
	 *
	 * @param string $cache_id
	 * @param mixed $data
	 * @param int $expire
	 * @param array $tags
	 * @return array
	 */
	public function set(string $cache_id, $data, int $expire = null, array $tags = []) : array {
		return $this->connection_object->set($cache_id, $this->storage_convert('set', $data), $expire ?? $this->options['expire'], $this->options['tags'] ? $this->connection_object::flag_tags : 0, $tags);
	}

	/**
	 * Garbage collector
	 *
	 * @param int $mode
	 *		1 - old
	 *		2 - all
	 *		3 - tags
	 * @param array $tags
	 * @return array
	 */
	public function gc(int $mode = 1, array $tags = []) : array {
		// remove all caches
		if ($mode == 2) {
			return $this->connection_object->flush_all();
		}
		// tags
		if ($mode == 3 && !empty($tags) && !empty($this->options['tags'])) {
			foreach ($tags as $v) {
				$temp = $this->connection_object->set($this->connection_object::flag_tag_separator, '', $expire ?? $this->options['expire'], $this->connection_object::flag_reset_using_tags, $v);
				print_r2($temp);
			}
		}
		// todo
		return [
			'success' => true,
			'error' => []
		];
	}
}