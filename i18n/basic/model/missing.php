<?php

class numbers_backend_i18n_basic_model_missing extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'lc.missing';
	public $pk = ['lc_missing_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'lc_missing_';
	public $columns = [
		'lc_missing_id' => ['name' => 'Missing Translation #', 'type' => 'serial'],
		'lc_missing_language_code' => ['name' => 'Language Code', 'domain' => 'language_code'],
		'lc_missing_text_sys' => ['name' => 'System Text', 'type' => 'varchar', 'length' => 2500],
		'lc_missing_counter' => ['name' => 'Counter', 'domain' => 'counter', 'default' => 1]
	];
	public $constraints = [
		'lc_missing_pk' => ['type' => 'pk', 'columns' => ['lc_missing_id']]
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