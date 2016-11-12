<?php

class numbers_backend_i18n_languages_model_languages extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'lc_languages';
	public $pk = ['lc_language_code'];
	public $orderby;
	public $limit;
	public $column_prefix = 'lc_language_';
	public $columns = [
		'lc_language_code' => ['name' => 'Code', 'domain' => 'language_code'],
		'lc_language_name' => ['name' => 'Name', 'domain' => 'name'],
		'lc_language_rtl' => ['name' => 'RTL', 'type' => 'boolean'],
		'lc_language_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
		// the same set of fields as in entities model
		'lc_language_locale' => ['name' => 'Locale', 'type' => 'text'],
		'lc_language_timezone' => ['name' => 'Timezone', 'domain' => 'code', 'null' => true],
		'lc_language_date' => ['name' => 'Date Format', 'domain' => 'code', 'null' => true],
		'lc_language_time' => ['name' => 'Time Format', 'domain' => 'code', 'null' => true],
		'lc_language_datetime' => ['name' => 'Datetime Format', 'domain' => 'code', 'null' => true],
		'lc_language_timestamp' => ['name' => 'Timestamp Format', 'domain' => 'code', 'null' => true],
		'lc_language_amount_frm' => ['name' => 'Amounts In Forms', 'domain' => 'type_id', 'null' => true],
		'lc_language_amount_fs' => ['name' => 'Amounts In Financial Statement', 'domain' => 'type_id', 'null' => true],
	];
	public $constraints = [
		'lc_languages_pk' => ['type' => 'pk', 'columns' => ['lc_language_code']]
	];
	public $indexes = [
		'lc_languages_fulltext_idx' => ['type' => 'fulltext', 'columns' => ['lc_language_code', 'lc_language_name', 'lc_language_locale']]
	];
	public $history = false;
	public $audit = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'mysqli' => 'InnoDB'
	];

	public $cache = true;
	public $cache_tags = [];
	public $cache_memory = false;
}