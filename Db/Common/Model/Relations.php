<?php

namespace Numbers\Backend\Db\Common\Model;
class Relations extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Relations';
	public $schema;
	public $name = 'sm_relations';
	public $pk = ['sm_relation_id'];
	public $orderby = [
		'sm_relation_name' => SORT_DESC
	];
	public $limit;
	public $column_prefix = 'sm_relation_';
	public $columns = [
		'sm_relation_id' => ['name' => 'Relation #', 'domain' => 'relation_id_sequence'],
		'sm_relation_model' => ['name' => 'Model', 'domain' => 'code'],
		'sm_relation_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_relation_column' => ['name' => 'Column', 'domain' => 'code'],
		'sm_relation_domain' => ['name' => 'Domain', 'domain' => 'code', 'null' => true],
		'sm_relation_type' => ['name' => 'Type', 'domain' => 'code'],
		'sm_relation_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_relations_pk' => ['type' => 'pk', 'columns' => ['sm_relation_id']],
		'sm_relation_model_un' => ['type' => 'unique', 'columns' => ['sm_relation_model']],
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