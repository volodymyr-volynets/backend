<?php

class numbers_backend_ip_cache_model_ipcache extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.ip.cache.default_db_link';
	public $table_name = 'sm.ip_cache';
	public $table_pk = ['sm_ipcache_ip', 'sm_ipcache_date'];
	public $table_orderby = null;
	public $table_get_limit;
	public $table_columns = [
		'sm_ipcache_ip' => ['name' => 'IP', 'type' => 'varchar', 'length' => 50],
		'sm_ipcache_date' => ['name' => 'Date', 'type' => 'date'],
		'sm_ipcache_country' => ['name' => 'Country', 'type' => 'varchar', 'length' => 50, 'null' => true],
		'sm_ipcache_region' => ['name' => 'Region', 'type' => 'varchar', 'length' => 50, 'null' => true],
		'sm_ipcache_city' => ['name' => 'City', 'type' => 'varchar', 'length' => 50, 'null' => true],
		'sm_ipcache_postal' => ['name' => 'Postal Code', 'type' => 'varchar', 'length' => 50, 'null' => true],
		'sm_ipcache_lat' => ['name' => 'Latitude', 'type' => 'numeric', 'default' => 0],
		'sm_ipcache_lon' => ['name' => 'Longitude', 'type' => 'numeric', 'default' => 0]
	];
	public $table_constraints = [
		'sm_ip_cache_pk' => ['type' => 'pk', 'columns' => ['sm_ipcache_ip', 'sm_ipcache_date']],
	];
	public $table_indexes = [];
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