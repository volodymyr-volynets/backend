<?php

class numbers_backend_cron_base_model_details extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.cron.base.default_db_link';
	public $name = 'sc_details';
	public $pk = ['sc_detail_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sc_detail_';
	public $columns = [
		'sc_detail_id' => ['name' => 'Detail #', 'type' => 'bigserial'],
		'sc_detail_entry_id' => ['name' => 'Entry #', 'type' => 'integer'],
		'sc_detail_slot_id' => ['name' => 'Slot', 'type' => 'smallint'],
		'sc_detail_index' => ['name' => 'Index', 'type' => 'smallint'],
		'sc_detail_value1' => ['name' => 'Value 1', 'type' => 'varchar', 'length' => 30],
		'sc_detail_operator' => ['name' => 'Operator', 'type' => 'varchar', 'length' => 30, 'null' => true],
		'sc_detail_value2' => ['name' => 'Value 2', 'type' => 'varchar', 'length' => 30, 'null' => true],
	];
	public $constraints = [
		'sc_details_pk' => ['type' => 'pk', 'columns' => ['sc_detail_id']],
		'sc_details_un' => ['type' => 'unique', 'columns' => ['sc_detail_entry_id', 'sc_detail_slot_id', 'sc_detail_index']],
		'sc_detail_entry_id_fk' => [
			'type' => 'fk',
			'columns' => ['sc_detail_entry_id'],
			'foreign_model' => 'numbers_backend_cron_base_model_entries',
			'foreign_columns' => ['sc_entry_id'],
			'options' => [
				'match' => 'simple',
				'update' => 'no action',
				'delete' => 'no action'
			]
		],
		'sc_detail_slot_fk' => [
			'type' => 'fk',
			'columns' => ['sc_detail_slot_id'],
			'foreign_model' => 'numbers_backend_cron_base_model_slots',
			'foreign_columns' => ['sc_slot_id'],
			'options' => [
				'match' => 'simple',
				'update' => 'no action',
				'delete' => 'no action'
			]
		]
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