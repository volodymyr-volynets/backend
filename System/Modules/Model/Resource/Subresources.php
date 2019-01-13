<?php

namespace Numbers\Backend\System\Modules\Model\Resource;
class Subresources extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Subresources';
	public $name = 'sm_resource_subresources';
	public $pk = ['sm_rsrsubres_id'];
	public $tenant = false;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_rsrsubres_';
	public $columns = [
		'sm_rsrsubres_id' => ['name' => 'Subresource #', 'domain' => 'resource_id_sequence'],
		'sm_rsrsubres_resource_id' => ['name' => 'Resource #', 'domain' => 'resource_id'],
		'sm_rsrsubres_parent_rsrsubres_id' => ['name' => 'Parent Subresource #', 'domain' => 'resource_id', 'null' => true],
		'sm_rsrsubres_code' => ['name' => 'Code', 'domain' => 'code'],
		'sm_rsrsubres_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_rsrsubres_icon' => ['name' => 'Icon', 'domain' => 'icon', 'null' => true],
		'sm_rsrsubres_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'],
		'sm_rsrsubres_disabled' => ['name' => 'Disabled', 'type' => 'boolean'],
		'sm_rsrsubres_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
	];
	public $constraints = [
		'sm_resource_subresources_pk' => ['type' => 'pk', 'columns' => ['sm_rsrsubres_id']],
		'sm_rsrsubres_resource_id_un' => ['type' => 'unique', 'columns' => ['sm_rsrsubres_resource_id', 'sm_rsrsubres_id']],
		'sm_rsrsubres_code_un' => ['type' => 'unique', 'columns' => ['sm_rsrsubres_resource_id', 'sm_rsrsubres_code']],
		'sm_rsrsubres_resource_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_rsrsubres_resource_id'],
			'foreign_model' => '\Numbers\Backend\System\Modules\Model\Resources',
			'foreign_columns' => ['sm_resource_id']
		],
		'sm_rsrsubres_parent_rsrsubres_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_rsrsubres_parent_rsrsubres_id'],
			'foreign_model' => '\Numbers\Backend\System\Modules\Model\Resource\Subresources',
			'foreign_columns' => ['sm_rsrsubres_id']
		]
	];
	public $history = false;
	public $audit = false;
	public $optimistic_lock = false;
	public $options_map = [
		'sm_rsrsubres_name' => 'name',
	];
	public $options_active = [
		'sm_rsrsubres_inactive' => 0
	];
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