<?php

class numbers_backend_misc_tinyurl_db_model_tinyurls extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.misc.tinyurl.db.default_db_link';
	public $name = 'sm.tinyurls';
	public $pk = ['sm_tinyurl_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_tinyurl_';
	public $columns = [
		'sm_tinyurl_id' => ['name' => 'Tinyurl #', 'type' => 'bigserial'],
		'sm_tinyurl_inserted' => ['name' => 'Inserted', 'type' => 'datetime'],
		'sm_tinyurl_url' => ['name' => 'Url', 'type' => 'text'],
		'sm_tinyurl_expires' => ['name' => 'Expires', 'type' => 'datetime', 'null' => true]
	];
	public $constraints = [
		'sm_tinyurls_pk' => ['type' => 'pk', 'columns' => ['sm_tinyurl_id']],
	];
	public $indexes = [
		'sm_tinyurl_expires_idx' => ['type' => 'btree', 'columns' => ['sm_tinyurl_expires']]
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