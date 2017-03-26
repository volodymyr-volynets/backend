<?php

class numbers_backend_system_modules_model_module_features extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Module Features';
	public $name = 'sm_module_features';
	public $pk = ['sm_feature_code'];
	public $tenant = false;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_feature_';
	public $columns = [
		'sm_feature_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'],
		'sm_feature_code' => ['name' => 'Feature Code', 'domain' => 'feature_code'],
		'sm_feature_type' => ['name' => 'Type', 'domain' => 'type_id', 'options_model' => 'numbers_backend_system_modules_model_module_feature_types'],
		'sm_feature_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_feature_icon' => ['name' => 'Name', 'domain' => 'icon', 'null' => true],
		'sm_feature_activation_model' => ['name' => 'Activation Model', 'domain' => 'code', 'null' => true],
		'sm_feature_activated_by_default' => ['name' => 'Activated By Default', 'type' => 'boolean'],
		'sm_feature_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_module_features_pk' => ['type' => 'pk', 'columns' => ['sm_feature_code']],
		'sm_feature_module_code_un' => ['type' => 'unique', 'columns' => ['sm_feature_module_code', 'sm_feature_code']],
		'sm_feature_module_code_fk' => [
			'type' => 'fk',
			'columns' => ['sm_feature_module_code'],
			'foreign_model' => 'numbers_backend_system_modules_model_modules',
			'foreign_columns' => ['sm_module_code']
		]
	];
	public $indexes = [];
	public $history = false;
	public $audit = false;
	public $optimistic_lock = false;
	public $options_map = [
		'sm_feature_name' => 'name'
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
}