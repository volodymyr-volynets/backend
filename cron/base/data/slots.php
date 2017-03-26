<?php

class numbers_backend_cron_base_data_slots extends \Object\Import {
	public $import_data = [
		'slots' => [
			'options' => [
				'pk' => ['sc_slot_id'],
				'model' => 'numbers_backend_cron_base_model_slots',
				'method' => 'save'
			],
			'data' => [
				['sc_slot_id' => 1, 'sc_slot_name' => 'Minutes'],
				['sc_slot_id' => 2, 'sc_slot_name' => 'Hours'],
				['sc_slot_id' => 3, 'sc_slot_name' => 'Days'],
				['sc_slot_id' => 4, 'sc_slot_name' => 'Months'],
				['sc_slot_id' => 5, 'sc_slot_name' => 'Weekdays']
			]
		]
	];
}