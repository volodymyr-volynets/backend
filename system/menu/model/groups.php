<?php

class numbers_backend_system_menu_model_groups extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'sm.menu_groups';
	public $pk = ['sm_menugrp_code'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_menugrp_';
	public $columns = [
		'sm_menugrp_code' => ['name' => 'Group #', 'domain' => 'group_code'],
		'sm_menugrp_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_menugrp_icon' => ['name' => 'Icon', 'domain' => 'icon', 'null' => true],
		'sm_menugrp_parent_code' => ['name' => 'Parent #', 'domain' => 'group_code', 'null' => true],
		'sm_menugrp_order' => ['name' => 'Order', 'domain' => 'order'],
		'sm_menugrp_url' => ['name' => 'URL', 'type' => 'text', 'null' => true],
		'sm_menugrp_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_menu_groups_pk' => ['type' => 'pk', 'columns' => ['sm_menugrp_code']]
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