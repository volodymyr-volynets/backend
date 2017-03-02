<?php

class numbers_backend_system_modules_override_aliases {
	public $data = [
		'resource_id' => [
			'no_data_alias_name' => 'Resource #',
			'no_data_alias_model' => 'numbers_backend_system_modules_model_resources',
			'no_data_alias_column' => 'sm_resource_code'
		],
		'action_id' => [
			'no_data_alias_name' => 'Action #',
			'no_data_alias_model' => 'numbers_backend_system_modules_model_resource_actions',
			'no_data_alias_column' => 'sm_action_code'
		]
	];
}