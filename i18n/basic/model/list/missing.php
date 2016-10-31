<?php

class numbers_backend_i18n_basic_model_list_missing extends numbers_frontend_html_list_base {
	public $list_link = 'translations';
	public $model = 'numbers_backend_i18n_basic_model_missing';
	public $columns = [
		'offset_number' => ['name' => '&nbsp;', 'width' => '1%', 'align' => 'right'],
		'action' => ['name' => 'Action', 'width' => '1%'],
		'lc_missing_id' => ['name' => 'Missing Translation #', 'type' => 'serial'],
		'lc_missing_language_code' => ['name' => 'Language', 'domain' => 'language_code', 'options_model' => 'numbers_backend_i18n_languages_model_languages'],
		'lc_missing_text_sys' => ['name' => 'System Text', 'type' => 'varchar', 'length' => 2500],
		'lc_missing_counter' => ['name' => 'Counter', 'domain' => 'counter', 'default' => 1]
	];
	public $filter = [
		'lc_missing_id' => ['name' => 'Missing Translation #', 'type' => 'serial', 'range' => true],
		'lc_missing_language_code' => ['name' => 'Language Code', 'domain' => 'language_code', 'method' => 'html::multiselect', 'options_model' => 'numbers_backend_i18n_languages_model_languages'],
		'lc_missing_text_sys' => ['name' => 'System Text', 'type' => 'varchar', 'length' => 2500, 'operator' => 'like%'],
		'lc_missing_counter' => ['name' => 'Counter', 'domain' => 'counter'],
		'full_text_search' => ['lc_missing_language_code', 'lc_missing_text_sys']
	];
	public $orderby = [
		'lc_missing_counter' => SORT_DESC
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