<?php

class numbers_backend_cron_base_model_entries extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.cron.base.default_db_link';
	public $name = 'sc.entires';
	public $pk = ['sc_entry_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sc_entry_';
	public $columns = [
		'sc_entry_id' => ['name' => 'Cron #', 'type' => 'serial'],
		'sc_entry_name' => ['name' => 'Name', 'type' => 'varchar', 'length' => 50],
		'sc_entry_cron_string' => ['name' => 'Cron String', 'type' => 'varchar', 'length' => 255, 'null' => true],
		'sc_entry_attribute_count' => ['name' => 'Attribute Count', 'type' => 'integer'],
		'sc_entry_cancelable' => ['name' => 'Cancelable', 'type' => 'boolean'],
		'sc_entry_singleton' => ['name' => 'Singleton', 'type' => 'boolean'],
		'sc_entry_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sc_entires_pk' => ['type' => 'pk', 'columns' => ['sc_entry_id']],
	];
	public $history = false;
	public $audit = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'mysqli' => 'MyISAM'
	];

	public $cache = false;
	public $cache_tags = [];
	public $cache_memory = false;
}