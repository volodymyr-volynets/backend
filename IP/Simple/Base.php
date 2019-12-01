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
		if (!empty($ip_long)) {
			$data = \Numbers\Backend\IP\Simple\Model\IPv4::getStatic([
				'where' => [
					'sm_ipver4_start;<=' => $ip_long,
					'sm_ipver4_end;>=' => $ip_long
				],
				'pk' => null,
			]);
			if (!empty($data[0])) {
				foreach (['country_code', 'province', 'city', 'latitude', 'longitude'] as $v) {
					if (!empty($data[0]['sm_ipver4_' . $v])) {
						$result['data'][$v] = $data[0]['sm_ipver4_' . $v];
					}
				}
				$result['success'] = true;
			}
		} else {
			$result['error'][] = 'Could not decode IP address!';
		}
		return $result;
	}
}