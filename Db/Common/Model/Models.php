<?php

namespace Numbers\Backend\Db\Common\Model;
class Models extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Models';
	public $schema;
	public $name = 'sm_models';
	public $pk = ['sm_model_id'];
	public $orderby = [
		'sm_model_name' => SORT_DESC
	];
	public $limit;
	public $column_prefix = 'sm_model_';
	public $columns = [
		'sm_model_id' => ['name' => 'Relation #', 'domain' => 'group_id_sequence'],
		'sm_model_code' => ['name' => 'Model', 'domain' => 'code'],
		'sm_model_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_model_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'],
		'sm_model_tenant' => ['name' => 'Tenant', 'type' => 'boolean'],
		// widgets
		'sm_model_widget_attributes' => ['name' => 'Widget Attributes', 'type' => 'boolean'],
		'sm_model_widget_audit' => ['name' => 'Widget Audit', 'type' => 'boolean'],
		'sm_model_widget_addressees' => ['name' => 'Widget Addresses', 'type' => 'boolean'],
		// relation
		'sm_model_relation_enabled' => ['name' => 'Relation Enabled', 'type' => 'boolean'],
		'sm_model_relation_column' => ['name' => 'Column', 'domain' => 'code', 'null' => true],
		'sm_model_relation_domain' => ['name' => 'Domain', 'domain' => 'code', 'null' => true],
		'sm_model_relation_type' => ['name' => 'Type', 'domain' => 'code', 'null' => true],
		// data asset
		'sm_model_da_classification' => ['name' => 'Data Asset Classification', 'type' => 'text'],
		'sm_model_da_protection' => ['name' => 'Data Asset Protection', 'type' => 'smallint', 'default' => 0],
		'sm_model_da_scope' => ['name' => 'Data Asset Scope', 'type' => 'text'],
		'sm_model_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_models_pk' => ['type' => 'pk', 'columns' => ['sm_model_id']],
		'sm_model_code_un' => ['type' => 'unique', 'columns' => ['sm_model_code']],
	];
	public $history = false;
	public $audit = false;
	public $optimistic_lock = false;
	public $options_map = [];
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