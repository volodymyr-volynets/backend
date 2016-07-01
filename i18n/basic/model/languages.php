<?php

class numbers_backend_i18n_basic_model_languages extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'lc.languages';
	public $pk = ['lc_language_code'];
	public $orderby;
	public $limit;
	public $column_prefix = 'lc_language_';
	public $columns = [
		'lc_language_code' => ['name' => 'Code', 'domain' => 'language_code'],
		'lc_language_name' => ['name' => 'Name', 'domain' => 'name'],
		'lc_language_locale' => ['name' => 'Locale', 'type' => 'text'],
		'lc_language_rtl' => ['name' => 'RTL', 'type' => 'boolean'],
		'lc_language_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
	];
	public $constraints = [
		'lc_languages_pk' => ['type' => 'pk', 'columns' => ['lc_language_code']]
	];
	public $history = false;
	public $audit = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'mysqli' => 'MyISAM'
	];

	public $cache = true;
	public $cache_tags = [];
	public $cache_memory = false;
}