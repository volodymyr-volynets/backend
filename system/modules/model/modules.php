<?php

class numbers_backend_system_modules_model_modules extends object_table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M System';
	public $name = 'sm_modules';
	public $pk = ['sm_module_code'];
	public $tenant = false;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_module_';
	public $columns = [
		'sm_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'],
		'sm_module_type' => ['name' => 'Type', 'domain' => 'type_id', 'options_model' => 'numbers_backend_system_modules_model_module_types'],
		'sm_module_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_module_short' => ['name' => 'Short Name', 'domain' => 'name'],
		'sm_module_parent_module_code' => ['name' => 'Parent Module Code', 'domain' => 'module_code', 'null' => true],
		'sm_module_transactions' => ['name' => 'Transactions', 'type' => 'boolean'],
		'sm_module_multiple' => ['name' => 'Multiple', 'type' => 'boolean'],
		'sm_module_activation_model' => ['name' => 'Activation Model', 'domain' => 'code', 'null' => true],
		'sm_module_custom_activation' => ['name' => 'Custom Activation', 'type' => 'boolean'],
		'sm_module_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_modules_pk' => ['type' => 'pk', 'columns' => ['sm_module_code']],
		'sm_module_parent_module_code_fk' => [
			'type' => 'fk',
			'columns' => ['sm_module_parent_module_code'],
			'foreign_model' => 'numbers_backend_system_modules_model_modules',
			'foreign_columns' => ['sm_module_code']
		]
	];
	public $indexes = [];
	public $history = false;
	public $audit = false;
	public $optimistic_lock = false;
	public $options_map = [
		'sm_module_name' => 'name'
	];
	public $options_active = [];
	public $engine = [
		'mysqli' => 'InnoDB'
	];

	public $cache = true;
	public $cache_tags = [];
	public $cache_memory = false;

	public $data_asset = [
		'classification' => 'public',
		'protection' => 0,
		'scope' => 'global'
	];

	/**
	 * Options short
	 *
	 * @param array $options
	 * @return array
	 */
	public function options_short($options = []) {
		$options['options_map'] = [
			'sm_module_short' => 'name',
			'sm_module_name' => 'title'
		];
		return parent::options($options);
	}
}