<?php

namespace Numbers\Backend\Cache\Memcached\Model;
class Tags extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Memcached Tags';
	public $schema;
	public $name = 'sm_memcached_tags';
	public $pk = ['sm_memcached_cache_link', 'sm_memcached_cache_id', 'sm_memcached_tag'];
	public $orderby = [];
	public $limit;
	public $column_prefix = 'sm_memcached_';
	public $columns = [
		'sm_memcached_cache_link' => ['name' => 'Cache Link', 'domain' => 'code'],
		'sm_memcached_cache_id' => ['name' => 'Cache #', 'domain' => 'code'],
		'sm_memcached_tag' => ['name' => 'Cache #', 'domain' => 'code']
	];
	public $constraints = [
		'sm_memcached_tags_pk' => ['type' => 'pk', 'columns' => ['sm_memcached_cache_link', 'sm_memcached_cache_id', 'sm_memcached_tag']]
	];
	public $history = false;
	public $audit = false;
	public $optimistic_lock = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'MySQLi' => 'InnoDB'
	];

	public $cache = false;
	public $cache_tags = [];
	public $cache_memory = false;

	public $data_asset = [
		'classification' => 'public',
		'protection' => 0,
		'scope' => 'global'
	];
}