<?php

class numbers_backend_session_db_model_sessions extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.session.db.default_db_link';
	public $name = 'sm.sessions';
	public $pk = ['sm_session_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_session_';
	public $columns = [
		'sm_session_id' => ['name' => 'Session #', 'type' => 'varchar', 'length' => 40],
		'sm_session_started' => ['name' => 'Datetime Started', 'type' => 'timestamp'],
		'sm_session_expires' => ['name' => 'Datetime Expires', 'type' => 'timestamp'],
		'sm_session_last_requested' => ['name' => 'Datetime Last Requested', 'type' => 'timestamp'],
		'sm_session_pages_count' => ['name' => 'Pages Count', 'type' => 'integer', 'default' => 0],
		'sm_session_user_ip' => ['name' => 'User IP', 'type' => 'varchar', 'length' => 50],
		'sm_session_user_id' => ['name' => 'User #', 'type' => 'integer', 'default' => 0],
		'sm_session_data' => ['name' => 'Session Data', 'type' => 'text', 'null' => true]
	];
	public $constraints = [
		'sm_sessions_pk' => ['type' => 'pk', 'columns' => ['sm_session_id']],
	];
	public $indexes = [
		'sm_sessions_expires_idx' => ['type' => 'btree', 'columns' => ['sm_session_expires']],
		'sm_session_user_id_idx' => ['type' => 'btree', 'columns' => ['sm_session_user_id']]
	];
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