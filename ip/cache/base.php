<?php

class numbers_backend_ip_cache_base implements numbers_backend_ip_interface_base {

	/**
	 * Object
	 *
	 * @var object
	 */
	public $object;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->object = new numbers_backend_ip_cache_model_ipcache();
	}

	/**
	 * Get information
	 *
	 * @param string $ip
	 * @return array
	 */
	public function get($ip) {
		$ip = $ip . '';
		// gc
		$this->gc();
		// query table for ip address information
		$data = $this->object->get(['sm_ipcache_ip' => $ip], ['limit' => 1, 'orderby' => 'sm_ipcache_date DESC', 'pk' => null]);
		if (isset($data[0])) {
			return [
				'success' => true,
				'error' => [],
				'data' => [
					'ip' => $ip,
					'country' => $data[0]['sm_ipcache_country'],
					'region' => $data[0]['sm_ipcache_region'],
					'city' => $data[0]['sm_ipcache_city'],
					'postal' => $data[0]['sm_ipcache_postal'],
					'lat' => $data[0]['sm_ipcache_lat'],
					'lon' => $data[0]['sm_ipcache_lon']
				]
			];
		} else {
			return [
				'success' => false,
				'error' => ['IP not found'],
				'data' => []
			];
		}
	}

	/**
	 * Put IP address into cache
	 *
	 * @param array $data
	 * @return array
	 */
	public function set($data) {
		return $this->object->save($data);
	}

	/**
	 * Garbage collector
	 */
	public function gc() {
		// we need to cleanup ip addresses older than 2 weeks, 10% chance
		if (rand(0, 99) <= 10 | true) {
			$date = format::now('date', ['add_seconds' => -(14 * 24 * 60 * 60)]);
			$db = new db($this->object->db_link);
			$delete = [
				'sm_ipcache_date,>' => $date
			];
			$db->delete($this->object->table_name, $delete, 'sm_ipcache_date,>');
		}
	}
}