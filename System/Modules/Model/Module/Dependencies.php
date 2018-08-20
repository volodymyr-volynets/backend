<?php

namespace Numbers\Backend\System\Modules\Model\Module;
class Dependencies extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Module Dependencies';
	public $name = 'sm_module_dependencies';
	public $pk = ['sm_mdldep_parent_module_code', 'sm_mdldep_parent_feature_code', 'sm_mdldep_child_module_code', 'sm_mdldep_child_feature_code'];
	public $tenant = false;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_mdldep_';
	public $columns = [
		'sm_mdldep_parent_module_code' => ['name' => 'Parent Module Code', 'domain' => 'module_code'],
		'sm_mdldep_parent_feature_code' => ['name' => 'Parent Feature Code', 'domain' => 'feature_code', 'null' => true],
		'sm_mdldep_child_module_code' => ['name' => 'Child Module Code', 'domain' => 'module_code'],
		'sm_mdldep_child_feature_code' => ['name' => 'Child Feature Code', 'domain' => 'feature_code', 'null' => true],
		'sm_mdldep_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_mdldep_parent_module_code_fk' => [
			'type' => 'fk',
			'columns' => ['sm_mdldep_parent_module_code'],
			'foreign_model' => '\Numbers\Backend\System\Modules\Model\Modules',
			'foreign_columns' => ['sm_module_code']
		],
		'sm_mdldep_parent_feature_code_fk' => [
			'type' => 'fk',
			'columns' => ['sm_mdldep_parent_module_code', 'sm_mdldep_parent_feature_code'],
			'foreign_model' => '\Numbers\Backend\System\Modules\Model\Module\Features',
			'foreign_columns' => ['sm_feature_module_code', 'sm_feature_code']
		],
		/**
		 * Important not to use these fks
		'sm_mdldep_child_module_code_fk' => [
			'type' => 'fk',
			'columns' => ['sm_mdldep_child_module_code'],
			'foreign_model' => '\Numbers\Backend\System\Modules\Model\Modules',
			'foreign_columns' => ['sm_module_code']
		],
		'sm_mdldep_child_feature_code_fk' => [
			'type' => 'fk',
			'columns' => ['sm_mdldep_child_module_code', 'sm_mdldep_child_feature_code'],
			'foreign_model' => '\Numbers\Backend\System\Modules\Model\Module\Features',
			'foreign_columns' => ['sm_feature_module_code', 'sm_feature_code']
		],
		*/
	];
	public $indexes = [];
	public $history = false;
	public $audit = false;
	public $optimistic_lock = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'MySQLi' => 'InnoDB'
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