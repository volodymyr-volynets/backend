<?php

namespace Numbers\Backend\System\Modules\Override;
class Aliases {
	public $data = [
		'resource_id' => [
			'no_data_alias_name' => 'Resource #',
			'no_data_alias_model' => '\Numbers\Backend\System\Modules\Model\Resources',
			'no_data_alias_column' => 'sm_resource_code'
		],
		'action_id' => [
			'no_data_alias_name' => 'Action #',
			'no_data_alias_model' => '\Numbers\Backend\System\Modules\Model\Resource\Actions',
			'no_data_alias_column' => 'sm_action_code'
		]
	];
}