<?php

class numbers_backend_flow_db_model_flows extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.flow.db.default_db_link';
	public $name = 'sm.flows';
	public $pk = ['sm_flow_id', 'sm_flow_subflow_id', 'sm_flow_transaction_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_flow_';
	public $columns = [
		'sm_flow_id' => ['name' => 'Flow #', 'type' => 'bigint'],
		'sm_flow_inserted' => ['name' => 'Inserted', 'type' => 'timestamp'],
		'sm_flow_subflow_id' => ['name' => 'Subflow #', 'type' => 'bigint'],
		'sm_flow_transaction_id' => ['name' => 'Subflow #', 'type' => 'bigint'],
		'sm_flow_page' => ['name' => 'Page', 'type' => 'varchar', 'length' => 255],
		'sm_flow_action' => ['name' => 'Action', 'type' => 'varchar', 'length' => 255],
		'sm_flow_options' => ['name' => 'Options', 'type' => 'json'],
	];
	public $constraints = [
		'sm_flows_pk' => ['type' => 'pk', 'columns' => ['sm_flow_id', 'sm_flow_subflow_id', 'sm_flow_transaction_id']],
	];
	public $indexes = [];
	public $history = false;
	public $audit = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'mysqli' => 'InnoDB'
	];

	public $cache = false;
	public $cache_link;
	public $cache_link_flag;
	public $cache_tags = [];
	public $cache_memory = false;
}