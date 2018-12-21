<?php

namespace Numbers\Backend\System\Modules\Model\Resource\Subresource;
class Features extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Subresource Features';
	public $name = 'sm_resource_subresource_features';
	public $pk = ['sm_rsrsubftr_rsrsubres_id', 'sm_rsrsubftr_feature_code'];
	public $tenant = false;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_rsrsubftr_';
	public $columns = [
		'sm_rsrsubftr_rsrsubres_id' => ['name' => 'Subresource #', 'domain' => 'resource_id'],
		'sm_rsrsubftr_feature_code' => ['name' => 'Feature Code', 'domain' => 'feature_code'],
		'sm_rsrsubftr_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
	];
	public $constraints = [
		'sm_resource_subresource_features_pk' => ['type' => 'pk', 'columns' => ['sm_rsrsubftr_rsrsubres_id', 'sm_rsrsubftr_feature_code']],
		'sm_rsrsubftr_rsrsubres_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_rsrsubftr_rsrsubres_id'],
			'foreign_model' => '\Numbers\Backend\System\Modules\Model\Resource\Subresources',
			'foreign_columns' => ['sm_rsrsubres_id']
		]
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