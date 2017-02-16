<?php

class numbers_backend_ip_ipinfo_base extends numbers_backend_ip_class_base {

	/**
	 * Get
	 *
	 * @param string $ip
	 * @return array
	 */
	public function get(string $ip) : array {
		$result = [
			'success' => false,
			'error' => [],
			'data' => []
		];
		do {
			// try to get IP information from cache
			if (!empty($this->options['cache_link'])) {
				$cache = new cache($this->options['cache_link']);
				$cache_id = 'numbers_backend_ip_ipinfo_' . $ip;
				$data = $cache->get($cache_id, true);
				if ($data !== false) {
					$result['success'] = true;
					$result['data'] = $data;
					break;
				}
			}
			// if we need to query ipinfo.io
			$json = file_get_contents("http://ipinfo.io/{$ip}/json");
			if ($json === false) {
				$result['error'][] = 'Failed to read!';
				break;
			}
			$data = json_decode($json, true);
			if (!isset($data['country'])) {
				$result['error'][] = 'Could not decode IP address!';
				break;
			}
			$temp = explode(',', $data['loc']);
			$result['data'] = [
				'ip' => $data['ip'],
				'timestamp' => format::now('timestamp'),
				'country' => $data['country'],
				'province' => $data['region'],
				'city' => $data['city'],
				'postal' => strtoupper($data['postal']),
				'latitude' => $temp[0],
				'longitude' => $temp[1]
			];
			// cache data
			if (!empty($this->options['cache_link'])) {
				$cache->set($cache_id, $result['data'], $this->options['expire']);
			}
			$result['success'] = true;
		} while(0);
		return $result;
	}
}