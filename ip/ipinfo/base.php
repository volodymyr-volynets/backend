<?php

class numbers_backend_ip_ipinfo_base implements numbers_backend_ip_interface_base {

	/**
	 * Object
	 *
	 * @var object
	 */
	public $object;

	/**
	 * Get information
	 *
	 * @param string $ip
	 * @return array
	 */
	public function get($ip) {
		$ip = $ip . '';
		// try to get IP address from cache
		$ip_cache = new numbers_backend_ip_cache_base();
		$ip_info = $ip_cache->get($ip);
		if ($ip_info['success']) {
			return $ip_info;
		}
		// if we need to query ipinfo.io
		$json = file_get_contents("http://ipinfo.io/{$ip}/json");
		$data = json_decode($json, true);
		if (isset($data['country'])) {
			$temp = explode(',', $data['loc']);
			$save = [
				'sm_ipcache_ip' => $ip,
				'sm_ipcache_date' => format::now('date'),
				'sm_ipcache_country' => $data['country'],
				'sm_ipcache_region' => $data['region'],
				'sm_ipcache_city' => $data['city'],
				'sm_ipcache_postal' => $data['postal'],
				'sm_ipcache_lat' => format::read_floatval($temp[0]),
				'sm_ipcache_lon' => format::read_floatval($temp[1])
			];
			$ip_cache->set($save);
			return [
				'success' => false,
				'error' => [],
				'data' => [
					'ip' => $save['sm_ipcache_ip'],
					'country' => $save['sm_ipcache_country'],
					'region' => $save['sm_ipcache_region'],
					'city' => $save['sm_ipcache_city'],
					'postal' => $save['sm_ipcache_postal'],
					'lat' => $save['sm_ipcache_lat'],
					'lon' => $save['sm_ipcache_lon'],
				]
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