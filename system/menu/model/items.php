<?php

class numbers_backend_system_menu_model_items extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'sm.menu_items';
	public $pk = ['sm_menuitm_code'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_menuitm_';
	public $columns = [
		'sm_menuitm_code' => ['name' => 'Code', 'domain' => 'code'],
		'sm_menuitm_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_menuitm_type_id' => ['name' => 'Type', 'domain' => 'type_id', 'default' => 1],
		'sm_menuitm_group1_id' => ['name' => 'Group 1', 'domain' => 'group_id', 'null' => true],
		'sm_menuitm_group2_id' => ['name' => 'Group 2', 'domain' => 'group_id', 'null' => true],
		'sm_menuitm_group3_id' => ['name' => 'Group 3', 'domain' => 'group_id', 'null' => true],
		'sm_menuitm_order' => ['name' => 'Order', 'domain' => 'order'],
		'sm_menuitm_acl_controller_id' => ['name' => 'Acl Controller #', 'domain' => 'controller_id', 'null' => true],
		'sm_menuitm_acl_action_id' => ['name' => 'Acl Action #', 'domain' => 'action_id', 'null' => true],
		'sm_menuitm_url' => ['name' => 'URL', 'type' => 'text'],
		'sm_menuitm_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_menu_items_pk' => ['type' => 'pk', 'columns' => ['sm_menuitm_code']],
		'sm_menuitm_type_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_menuitm_type_id'],
			'foreign_model' => 'numbers_backend_system_menu_model_types',
			'foreign_columns' => ['sm_menutype_id'],
			'options' => [
				'match' => 'simple',
				'update' => 'no action',
				'delete' => 'no action'
			]
		],
		'sm_menuitm_group1_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_menuitm_group1_id'],
			'foreign_model' => 'numbers_backend_system_menu_model_groups',
			'foreign_columns' => ['sm_menugrp_id'],
			'options' => [
				'match' => 'simple',
				'update' => 'no action',
				'delete' => 'no action'
			]
		],
		'sm_menuitm_group2_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_menuitm_group2_id'],
			'foreign_model' => 'numbers_backend_system_menu_model_groups',
			'foreign_columns' => ['sm_menugrp_id'],
			'options' => [
				'match' => 'simple',
				'update' => 'no action',
				'delete' => 'no action'
			]
		],
		'sm_menuitm_group3_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_menuitm_group3_id'],
			'foreign_model' => 'numbers_backend_system_menu_model_groups',
			'foreign_columns' => ['sm_menugrp_id'],
			'options' => [
				'match' => 'simple',
				'update' => 'no action',
				'delete' => 'no action'
			]
		],
		'sm_menuitm_acl_controller_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_menuitm_acl_controller_id'],
			'foreign_model' => 'numbers_backend_system_controller_model_controllers',
			'foreign_columns' => ['sm_controller_id'],
			'options' => [
				'match' => 'simple',
				'update' => 'no action',
				'delete' => 'no action'
			]
		],
		'sm_menuitm_acl_action_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_menuitm_acl_action_id'],
			'foreign_model' => 'numbers_backend_system_controller_model_actions',
			'foreign_columns' => ['sm_cntractn_id'],
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
		'mysqli' => 'MyISAM'
	];

	public $cache = false;
	public $cache_link;
	public $cache_link_flag;
	public $cache_tags = [];
	public $cache_memory = false;
}