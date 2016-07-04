<?php

class numbers_backend_i18n_basic_model_list_translations extends numbers_frontend_html_list_base {
	public $list_link = 'translations';
	public $model = 'numbers_backend_i18n_basic_model_translations';
	public $columns = [
		'offset_number' => ['name' => '&nbsp;', 'width' => '1%', 'align' => 'right'],
		'action' => ['name' => 'Action', 'width' => '1%'],
		'lc_translation_id' => ['name' => 'Translation #', 'type' => 'serial', 'width' => '1%'],
		'lc_translation_language_code' => ['name' => 'Language', 'domain' => 'language_code', 'width' => '1%', 'options_model' => 'numbers_backend_i18n_basic_model_languages'],
		'lc_translation_text_sys' => ['name' => 'System Text', 'type' => 'varchar', 'length' => 2500],
		'lc_translation_text_new' => ['name' => 'Translated Text', 'type' => 'varchar', 'length' => 2500]
	];
	public $filter = [
		'lc_translation_id' => ['name' => 'Translation #', 'type' => 'serial', 'range' => true],
		'lc_translation_language_code' => ['name' => 'Language Code', 'domain' => 'language_code', 'method' => 'html::multiselect', 'options_model' => 'numbers_backend_i18n_basic_model_languages'],
		'lc_translation_text_sys' => ['name' => 'System Text', 'type' => 'varchar', 'length' => 2500, 'operator' => 'like%'],
		'lc_translation_text_new' => ['name' => 'Translated Text', 'type' => 'varchar', 'length' => 2500, 'operator' => 'like%'],
		'full_text_search' => ['lc_translation_language_code', 'lc_translation_text_sys', 'lc_translation_text_new']
	];
	public $orderby = [
		'lc_translation_id' => SORT_ASC
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