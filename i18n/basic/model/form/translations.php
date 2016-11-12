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
		],
		'no_ajax_form_reload' => true
	];
	public $containers = [
		'default' => ['default_row_type' => 'grid', 'order' => 1]
	];
	public $rows = [];
	public $elements = [
		'default' => [
			'lc_translation_id' => [
				'lc_translation_id' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Translation #', 'domain' => 'big_id_sequence', 'percent' => 50, 'navigation' => true],
				'lc_translation_language_code' => ['order' => 2, 'label_name' => 'Language Code', 'domain' => 'language_code', 'percent' => 50, 'required' => true, 'method' => 'select', 'options_model' => 'numbers_backend_i18n_languages_model_languages'],
			],
			'lc_translation_text_sys' => [
				'lc_translation_text_sys' => ['order' => 1, 'row_order' => 200, 'label_name' => 'System Text', 'percent' => 100, 'required' => true, 'rows' => 6, 'method' => 'textarea']
			],
			'lc_translation_text_new' => [
				'lc_translation_text_new' => ['order' => 1, 'row_order' => 300, 'label_name' => 'Translated Text', 'percent' => 100, 'required' => true, 'rows' => 6, 'method' => 'textarea']
			],
			'lc_translation_javascript' => [
				'lc_translation_javascript' => ['order' => 1, 'row_order' => 400, 'label_name' => 'JavaScript', 'percent' => 50, 'type' => 'boolean']
			],
			self::buttons => self::buttons_data_group
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