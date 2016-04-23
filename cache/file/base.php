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
		// expiration
		$this->options['expire'] = $this->options['expire'] ?? 7200;
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
		if (empty($this->options['dir'])) {
			$result['error'][] = 'Cache directory does not exists or not provided!';
		} else {
			// fixing path
			$this->options['dir'] = rtrim($this->options['dir'], '/') . '/';
			// we need to create directory
			if (!empty($this->cache_key)) {
				$this->options['dir'].= $this->cache_key . '/';
			}
			// we need to create cache directory
			if (!is_dir($this->options['dir'])) {
				if (!helper_file::mkdir($this->options['dir'])) {
					$result['error'][] = 'Unable to create caching directory!';
					return $result;
				}
			}
			$result['success'] = true;
		}
		return $result;
	}

	/**
	 * Close
	 */
	public function close() {
		// 5 percent chance to call garbage collector
		if (chance(5)) {
			$this->gc(1);
		}
		return ['success' => true, 'error' => []];
	}

	/**
	 * Get data from cache
	 *
	 * @param string $cache_id
	 * @return mixed
	 */
	public function get($cache_id) {
		// load cookie
		$cookie_name = $this->options['dir'] . 'cache--cookie--' . $cache_id . '.data';
		if (!file_exists($cookie_name)) {
			return false;
		}
		$cookie_data = unserialize(helper_file::read($cookie_name));
		if ($cookie_data['expire'] < time()) {
			helper_file::delete($cookie_name);
			helper_file::delete($cookie_data['file']);
			return false;
		}
		// returning unserialized content
		return unserialize(helper_file::read($cookie_data['file']));
	}

	/**
	 * Put data into cache
	 *
	 * @param string $cache_id
	 * @param mixed $data
	 * @param mixed $tags
	 * @param int $expire
	 * @return boolean
	 */
	public function set($cache_id, $data, $tags = [], $expire = null) {
		// writing data first
		$data_name = $this->options['dir'] . 'cache--' . $cache_id . '.data';
		if (helper_file::write($data_name, serialize($data), 0777, LOCK_EX) ===false) {
			return false;
		}
		// writing cookie
		$time = time();
		$expire = !empty($expire) ? $expire : ($time + $this->options['expire']);
		// generating cookie array
		$cookie_data = array(
			'time' => $time,
			'expire' => $expire,
			'tags' => array_fix($tags),
			'file' => $data_name
		);
		$cookie_name = $this->options['dir'] . 'cache--cookie--' . $cache_id . '.data';
		return helper_file::write($cookie_name, serialize($cookie_data), 0777, LOCK_EX);
	}

	/**
	 * Garbage collector
	 *
	 * @param int $mode - 1 - old, 2 - all
	 * @param array $tags
	 * @return boolean
	 */
	public function gc($mode = 1, $tags = []) {
		if (($cookies = glob($this->options['dir'] . 'cache--cookie--*')) === false) {
			return false;
		}
		$time = time();
		$tags= array_fix($tags);
		foreach ($cookies as $file)  {
			$flag_delete = false;
			do {
				if (!is_file($file)) {
					break;
				}
				$cookie = unserialize(helper_file::read($file));
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