<?php

class numbers_backend_system_menu_model_groups extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'sm.menu_groups';
	public $pk = ['sm_menugrp_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_menugrp_';
	public $columns = [
		'sm_menugrp_id' => ['name' => 'Group #', 'domain' => 'group_id'],
		'sm_menugrp_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_menugrp_parent_id' => ['name' => 'Parent #', 'domain' => 'group_id'],
		'sm_menugrp_order' => ['name' => 'Order', 'domain' => 'order'],
		'sm_menugrp_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_menu_groups_pk' => ['type' => 'pk', 'columns' => ['sm_menugrp_id']]
	];
	public $history = false;
	public $audit = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'mysqli' => 'MyISAM'
	];

	public $cache = false;
	public $cache_link;
	public $cache_link_flag;
	public $cache_tags = [];
	public $cache_memory = false;
}