<?php

class numbers_backend_system_modules_model_resource_types extends object_data {
	public $module_code = 'SM';
	public $title = 'S/M Resource Types';
	public $column_key = 'sm_rsrctype_id';
	public $column_prefix = 'sm_rsrctype_';
	public $orderby;
	public $columns = [
		'sm_rsrctype_id' => ['name' => 'Type #', 'domain' => 'type_id'],
		'sm_rsrctype_name' => ['name' => 'Name', 'type' => 'text']
	];
	public $data = [
		100 => ['sm_rsrctype_name' => 'Controllers'],
		// menu
		200 => ['sm_rsrctype_name' => 'Main Menu'],
		210 => ['sm_rsrctype_name' => 'Top Links'],
		220 => ['sm_rsrctype_name' => 'Footer Links'],
		// data activation
		300 => ['sm_rsrctype_name' => 'Data Activation'],
	];
}