<?php

class numbers_backend_cache_file_base extends numbers_backend_cache_class_base implements numbers_backend_cache_interface_base {

	/**
	 * Constructing cache object
	 *
	 * @param string $cache_link
	 * @param array $options
	 */
	public function __construct($cache_link) {
		$this->cache_link = $cache_link;
		$this->cache_key = application::get(['wildcard', 'keys', 'cache_key']);
	}

	/**
	 * Connect
	 *
	 * @param array $options
	 * @return array
	 */
	public function connect($options) {
		$result = [
			'success' => false,
			'error' => []
		];
		$this->options = $options;
		// for deployed code the directory is different because we relate it based on code
		if (!empty($this->options['dir']) && application::is_deployed()) {
			$temp = $this->options['dir'][0] . $this->options['dir'][1];
			if ($temp == './') {
				$this->options['dir'] = './.' . $this->options['dir'];
			} else {
				$this->options['dir'] = '../';
			}
		}
		// check if we have valid directory
		if (empty($this->options['dir']) || !is_dir($this->options['dir'])) {
			$result['error'][] = 'Cache directory does not exists or not provided!';
		} else {
			// fixing path
			$this->options['dir'] = rtrim($this->options['dir'], '/') . '/';
			// expiration
			$this->options['expire'] = !empty($this->options['expire']) ? $this->options['expire'] : 7200;
			// determining key
			$this->cache_key = application::get(['wildcard', 'keys', 'cache_key']);
			// we need to create directory
			if (!empty($this->cache_key)) {
				$this->options['dir'].= $this->cache_key;
				// create a cache directory for selected key
				if (!file_exists($this->options['dir'])) {
					mkdir($this->options['dir'], 0777, true);
					chmod($this->options['dir'], 0777);
				}
				$this->options['dir'].= '/';
			}
			$result['success'] = true;
		}
		return $result;
	}

	/**
	 * Close
	 */
	public function close() {
		return ['success' => true, 'error' => []];
	}

	/**
	 * Get data from cache
	 *
	 * @param string $cache_id
	 * @return mixed
	 */
	public function get($cache_id) {
		$data_name = $this->options['dir'] . 'cache--' . $cache_id . '.data';
		if (!file_exists($data_name)) {
			return false;
		}
		// check expiration
		if ((filemtime($data_name) + $this->options['expire']) < time()) {
			unlink($this->options['dir'] . 'cache--cookie--' . $cache_id . '.data');
			unlink($data_name);
			return false;
		}
		// returning unserialized content
		return unserialize(file_get_contents($data_name));
	}

	/**
	 * Put data into cache
	 *
	 * @param string $cache_id
	 * @param mixed $data
	 * @param mixed $tags
	 * @return boolean
	 */
	public function set($cache_id, $data, $tags = []) {
		// writing data first
		$data_name = $this->options['dir'] . 'cache--' . $cache_id . '.data';
		file_put_contents($data_name, serialize($data), LOCK_EX);
		// writing cookie
		$time = time();
		$cookie_data = array(
			'time' => $time,
			'expire' => $time + $this->options['expire'],
			'tags' => array_fix($tags),
			'file' => $data_name
		);
		$cookie_name = $this->options['dir'] . 'cache--cookie--' . $cache_id . '.data';
		file_put_contents($cookie_name, serialize($cookie_data), LOCK_EX);
		return true;
	}

	/**
	 * Garbage collector
	 *
	 * @param int $mode - 1 - old, 2 - all
	 * @param array $tags
	 */
	public function gc($mode = 1, $tags = []) {
		if (($cookies = glob($this->options['dir'] . 'cache--cookie--*')) === false) {
			return true;
		}
		$time = time();
		$tags= array_fix($tags);
		foreach ($cookies as $file)  {
			$flag_delete = false;
			do {
				if (!is_file($file)) {
					break;
				}
				$cookie = unserialize(file_get_contents($file));
				// if we delete all caches
				if ($mode == 2) {
					$flag_delete = true;
					break;
				}
				// processing tags
				if ($tags) {
					if (array_intersect($tags, $cookie['tags'])) {
						$flag_delete = true;
						break;
					}
				}
				// if file expired
				if ($time > $cookie['expire']) {
					$flag_delete = true;
					break;
				}
			} while(0);
			// if we need to delete
			if ($flag_delete) {
				unlink($file);
				unlink($cookie['file']);
			}
		}
		return true;
	}
}