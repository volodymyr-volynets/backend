<?php

namespace Numbers\Backend\ABAC\Model;
class Services extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M ABAC Services';
	public $name = 'sm_abac_services';
	public $pk = ['sm_abacservice_id'];
	public $tenant;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_abacservice_';
	public $columns = [
		'sm_abacservice_id' => ['name' => 'Service #', 'domain' => 'service_id_sequence'],
		'sm_abacservice_code' => ['name' => 'Code', 'domain' => 'feature_code'], // the same as feature code
		'sm_abacservice_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_abacservice_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'], // no fk
		'sm_abacservice_parent_abacservice_id' => ['name' => 'Parent Service #', 'domain' => 'service_id', 'null' => true],
		'sm_abacservice_feature' => ['name' => 'Feature', 'type' => 'boolean'],
		'sm_abacservice_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_abac_services_pk' => ['type' => 'pk', 'columns' => ['sm_abacservice_id']],
		'sm_abacservice_code_un' => ['type' => 'unique', 'columns' => ['sm_abacservice_code']],
	];
	public $indexes = [
		'sm_abac_services_fulltext_idx' => ['type' => 'fulltext', 'columns' => ['sm_abacservice_code', 'sm_abacservice_name']]
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