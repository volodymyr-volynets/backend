<?php

/**
 * File based cache
 */
namespace Numbers\Backend\Cache\File;
class Base extends \Numbers\Backend\Cache\Common\Base {

	/**
	 * Constructor
	 *
	 * @param string $cache_link
	 * @param array $options
	 */
	public function __construct(string $cache_link, array $options = []) {
		parent::__construct($cache_link, $options);
	}

	/**
	 * Connect
	 *
	 * @param array $options
	 *		dir
	 * @return array
	 */
	public function connect(array $options) : array {
		$result = [
			'success' => false,
			'error' => [],
		];
		// check if we have valid directory
		if (empty($options['dir'])) {
			$result['error'][] = 'Cache directory does not exists or not provided!';
		} else {
			// fixing path
			$options['dir'] = rtrim($options['dir'], '/') . DIRECTORY_SEPARATOR . $this->cache_link . DIRECTORY_SEPARATOR;
			// handle cache key
			if (!empty($this->options['cache_key'])) {
				$options['dir'].= $this->options['cache_key'] . DIRECTORY_SEPARATOR;
			}
			// we need to create cache directory
			if (!is_dir($options['dir'])) {
				if (!\Helper\File::mkdir($options['dir'], 0777)) {
					$result['error'][] = 'Unable to create caching directory!';
				}
				\Helper\File::chmod($options['dir'], 0777);
			}
			// add directory to the options
			$this->options['dir'] = $options['dir'];
		}
		if (empty($result['error'])) {
			$result['success'] = true;
		}
		return $result;
	}

	/**
	 * Close
	 *
	 * @return array
	 */
	public function close() : array {
		return [
			'success' => true,
			'error' => [],
		];
	}

	/**
	 * Get
	 *
	 * @param string $cache_id
	 * @return array
	 */
	public function get(string $cache_id) : array {
		$result = [
			'success' => false,
			'error' => [],
			'data' => null
		];
		do {
			// load cookie
			$cookie_name = $this->options['dir'] . 'cache--cookie--' . $cache_id . '.data';
			if (!file_exists($cookie_name)) break;
			$cookie_data = file_get_contents($cookie_name);
			if ($cookie_data === false) {
				$result['error'][] = 'File cache: Failed to read cookie file!';
				break;
			}
			// convert data to array
			$cookie_data = $this->storageConvert('get', $cookie_data);
			// remove cookie files if expired
			if ($cookie_data['expire'] < time()) {
				@unlink($cookie_name);
				@unlink($cookie_data['file']);
				break;
			}
			// load cache file
			$cache_data = file_get_contents($cookie_data['file']);
			if ($cache_data === false) {
				$result['error'][] = 'File cache: Failed to read cache file!';
				break;
			}
			// success if we got here
			$result['data'] = $this->storageConvert('get', $cache_data);
			$result['success'] = true;
		} while(0);
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
		$result = [
			'success' => false,
			'error' => []
		];
		do {
			// writing data first
			$data_name = $this->options['dir'] . 'cache--' . $cache_id . '.data';
			if (file_put_contents($data_name, $this->storageConvert('set', $data), LOCK_EX) === false) {
				$result['error'][] = 'Failed to write cache file!';
				break;
			}
			// prepare cookie data
			$time = time();
			$cookie_data = [
				'time' => $time,
				'expire' => $this->calculateExpireTimestamp($time, $expire),
				'tags' => $tags,
				'file' => $data_name
			];
			// writing cookie
			$cookie_name = $this->options['dir'] . 'cache--cookie--' . $cache_id . '.data';
			if (file_put_contents($cookie_name, $this->storageConvert('set', $cookie_data), LOCK_EX) === false) {
				$result['error'][] = 'Failed to write cookie file!';
				break;
			}
			// set file permission
			@chmod($data_name, 0777);
			@chmod($cookie_name, 0777);
			// success if we got here
			$result['success'] = true;
		} while(0);
		return $result;
	}

	/**
	 * Garbage collector
	 *
	 * @param int $mode
	 *		1 - old
	 *		2 - all
	 *		3 - tag
	 * @param array $tags
	 * @return array
	 */
	public function gc(int $mode = 1, array $tags = []) : array {
		$result = [
			'success' => false,
			'error' => []
		];
		// get a list of cache cookies
		if (($cookies = glob($this->options['dir'] . 'cache--cookie--*')) === false) {
			$result['success'] = true;
		} else {
			$time = time();
			foreach ($cookies as $cookie) {
				// read cookie
				$cookie_data = file_get_contents($cookie);
				if ($cookie_data === false) {
					continue;
				}
				$cookie_data = $this->storageConvert('get', $cookie_data);
				$flag_delete = false;
				// all
				if ($mode == 2) goto delete;
				// tags
				if ($mode == 3 && !empty($tags) && !empty($cookie_data['tags'])) {
					$cookie_tags_processed = $this->extractSubtagsTags($cookie_data['tags']);
					foreach ($tags as $v) {
						$temp_tags_processed = $this->extractSubtagsTags($v);
						// mandatory tags first
						$flag_mandatory_check_through = false;
						if (!empty($cookie_tags_processed['mandatory'])) {
							if (empty($temp_tags_processed['mandatory'])) continue;
							// every tag must be present
							$temp = array_intersect($cookie_tags_processed['mandatory'], $temp_tags_processed['mandatory']);
							if (!empty($temp) && count($temp) == count($cookie_tags_processed['mandatory'])) {
								$flag_mandatory_check_through = true;
							}
						} else {
							if (!empty($temp_tags_processed['mandatory'])) continue;
							$flag_mandatory_check_through = true;
						}
						// optional tags
						if ($flag_mandatory_check_through) {
							if (array_intersect($cookie_tags_processed['optional'], $temp_tags_processed['optional'])) {
								goto delete;
							}
						}
					}
				}
				// old
				if ($mode == 1 && $time > $cookie_data['expire']) {
					goto delete;
				}
				// if we need to delete
				if ($flag_delete) {
delete:
					unlink($cookie);
					unlink($cookie_data['file']);
				}
			}
			// success if we got here
			$result['success'] = true;
		}
		return $result;
	}
}