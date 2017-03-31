<?php

namespace Numbers\Backend\Cache\Common;
abstract class Base {

	/**
	 * Cache link
	 *
	 * @var string
	 */
	public $cache_link;

	/**
	 * Options
	 *
	 * @var array
	 */
	public $options;

	/**
	 * Constructor
	 *
	 * @param string $cache_link
	 * @param array $options
	 *		expire - global expiration
	 *		storage
	 *			json
	 *			serialize
	 *		cache_key - used for multi tenant/db systems
	 *		tags - whether tags are supported
	 */
	public function __construct(string $cache_link, array $options = []) {
		$this->cache_link = $cache_link;
		$this->options = $options;
		$this->options['cache_key'] = $this->options['cache_key'] ?? null;
		$this->options['storage'] = $this->options['storage'] ?? 'json';
		$this->options['expire'] = (int) ($this->options['expire'] ?? 7200);
		$this->options['tags'] = !empty($this->options['tags']);
	}

	/**
	 * Connect
	 *
	 * @param array $options
	 * @return array
	 */
	abstract public function connect(array $options) : array;

	/**
	 * Close
	 *
	 * @return array
	 */
	abstract public function close() : array;

	/**
	 * Get
	 *
	 * @param string $cache_id
	 * @return array
	 */
	abstract public function get(string $cache_id) : array;

	/**
	 * Set
	 *
	 * @param string $cache_id
	 * @param mixed $data
	 * @param int $expire
	 * @param array $tags
	 */
	abstract public function set(string $cache_id, $data, int $expire = null, array $tags = []) : array;

	/**
	 * Garbage collector
	 *
	 * @param int $mode
	 *		1 - old
	 *		2 - all
	 *		3 - tag
	 * @param array $tags
	 *		array of arrays of tags
	 * @return array
	 */
	abstract public function gc(int $mode = 1, array $tags = []) : array;

	/**
	 * Convert data for storage
	 *
	 * @param string $method
	 * @param mixed $data
	 * @return mixed
	 * @throws Exception
	 */
	protected function storageConvert(string $method, $data) {
		switch ($this->options['storage'] . '_' . $method) {
			// json
			case 'json_get': return json_decode($data, true);
			case 'json_set': return json_encode($data);
			// serialize
			case 'serialize_get': return unserialize($data);
			case 'serialize_set': return serialize($data);
			default:
				Throw new Exception("Cache: Unsupported storage {$this->options['storage']}!");
		}
	}

	/**
	 * Calculate expire timestamp
	 *
	 * @param int $time
	 * @param int $expire
	 *		null
	 *		offset
	 *		timestamp
	 * @return int
	 */
	protected function calculateExpireTimestamp($time = null, $expire = null) : int {
		if (empty($time)) $time = time();
		if (empty($expire)) {
			return $time + $this->options['expire'];
		} else if ($expire > $time) {
			return $expire;
		} else {
			return $time + $expire;
		}
	}

	/**
	 * Extract sub-tags from tags
	 *
	 * @param array $tags
	 * @return array
	 */
	protected function extractSubtagsTags($tags) : array {
		$result = ['mandatory' => [], 'optional' => []];
		foreach ($tags as $v) {
			if ($v[0] == '+') {
				$result['mandatory'][] = $v;
			} else {
				$result['optional'][] = $v;
			}
		}
		return $result;
	}
}