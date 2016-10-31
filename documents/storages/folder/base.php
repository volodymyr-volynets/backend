<?php

class numbers_backend_documents_storages_folder_base extends numbers_backend_documents_storages_interface_class implements numbers_backend_documents_storages_interface_base {

	/**
	 * Constructing storage object
	 *
	 * @param array $options
	 */
	public function __construct() {
		$this->cache_key = application::get(['wildcard', 'keys', 'default', 'cache_key']);
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
		if (empty($this->options['dir'])) {
			$result['error'][] = 'Storage directory does not exists or not provided!';
		} else {
			// fixing path
			$this->options['dir'] = rtrim($this->options['dir'], '/') . '/';
			// we need to create directory
			if (!empty($this->cache_key)) {
				$this->options['dir'].= $this->cache_key . '/';
			}
			// we need to create cache directory
			if (!is_dir($this->options['dir'])) {
				if (!helper_file::mkdir($this->options['dir'], 0777)) {
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
		return ['success' => true, 'error' => []];
	}
}