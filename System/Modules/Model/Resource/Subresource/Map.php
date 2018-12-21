<?php

namespace Numbers\Backend\System\Modules\Model\Resource\Subresource;
class Map extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Resource Map';
	public $name = 'sm_resource_subresource_map';
	public $pk = ['sm_rsrsubmap_rsrsubres_id', 'sm_rsrsubmap_action_id'];
	public $tenant = false;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_rsrsubmap_';
	public $columns = [
		'sm_rsrsubmap_rsrsubres_id' => ['name' => 'Subresource #', 'domain' => 'resource_id'],
		'sm_rsrsubmap_action_id' => ['name' => 'Action #', 'domain' => 'action_id'],
		'sm_rsrsubmap_disabled' => ['name' => 'Disabled', 'type' => 'boolean'],
		'sm_rsrsubmap_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
	];
	public $constraints = [
		'sm_resource_subresource_map_pk' => ['type' => 'pk', 'columns' => ['sm_rsrsubmap_rsrsubres_id', 'sm_rsrsubmap_action_id']],
		'sm_rsrsubmap_rsrsubres_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_rsrsubmap_rsrsubres_id'],
			'foreign_model' => '\Numbers\Backend\System\Modules\Model\Resource\Subresources',
			'foreign_columns' => ['sm_rsrsubres_id']
		],
		'sm_rsrsubmap_action_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_rsrsubmap_action_id'],
			'foreign_model' => '\Numbers\Backend\System\Modules\Model\Resource\Actions',
			'foreign_columns' => ['sm_action_id']
		],
	];
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