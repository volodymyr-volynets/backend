<?php

class numbers_backend_i18n_basic_controller_javascript extends object_controller {

	public $title = 'I18n Javascript';
	public $acl = [
		'public' => 1,
		'authorized' => 1
	];

	public function action_js() {
		layout::render_as('numbers.i18n.__custom.data = ' . json_encode(numbers_backend_i18n_basic_base::$data) . ';', 'application/javascript');
	}
}