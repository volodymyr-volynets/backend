<?php

class numbers_backend_i18n_basic_model_form_missing extends numbers_frontend_html_form_wrapper_base {
	public $form_link = 'missing_translations';
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
		'default' => ['default_row_type' => 'grid', 'order' => 1],
		'preset' => ['default_row_type' => 'grid', 'order' => 2, 'custom_renderer' => 'numbers_backend_i18n_basic_model_form_missing::render_preset_url'],
		'buttons' => ['default_row_type' => 'grid', 'order' => 3],
	];
	public $rows = [
		'default' => [
			'lc_missing_id' => ['order' => 100],
			'lc_missing_language_code' => ['order' => 200],
			'lc_missing_text_sys' => ['order' => 300]
		]
	];
	public $elements = [
		'default' => [
			'lc_missing_id' => [
				'lc_missing_id' => ['order' => 1, 'label_name' => 'Missing Translation #', 'type' => 'serial', 'percent' => 100, 'required' => false],
			],
			'lc_missing_language_code' => [
				'lc_missing_language_code' => ['order' => 1, 'label_name' => 'Language Code', 'domain' => 'language_code', 'percent' => 50, 'required' => true, 'method' => 'select', 'options_model' => 'numbers_backend_i18n_languages_model_languages'],
				'lc_missing_counter' => ['order' => 2, 'label_name' => 'Counter', 'domain' => 'counter', 'readonly' => true, 'percent' => 50]
			],
			'lc_missing_text_sys' => [
				'lc_missing_text_sys' => ['order' => 1, 'label_name' => 'System Text', 'percent' => 100, 'required' => true, 'rows' => 6, 'method' => 'textarea']
			],
		],
		'buttons' => [
			self::buttons => self::buttons_data_group
		]
	];
	public $collection = [
		'model' => 'numbers_backend_i18n_basic_model_missing'
	];

	/**
	 * Render preset url
	 *
	 * @param object $form
	 * @return array
	 */
	public function render_preset_url(& $form) {
		$result = [
			'success' => true,
			'error' => [],
			'data' => [
				'html' => '',
				'js' => '',
				'css' => ''
			]
		];
		$params = [
			'lc_translation_language_code' => $form->values['lc_missing_language_code'],
			'lc_translation_text_sys' => $form->values['lc_missing_text_sys']
		];
		$result['data']['html'] = html::a(['href' => '/numbers/backend/i18n/basic/controller/translations/_edit?' . http_build_query2($params), 'value' => 'Translate']) . '<br/><br/>';
		return $result;
	}
}