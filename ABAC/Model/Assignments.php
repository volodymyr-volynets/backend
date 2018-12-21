<?php

namespace Numbers\Backend\ABAC\Model;
class Assignments extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M ABAC Assignments';
	public $name = 'sm_abac_assignments';
	public $pk = ['sm_abacassign_id'];
	public $tenant;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_abacassign_';
	public $columns = [
		'sm_abacassign_id' => ['name' => 'Attribute #', 'domain' => 'attribute_id_sequence'],
		'sm_abacassign_code' => ['name' => 'Code', 'domain' => 'group_code'],
		'sm_abacassign_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_abacassign_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'],
		'sm_abacassign_model_id' => ['name' => 'Model #', 'domain' => 'model_id'],
		'sm_abacassign_model_code' => ['name' => 'Code', 'domain' => 'code'],
		'sm_abacassign_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_abac_assignments_pk' => ['type' => 'pk', 'columns' => ['sm_abacassign_id']],
		'sm_abacassign_code_un' => ['type' => 'unique', 'columns' => ['sm_abacassign_code']],
		'sm_abacassign_model_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_abacassign_model_id'],
			'foreign_model' => '\Numbers\Backend\Db\Common\Model\Models',
			'foreign_columns' => ['sm_model_id']
		]
	];
	public $indexes = [
		'sm_abac_assignments_fulltext_idx' => ['type' => 'fulltext', 'columns' => ['sm_abacassign_code', 'sm_abacassign_name']]
	];
	public $history = false;
	public $audit = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'MySQLi' => 'InnoDB'
	];

	public $cache = true;
	public $cache_tags = [];
	public $cache_memory = true;

	public $data_asset = [
		'classification' => 'public',
		'protection' => 0,
		'scope' => 'global'
	];
}