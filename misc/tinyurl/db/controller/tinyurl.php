<?php

class numbers_backend_misc_tinyurl_db_controller_tinyurl {
	public $title = '';

	public function action_index() {
		$id = application::get(['mvc', 'controller_id']);
		if ($id) {
			$result = url_tinyurl::get($id);
			if ($result['success']) {
				request::redirect($result['data']['url']);
			}
		}
	}

	public function action_i() {
		$this->action_index();
	}
}