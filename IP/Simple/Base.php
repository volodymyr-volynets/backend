<?php

namespace Numbers\Backend\IP\Simple;
class Base extends \Numbers\Backend\IP\Common\Base {

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
		// convert IP to long
		$ip_long = ip2long($ip);
		// fetch it from model
		$model = new \Numbers\Backend\IP\Simple\Model\IPv4();
		$data = $model->get([
			'where' => [
				'sm_ipver4_start;>=' => $ip_long,
				'sm_ipver4_end;<=' => $ip_long
			],
			'single_row' => 1
		]);
		if (!empty($data)) {
			foreach (['country_code', 'province', 'city', 'latitude', 'longitude'] as $v) {
				$result['data'][$v] = $data['sm_ipver4_' . $v];
			}
			$result['success'] = true;
		}
		return $result;
	}
}