<?php

class numbers_backend_cron_base_data_slots extends object_data {
	public $column_key = 'id';
	public $column_prefix = 'sc_slot_';
	public $orderby = ['sc_slot_id' => SORT_ASC];
	public $columns = [
		'id' => ['name' => 'Slot #', 'type' => 'smallint'],
		'name' => ['name' => 'Name', 'type' => 'varchar', 'length' => 50]
	];
	public $data = [
		1 => ['name' => 'Minutes'],
		2 => ['name' => 'Hours'],
		3 => ['name' => 'Days'],
		4 => ['name' => 'Months'],
		5 => ['name' => 'Weekdays']
	];
	public $import_options = [
		'pk' => ['sc_slot_id'],
		'model' => 'numbers_backend_cron_base_model_slots',
		'method' => 'save'
	];
}