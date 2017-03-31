<?php

class numbers_backend_cache_db_model_cache extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $name = 'sm_cache';
	public $pk = ['sm_cache_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_cache_';
	public $columns = [
		'sm_cache_id' => ['name' => 'Cache #', 'domain' => 'code'],
		'sm_cache_time' => ['name' => 'Time', 'type' => 'timestamp'],
		'sm_cache_expire' => ['name' => 'Expire', 'type' => 'timestamp'],
		'sm_cache_data' => ['name' => 'Value', 'type' => 'json', 'null' => true],
		'sm_cache_tags' => ['name' => 'Tags', 'type' => 'json', 'null' => true] // todo
	];
	public $constraints = [
		'sm_cache_pk' => ['type' => 'pk', 'columns' => ['sm_cache_id']],
	];
	public $indexes = [
		'sm_cache_expire_idx' => ['type' => 'btree', 'columns' => ['sm_cache_expire']]
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