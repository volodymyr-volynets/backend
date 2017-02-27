<?php

class numbers_backend_system_modules_model_module_feature_types extends object_data {
	public $module_code = 'SM';
	public $title = 'S/M Module Feature Types';
	public $column_key = 'sm_ftrtype_id';
	public $column_prefix = 'sm_ftrtype_';
	public $orderby;
	public $columns = [
		'sm_ftrtype_id' => ['name' => 'Type #', 'domain' => 'type_id'],
		'sm_ftrtype_name' => ['name' => 'Name', 'type' => 'text']
	];
	public $data = [
		10 => ['sm_ftrtype_name' => 'Default'], // activated by default
		20 => ['sm_ftrtype_name' => 'General'],
		30 => ['sm_ftrtype_name' => 'Data'], // can be reactivated
	];
}