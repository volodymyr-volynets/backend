<?php

class numbers_backend_system_menu_model_types extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'sm.menu_types';
	public $pk = ['sm_menutype_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_menutype_';
	public $columns = [
		'sm_menutype_id' => ['name' => 'Type #', 'domain' => 'type_id'],
		'sm_menutype_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_menutype_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_menu_types_pk' => ['type' => 'pk', 'columns' => ['sm_menutype_id']]
	];
	public $history = false;
	public $audit = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'mysqli' => 'InnoDB'
	];

	public $cache = false;
	public $cache_tags = [];
	public $cache_memory = false;
}