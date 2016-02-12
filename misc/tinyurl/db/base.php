<?php

class numbers_backend_misc_tinyurl_db_base implements numbers_backend_misc_tinyurl_interface_base {

	/**
	 * see tinyurl::get();
	 */
	public static function get($hash) {
		$result = [
			'success' => false,
			'error' => [],
			'data' => []
		];
		do {
			$id = (int) base_convert($hash . '', 36, 10);
			if ($id == 0) break;
			$object = new numbers_backend_misc_tinyurl_db_model_tinyurls();
			$get = $object->get(['pk' => null, 'where' => ['sm_tinyurl_id' => $id]]);
			if (count($get) == 0) break;
			if (!empty($get[0]['sm_tinyurl_expires']) && strtotime($get[0]['sm_tinyurl_expires']) < time()) break;
			$result['success'] = true;
			$result['data']['url'] = $get[0]['sm_tinyurl_url'];
		} while(0);
		if (!$result['success']) {
			$result['error'][] = 'Tinyurl not found or expired!';
		}
		return $result;
	}

	/**
	 * see tinyurl::set();
	 */
	public static function set($url, $options = []) {
		// insert new row into the table
		$object = new numbers_backend_misc_tinyurl_db_model_tinyurls();
		$result = $object->insert([
			'sm_tinyurl_inserted' => format::now('datetime'),
			'sm_tinyurl_url' => $url . '',
			'sm_tinyurl_expires' => $options['expires'] ?? null,
		]);
		if ($result['success']) {
			$result['data']['id'] = $result['last_insert_id'];
			$result['data']['hash'] = base_convert($result['last_insert_id'] . '', 10, 36);
		} else {
			$result['data'] = [];
		}
		array_key_unset($result, ['success', 'error', 'data'], ['preserve' => true]);
		return $result;
	}
}