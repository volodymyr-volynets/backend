<?php

namespace Numbers\Backend\System\Modules\Model\System;
class Flags extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M System Flags';
	public $name = 'sm_system_flags';
	public $pk = ['sm_sysflag_id'];
	public $tenant = false;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_sysflag_';
	public $columns = [
		'sm_sysflag_id' => ['name' => 'Flag #', 'domain' => 'resource_id_sequence'],
		'sm_sysflag_parent_sysflag_id' => ['name' => 'Parent Flag #', 'domain' => 'resource_id', 'null' => true],
		'sm_sysflag_code' => ['name' => 'Code', 'domain' => 'code'],
		'sm_sysflag_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_sysflag_icon' => ['name' => 'Icon', 'domain' => 'icon', 'null' => true],
		'sm_sysflag_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'],
		'sm_sysflag_disabled' => ['name' => 'Disabled', 'type' => 'boolean'],
		'sm_sysflag_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
	];
	public $constraints = [
		'sm_system_flags_pk' => ['type' => 'pk', 'columns' => ['sm_sysflag_id']],
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
		'protection' => 1,
		'scope' => 'global'
	];
}