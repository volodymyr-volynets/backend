<?php

class numbers_backend_i18n_basic_model_form_languages extends numbers_frontend_html_form_wrapper_base {
	public $form_link = 'languages';
	public $options = [
		'segment' => [
			'type' => 'primary',
			'header' => [
				'icon' => ['type' => 'pencil-square-o'],
				'title' => 'View / Edit:'
			]
		]
	];
	public $containers = [
		'default' => ['default_row_type' => 'grid', 'order' => 1]
	];
	public $rows = [
		'default' => [
			'lc_language_code' => ['order' => 100],
			'lc_language_name' => ['order' => 200],
			'lc_language_locale' => ['order' => 300]
		]
	];
	public $elements = [
		'default' => [
			'lc_language_code' => [
				'lc_language_code' => ['order' => 1, 'label_name' => 'Language Code', 'domain' => 'language_code', 'percent' => 100, 'required' => true]
			],
			'lc_language_name' => [
				'lc_language_name' => ['order' => 1, 'label_name' => 'Name', 'domain' => 'name', 'percent' => 100, 'required' => true]
			],
			'lc_language_locale' => [
				'lc_language_locale' => ['order' => 1, 'label_name' => 'Locale', 'percent' => 50, 'required' => true],
				'lc_language_inactive' => ['order' => 2, 'label_name' => 'Inactive', 'type' => 'boolean', 'percent' => 50, 'required' => false, 'method' => 'select', 'no_choose' => true, 'options_model' => 'object_data_model_inactive']
			],
			self::BUTTONS => [
				self::BUTTON_SUBMIT_SAVE => self::BUTTON_SUBMIT_SAVE_DATA,
				self::BUTTON_SUBMIT_SAVE_AND_NEW => self::BUTTON_SUBMIT_SAVE_AND_NEW_DATA,
				self::BUTTON_SUBMIT_SAVE_AND_CLOSE => self::BUTTON_SUBMIT_SAVE_AND_CLOSE_DATA,
				self::BUTTON_SUBMIT_DELETE => self::BUTTON_SUBMIT_DELETE_DATA
			]
		]
	];
	public $collection = [
		'model' => 'numbers_backend_i18n_basic_model_languages'
	];

	public function overrides() {
		// todo: handle overrides here
	}

	public function validate(& $form) {
		// validation
	}
}