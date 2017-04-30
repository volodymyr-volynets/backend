<?php

class numbers_backend_session_db_controller_check extends \Object\Controller {

	public $title = 'Session Check';
	public $acl = [
		'public' => 1,
		'authorized' => 1
	];

	public function actionIndex() {
		$input = \Request::input(null, true, true);
		$result = [
			'success' => false,
			'error' => [],
			'loggedin' => false,
			'expired' => false,
			'expires_in' => 0
		];
		if (!empty($input['token']) && !empty($input[session_name()])) {
			$crypt = new \Crypt();
			$token_data = $crypt->token_validate($input['token'], ['skip_time_validation' => true]);
			if (!($token_data === false || $token_data['id'] !== 'general')) {
				// quering database
				$model = new numbers_backend_session_db_model_sessions();
				$db = $model->db_object();
				$session_id = $db->escape($input[session_name()]);
				$expire = Format::now('timestamp');
				$sql = <<<TTT
					SELECT
						sm_session_expires,
						sm_session_user_id
					FROM {$model->name}
					WHERE 1=1
						AND sm_session_id = '{$session_id}'
						AND sm_session_expires >= '{$expire}'
TTT;
				$temp = $db->query($sql);
				// put values into result
				$result['expired'] = empty($temp['rows']);
				$result['loggedin'] = !empty($temp['rows'][0]['sm_session_user_id']);
				// calculate when session is about to expire
				if (!empty($temp['rows'])) {
					$now = Format::now('unix');
					$expires = strtotime($temp['rows'][0]['sm_session_expires']);
					$result['expires_in'] = $expires - $now;
				}
				$result['success'] = true;
			}
		}
		// rendering
		Layout::render_as($result, 'application/json');
	}

	/**
	 * Renew session
	 */
	public function action_renew() {
		$input = \Request::input(null, true, true);
		$result = [
			'success' => false,
			'error' => []
		];
		if (!empty($input['token'])) {
			$crypt = new \Crypt();
			$token_data = $crypt->token_validate($input['token'], ['skip_time_validation' => true]);
			if (!($token_data === false || $token_data['id'] !== 'general')) {
				$result['success'] = true;
			}
		}
		Layout::render_as($result, 'application/json');
	}
}