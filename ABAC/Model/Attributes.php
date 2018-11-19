<?php

namespace Numbers\Backend\ABAC\Model;
class Attributes extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M ABAC Attributes';
	public $name = 'sm_abac_attributes';
	public $pk = ['sm_abacattr_id'];
	public $tenant;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_abacattr_';
	public $columns = [
		'sm_abacattr_id' => ['name' => 'Attribute #', 'domain' => 'attribute_id_sequence'],
		'sm_abacattr_code' => ['name' => 'Code', 'domain' => 'field_code'], // name of the field in a table
		'sm_abacattr_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_abacattr_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'], // no fk
		'sm_abacattr_parent_abacattr_id' => ['name' => 'Parent Attribute #', 'domain' => 'attribute_id', 'null' => true],
		// settings from models
		'sm_abacattr_tenant' => ['name' => 'Tenant', 'type' => 'boolean'],
		'sm_abacattr_module' => ['name' => 'Module', 'type' => 'boolean'],
		// flags
		'sm_abacattr_flag_abac' => ['name' => 'Flag ABAC', 'type' => 'boolean'],
		'sm_abacattr_flag_assingment' => ['name' => 'Flag Assignment', 'type' => 'boolean'],
		'sm_abacattr_flag_attribute' => ['name' => 'Flag Assignment', 'type' => 'boolean'],
		'sm_abacattr_flag_link' => ['name' => 'Flag Link', 'type' => 'boolean'],
		'sm_abacattr_flag_other_table' => ['name' => 'Flag Other Table', 'type' => 'boolean'],
		// models
		'sm_abacattr_model_id' => ['name' => 'Model #', 'domain' => 'model_id'],
		'sm_abacattr_link_model_id' => ['name' => 'Link Model #', 'domain' => 'model_id', 'null' => true],
		'sm_abacattr_domain' => ['name' => 'Domain', 'domain' => 'code', 'null' => true],
		'sm_abacattr_type' => ['name' => 'Type', 'domain' => 'code'],
		'sm_abacattr_is_numeric_key' => ['name' => 'Is Numeric Key', 'type' => 'boolean'],
		// methods
		'sm_abacattr_environment_method' => ['name' => 'Environment Method', 'domain' => 'code', 'null' => true],
		// inactive
		'sm_abacattr_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_abac_attributes_pk' => ['type' => 'pk', 'columns' => ['sm_abacattr_id']],
		'sm_abacattr_code_un' => ['type' => 'unique', 'columns' => ['sm_abacattr_code']],
		'sm_abacattr_model_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_abacattr_model_id'],
			'foreign_model' => '\Numbers\Backend\Db\Common\Model\Models',
			'foreign_columns' => ['sm_model_id']
		]
	];
	public $indexes = [
		'sm_abac_attributes_fulltext_idx' => ['type' => 'fulltext', 'columns' => ['sm_abacattr_code', 'sm_abacattr_name']]
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