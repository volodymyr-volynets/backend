<?php

class numbers_backend_misc_ip_ipinfo_base implements numbers_backend_misc_ip_interface_base {

	/**
	 * Get information
	 *
	 * @param string $ip
	 * @return array
	 */
	public function get($ip) {
		$ip = $ip . '';
		// try to get IP information from cache
		$cache = new cache('db');
		$cache_id = 'misc_ip_ipinfo_' . $ip;
		$data = $cache->get($cache_id);
		if ($data !== false) {
			return [
				'success' => true,
				'error' => [],
				'data' => $data
			];
		}
		// if we need to query ipinfo.io
		$json = file_get_contents("http://ipinfo.io/{$ip}/json");
		$data = json_decode($json, true);
		if (isset($data['country'])) {
			$temp = explode(',', $data['loc']);
			$save = [
				'ip' => $ip,
				'date' => format::now('date'),
				'country' => $data['country'],
				'region' => $data['region'],
				'city' => $data['city'],
				'postal' => $data['postal'],
				'lat' => format::read_floatval($temp[0]),
				'lon' => format::read_floatval($temp[1])
			];
			$cache->set($cache_id, $save, ['misc_ip_ipinfo'], 604800);
			return [
				'success' => true,
				'error' => [],
				'data' => $save
			];
		} else {
			return [
				'success' => false,
				'error' => ['Could not decode IP address!'],
				'data' => [
					'ip' => $ip
				]
			];
		}
	}
}