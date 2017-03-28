<?php

namespace Numbers\Backend\Session\Db\Model;
class Sessions extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $schema;
	public $name = 'sm_sessions';
	public $pk = ['sm_session_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_session_';
	public $columns = [
		'sm_session_id' => ['name' => 'Session #', 'type' => 'varchar', 'length' => 40],
		'sm_session_started' => ['name' => 'Datetime Started', 'type' => 'timestamp'],
		'sm_session_expires' => ['name' => 'Datetime Expires', 'type' => 'timestamp'],
		'sm_session_last_requested' => ['name' => 'Datetime Last Requested', 'type' => 'timestamp'],
		'sm_session_pages_count' => ['name' => 'Pages Count', 'domain' => 'counter'],
		'sm_session_user_id' => ['name' => 'User #', 'domain' => 'user_id', 'null' => true],
		'sm_session_user_ip' => ['name' => 'User IP', 'domain' => 'ip'],
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
	public $cache_tags = [];
	public $cache_memory = false;
}