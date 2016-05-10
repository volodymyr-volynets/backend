<?php

class numbers_backend_system_controller_model_map extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'sm.controller_map';
	public $pk = ['sm_cntrmap_controller_id', 'sm_cntrmap_action_code', 'sm_cntrmap_action_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_cntrmap_';
	public $columns = [
		'sm_cntrmap_controller_id' => ['name' => 'Controller #', 'domain' => 'controller_id'],
		'sm_cntrmap_action_code' => ['name' => 'Action Code', 'domain' => 'code'],
		'sm_cntrmap_action_id' => ['name' => 'Action #', 'domain' => 'action_id'],
		'sm_cntrmap_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
	];
	public $constraints = [
		'sm_controller_map_pk' => ['type' => 'pk', 'columns' => ['sm_cntrmap_controller_id', 'sm_cntrmap_action_code', 'sm_cntrmap_action_id']],
		'sm_cntrmap_controller_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_cntrmap_controller_id'],
			'foreign_model' => 'numbers_backend_system_controller_model_controllers',
			'foreign_columns' => ['sm_controller_id'],
			'options' => [
				'match' => 'simple',
				'update' => 'no action',
				'delete' => 'no action'
			]
		],
		'sm_cntrmap_action_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_cntrmap_action_id'],
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