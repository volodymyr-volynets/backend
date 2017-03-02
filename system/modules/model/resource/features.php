<?php

class numbers_backend_system_modules_model_resource_features extends object_table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Resource Features';
	public $name = 'sm_resource_features';
	public $pk = ['sm_rsrcftr_resource_id', 'sm_rsrcftr_feature_code'];
	public $tenant = false;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_rsrcftr_';
	public $columns = [
		'sm_rsrcftr_resource_id' => ['name' => 'Resource #', 'domain' => 'resource_id'],
		'sm_rsrcftr_feature_code' => ['name' => 'Feature Code', 'domain' => 'feature_code'],
		//'sm_rsrcftr_mandatory' => ['name' => 'Mandatory', 'type' => 'boolean'],
		'sm_rsrcftr_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
	];
	public $constraints = [
		'sm_resource_features_pk' => ['type' => 'pk', 'columns' => ['sm_rsrcftr_resource_id', 'sm_rsrcftr_feature_code']],
		'sm_rsrcftr_resource_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_rsrcftr_resource_id'],
			'foreign_model' => 'numbers_backend_system_modules_model_resources',
			'foreign_columns' => ['sm_resource_id']
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