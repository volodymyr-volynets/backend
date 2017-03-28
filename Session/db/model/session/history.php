<?php

namespace Numbers\Backend\Session\Db\Model\Session;
class History extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $schema;
	public $name = 'sm_session_history';
	public $pk = ['sm_sesshist_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_sesshist_';
	public $columns = [
		'sm_sesshist_id' => ['name' => 'Login #', 'type' => 'bigserial'],
		'sm_sesshist_started' => ['name' => 'Datetime Started', 'type' => 'timestamp'],
		'sm_sesshist_last_requested' => ['name' => 'Datetime Last Requested', 'type' => 'timestamp'],
		'sm_sesshist_pages_count' => ['name' => 'Pages Count', 'domain' => 'counter'],
		'sm_sesshist_user_id' => ['name' => 'User #', 'domain' => 'user_id', 'null' => true],
		'sm_sesshist_user_ip' => ['name' => 'User IP', 'domain' => 'ip']
	];
	public $constraints = [
		'sm_session_history_pk' => ['type' => 'pk', 'columns' => ['sm_sesshist_id']],
	];
	public $indexes = [
		'sm_sesshist_user_id_idx' => ['type' => 'btree', 'columns' => ['sm_sesshist_user_id']]
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