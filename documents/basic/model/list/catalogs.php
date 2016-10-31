<?php

class numbers_backend_documents_basic_model_list_catalogs extends numbers_frontend_html_list_base {
	public $list_link = 'catalogs';
	public $model = 'numbers_backend_documents_basic_model_catalogs';
	public $columns = [
		'offset_number' => ['name' => '&nbsp;', 'width' => '1%', 'align' => 'right'],
		'action' => ['name' => 'Action', 'width' => '1%'],
		'dc_catalog_code' => ['name' => 'Catalog Code', 'domain' => 'type_code'],
		'dc_catalog_name' => ['name' => 'Name', 'domain' => 'name'],
		'dc_catalog_storage_id' => ['name' => 'Storage', 'domain' => 'type_id', 'options_model' => 'numbers_backend_documents_basic_model_storages'],
		'dc_catalog_inactive' => ['name' => 'Inactive', 'type' => 'boolean', 'options_model' => 'object_data_model_inactive']
	];
	public $filter = [
		'dc_catalog_code' => ['name' => 'Catalog Code', 'domain' => 'type_code'],
		'dc_catalog_name' => ['name' => 'Name', 'domain' => 'name'],
		'dc_catalog_storage_id' => ['name' => 'Storage', 'domain' => 'type_id', 'method' => 'html::multiselect', 'options_model' => 'numbers_backend_documents_basic_model_storages'],
		'dc_catalog_inactive' => ['name' => 'Inactive', 'type' => 'boolean', 'options_model' => 'object_data_model_inactive'],
		'full_text_search' => ['dc_catalog_code', 'dc_catalog_name']
	];
	public $orderby = [
		'dc_catalog_name' => SORT_ASC
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