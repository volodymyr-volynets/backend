<?php

class numbers_backend_cron_base_model_slots extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.cron.base.default_db_link';
	public $name = 'sc.slots';
	public $pk = ['sc_slot_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sc_slot_';
	public $columns = [
		'sc_slot_id' => ['name' => 'Slot #', 'type' => 'smallint'],
		'sc_slot_name' => ['name' => 'Name', 'type' => 'varchar', 'length' => 50]
	];
	public $constraints = [
		'sc_slots_pk' => ['type' => 'pk', 'columns' => ['sc_slot_id']],
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