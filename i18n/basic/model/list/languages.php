<?php

class numbers_backend_i18n_basic_model_list_languages extends numbers_frontend_html_list_base {
	public $list_link = 'languages';
	public $model = 'numbers_backend_i18n_basic_model_languages';
	public $columns = [
		'offset_number' => ['name' => '&nbsp;', 'width' => '1%', 'align' => 'right'],
		'action' => ['name' => 'Action', 'width' => '1%'],
		'lc_language_code' => ['name' => 'Language Code', 'domain' => 'language_code', 'width' => '1%'],
		'lc_language_name' => ['name' => 'Name', 'domain' => 'name'],
		'lc_language_locale' => ['name' => 'Locale', 'type' => 'text'],
		'lc_language_rtl' => ['name' => 'Right-to-left', 'type' => 'boolean', 'options_model' => 'object_data_model_inactive'],
		'lc_language_inactive' => ['name' => 'Inactive', 'type' => 'boolean', 'options_model' => 'object_data_model_inactive']
	];
	public $filter = [
		'lc_language_code' => ['name' => 'Language Code', 'domain' => 'language_code'],
		'lc_language_name' => ['name' => 'Name', 'domain' => 'name', 'operator' => 'like%'],
		'lc_language_locale' => ['name' => 'Locale', 'type' => 'text', 'operator' => 'like%'],
		'lc_language_rtl' => ['name' => 'Right-to-left', 'type' => 'boolean', 'method' => 'html::multiselect', 'options_model' => 'object_data_model_inactive'],
		'lc_language_inactive' => ['name' => 'Inactive', 'type' => 'boolean', 'method' => 'html::multiselect', 'options_model' => 'object_data_model_inactive'],
		'full_text_search' => ['lc_language_code', 'lc_language_name', 'lc_language_locale']
	];
	public $orderby = [
		'lc_language_name' => SORT_ASC
	];
	public $datasources = [
		'count' => null,
		'data' => null
	];
	public $pagination = [
		'top' => 'numbers_frontend_html_list_pagination_base',
		'bottom' => 'numbers_frontend_html_list_pagination_base'
	];
	/*
	public function render_data() {
		return 'Data rendered';
	}
	*/
	/*
	public function render_data_rows($row, $original_row) {
		return $row;
	}
	*/
}