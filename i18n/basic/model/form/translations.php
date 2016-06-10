<?php

class numbers_backend_i18n_basic_model_form_translations extends numbers_frontend_html_form_wrapper_base {
	public $form_link = 'translations';
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
			'lc_translation_id' => ['order' => 100],
			'lc_translation_text_sys' => ['order' => 200],
			'lc_translation_text_new' => ['order' => 300]
		]
	];
	public $elements = [
		'default' => [
			'lc_translation_id' => [
				'lc_translation_id' => ['order' => 1, 'label_name' => 'Translation #', 'type' => 'serial', 'percent' => 50, 'required' => false],
				'lc_translation_language_code' => ['order' => 2, 'label_name' => 'Language Code', 'domain' => 'language_code', 'percent' => 50, 'required' => true, 'method' => 'select', 'options_model' => 'numbers_backend_i18n_basic_model_languages'],
			],
			'lc_translation_text_sys' => [
				'lc_translation_text_sys' => ['order' => 1, 'label_name' => 'System Text', 'percent' => 100, 'required' => true, 'rows' => 6, 'method' => 'textarea']
			],
			'lc_translation_text_new' => [
				'lc_translation_text_new' => ['order' => 1, 'label_name' => 'Translated Text', 'percent' => 100, 'required' => true, 'rows' => 6, 'method' => 'textarea']
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
		'model' => 'numbers_backend_i18n_basic_model_translations'
	];

	public function overrides() {
		// todo: handle overrides here
	}

	public function validate(& $form) {
		// validation
	}
}