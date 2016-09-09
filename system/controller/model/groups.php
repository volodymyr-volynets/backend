<?php

class numbers_backend_system_controller_model_groups extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'sm_controller_groups';
	public $pk = ['sm_cntrgrp_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_cntrgrp_';
	public $columns = [
		'sm_cntrgrp_id' => ['name' => 'Group #', 'domain' => 'group_id'],
		'sm_cntrgrp_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_cntrgrp_parent_id' => ['name' => 'Parent #', 'domain' => 'group_id', 'default' => 0],
		'sm_cntrgrp_order' => ['name' => 'Order', 'domain' => 'order'],
		'sm_cntrgrp_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_controller_groups_pk' => ['type' => 'pk', 'columns' => ['sm_cntrgrp_id']]
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