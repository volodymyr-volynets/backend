<?php

class numbers_backend_misc_tinyurl_db_model_tinyurls extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.misc.tinyurl.db.default_db_link';
	public $table_name = 'sm.tinyurls';
	public $table_pk = ['sm_tinyurl_id'];
	public $table_orderby = null;
	public $table_get_limit;
	public $table_columns = [
		'sm_tinyurl_id' => ['name' => 'Tinyurl #', 'type' => 'bigserial'],
		'sm_tinyurl_url' => ['name' => 'Url', 'type' => 'text'],
		'sm_tinyurl_expires' => ['name' => 'Expires', 'type' => 'datetime'],
	];
	public $table_constraints = [
		'sm_tinyurls_pk' => ['type' => 'pk', 'columns' => ['sm_tinyurl_id']],
	];
	public $table_indexes = [
		'sm_tinyurl_expires_idx' => ['type' => 'btree', 'columns' => ['sm_tinyurl_expires']]
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