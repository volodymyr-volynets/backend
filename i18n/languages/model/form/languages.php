<?php

class numbers_backend_i18n_languages_model_form_languages extends numbers_frontend_html_form_wrapper_base {
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
	public $rows = [];
	public $elements = [
		'default' => [
			'lc_language_code' => [
				'lc_language_code' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Language Code', 'domain' => 'language_code', 'percent' => 50, 'required' => true, 'navigation' => true],
				'lc_language_inactive' => ['order' => 2, 'label_name' => 'Inactive', 'type' => 'boolean', 'percent' => 50, 'required' => false, 'method' => 'select', 'no_choose' => true, 'options_model' => 'object_data_model_inactive']
			],
			'lc_language_name' => [
				'lc_language_name' => ['order' => 1, 'row_order' => 200, 'label_name' => 'Name', 'domain' => 'name', 'percent' => 50, 'required' => true],
				'lc_language_rtl' => ['order' => 2, 'label_name' => 'Right-to-left', 'type' => 'boolean', 'percent' => 50, 'required' => false, 'method' => 'select', 'no_choose' => true, 'options_model' => 'object_data_model_inactive']
			],
			'separator_1' => [
				self::separator_horisontal => ['order' => 1, 'row_order' => 300, 'label_name' => 'Locale & Settings', 'icon' => 'wrench', 'percent' => 100],
			],
			'lc_language_locale' => [
				'lc_language_locale' => ['order' => 1, 'row_order' => 400, 'label_name' => 'Locale', 'domain' => 'code', 'percent' => 50, 'null' => true, 'method' => 'select', 'preset' => true, 'options_model' => 'numbers_backend_i18n_languages_model_languages::presets', 'options_options' => ['columns' => 'lc_language_locale']],
				'lc_language_timezone' => ['order' => 2, 'label_name' => 'Timezone', 'domain' => 'code', 'percent' => 50, 'null' => true, 'method' => 'select', 'searchable' => true, 'options_model' => 'numbers_backend_i18n_languages_model_timezones::options'],
			],
			'lc_language_date' => [
				'lc_language_date' => ['order' => 1, 'row_order' => 500, 'label_name' => 'Date Format', 'domain' => 'code', 'percent' => 25, 'null' => true, 'method' => 'select', 'preset' => true, 'options_model' => 'numbers_backend_i18n_languages_model_languages::presets', 'options_options' => ['columns' => 'lc_language_date'], 'description' => 'Y - year, m - month, d - day, H - hour, i - minute, s = second, g - short hour, a - am/pm, u - miliseconds'],
				'lc_language_time' => ['order' => 2, 'label_name' => 'Time Format', 'domain' => 'code', 'percent' => 25, 'null' => true, 'method' => 'select', 'preset' => true, 'options_model' => 'numbers_backend_i18n_languages_model_languages::presets', 'options_options' => ['columns' => 'lc_language_time']],
				'lc_language_datetime' => ['order' => 3, 'label_name' => 'Datetime Format', 'domain' => 'code', 'percent' => 25, 'null' => true, 'method' => 'select', 'preset' => true, 'options_model' => 'numbers_backend_i18n_languages_model_languages::presets', 'options_options' => ['columns' => 'lc_language_datetime']],
				'lc_language_timestamp' => ['order' => 4, 'label_name' => 'Timestamp Format', 'domain' => 'code', 'percent' => 25, 'null' => true, 'method' => 'select', 'preset' => true, 'options_model' => 'numbers_backend_i18n_languages_model_languages::presets', 'options_options' => ['columns' => 'lc_language_timestamp']]
			],
			'lc_language_amount_frm' => [
				'lc_language_amount_frm' => ['order' => 1, 'row_order' => 600, 'label_name' => 'Amounts In Forms', 'domain' => 'type_id', 'percent' => 50, 'null' => true, 'method' => 'select', 'options_model' => 'format::amount_format_options'],
				'lc_language_amount_fs' => ['order' => 2, 'label_name' => 'Amounts In Financial Statement', 'domain' => 'type_id', 'percent' => 50, 'null' => true, 'method' => 'select', 'options_model' => 'format::amount_format_options'],
			],
			self::buttons => self::buttons_data_group
		]
	];
	public $collection = [
		'model' => 'numbers_backend_i18n_languages_model_languages'
	];

	public function overrides() {
		// todo: handle overrides here
	}

	public function validate(& $form) {
		// validation
	}
}