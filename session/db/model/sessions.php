<?php

class numbers_backend_session_db_model_sessions extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.session.db.default_db_link';
	public $table_name = 'sm.sessions';
	public $table_pk = ['sm_session_id'];
	public $table_orderby = null;
	public $table_get_limit;
	public $table_columns = [
		'sm_session_id' => ['name' => 'Session #', 'type' => 'varchar', 'length' => 40],
		'sm_session_started' => ['name' => 'Datetime Started', 'type' => 'datetime'],
		'sm_session_expires' => ['name' => 'Datetime Expires', 'type' => 'datetime'],
		'sm_session_last_requested' => ['name' => 'Datetime Last Requested', 'type' => 'datetime'],
		'sm_session_pages_count' => ['name' => 'Pages Count', 'type' => 'integer', 'default' => 0],
		'sm_session_user_ip' => ['name' => 'User IP', 'type' => 'varchar', 'length' => 50],
		'sm_session_user_id' => ['name' => 'User #', 'type' => 'integer', 'default' => 0],
		'sm_session_data' => ['name' => 'Session Data', 'type' => 'text', 'null' => true]
	];
	public $table_constraints = [
		'sm_sessions_pk' => ['type' => 'pk', 'columns' => ['sm_session_id']],
	];
	public $table_indexes = [
		'sm_sessions_expires_idx' => ['type' => 'btree', 'columns' => ['sm_session_expires']],
		'sm_session_user_id_idx' => ['type' => 'btree', 'columns' => ['sm_session_user_id']]
	];
	public $table_history = false;
	public $table_audit = false;
	public $table_row_details = [];
	public $table_options_map = [];
	public $table_options_active = [];
	public $table_engine = [
		'mysqli' => 'InnoDB'
	];

	public $cache = false;
	public $cache_link;
	public $cache_link_flag;
	public $cache_tags = [];
	public $cache_memory = false;
}