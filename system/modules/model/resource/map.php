<?php

class numbers_backend_system_modules_model_resource_map extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Resource Map';
	public $name = 'sm_resource_map';
	public $pk = ['sm_rsrcmp_resource_id', 'sm_rsrcmp_method_code', 'sm_rsrcmp_action_id'];
	public $tenant = false;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_rsrcmp_';
	public $columns = [
		'sm_rsrcmp_resource_id' => ['name' => 'Resource #', 'domain' => 'resource_id'],
		'sm_rsrcmp_method_code' => ['name' => 'Method Code', 'domain' => 'code'], // controlls access to controller's action in the code
		'sm_rsrcmp_action_id' => ['name' => 'Action #', 'domain' => 'action_id'],
		'sm_rsrcmp_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
	];
	public $constraints = [
		'tm_resource_map_pk' => ['type' => 'pk', 'columns' => ['sm_rsrcmp_resource_id', 'sm_rsrcmp_method_code', 'sm_rsrcmp_action_id']],
		'sm_rsrcmp_resource_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_rsrcmp_resource_id'],
			'foreign_model' => 'numbers_backend_system_modules_model_resources',
			'foreign_columns' => ['sm_resource_id']
		],
		'sm_rsrcmp_action_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_rsrcmp_action_id'],
			'foreign_model' => 'numbers_backend_system_modules_model_resource_actions',
			'foreign_columns' => ['sm_action_id']
		],
		'sm_rsrcmp_method_code_fk' => [
			'type' => 'fk',
			'columns' => ['sm_rsrcmp_method_code'],
			'foreign_model' => 'numbers_backend_system_modules_model_resource_methods',
			'foreign_columns' => ['sm_method_code']
		]
	];
	public $history = false;
	public $audit = false;
	public $optimistic_lock = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'mysqli' => 'InnoDB'
	];

	public $cache = false;
	public $cache_tags = [];
	public $cache_memory = false;

	public $data_asset = [
		'classification' => 'public',
		'protection' => 0,
		'scope' => 'global'
	];
}