<?php

class numbers_backend_system_controller_model_controllers extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'sm.controllers';
	public $pk = ['sm_controller_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_controller_';
	public $columns = [
		'sm_controller_id' => ['name' => 'Controller #', 'domain' => 'controller_id_sequence'],
		'sm_controller_code' => ['name' => 'Code', 'domain' => 'code'],
		'sm_controller_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_controller_icon' => ['name' => 'Icon', 'domain' => 'icon', 'null' => true],
		'sm_controller_group1_id' => ['name' => 'Group 1', 'domain' => 'group_id', 'null' => true],
		'sm_controller_group2_id' => ['name' => 'Group 2', 'domain' => 'group_id', 'null' => true],
		'sm_controller_group3_id' => ['name' => 'Group 3', 'domain' => 'group_id', 'null' => true],
		'sm_controller_order' => ['name' => 'Order', 'domain' => 'order'],
		'sm_controller_acl_public' => ['name' => 'Acl Public', 'type' => 'boolean'],
		'sm_controller_acl_authorized' => ['name' => 'Acl Authorized', 'type' => 'boolean'],
		'sm_controller_acl_permission' => ['name' => 'Acl Permission', 'type' => 'boolean'],
		'sm_controller_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_controllers_pk' => ['type' => 'pk', 'columns' => ['sm_controller_id']],
		'sm_controller_code_un' => ['type' => 'unique', 'columns' => ['sm_controller_code']],
		'sm_controller_group1_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_controller_group1_id'],
			'foreign_model' => 'numbers_backend_system_controller_model_groups',
			'foreign_columns' => ['sm_cntrgrp_id'],
			'options' => [
				'match' => 'simple',
				'update' => 'no action',
				'delete' => 'no action'
			]
		],
		'sm_controller_group2_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_controller_group2_id'],
			'foreign_model' => 'numbers_backend_system_controller_model_groups',
			'foreign_columns' => ['sm_cntrgrp_id'],
			'options' => [
				'match' => 'simple',
				'update' => 'no action',
				'delete' => 'no action'
			]
		],
		'sm_controller_group3_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_controller_group3_id'],
			'foreign_model' => 'numbers_backend_system_controller_model_groups',
			'foreign_columns' => ['sm_cntrgrp_id'],
			'options' => [
				'match' => 'simple',
				'update' => 'no action',
				'delete' => 'no action'
			]
		]
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