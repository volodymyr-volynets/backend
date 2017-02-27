<?php

class numbers_backend_system_modules_data_import extends object_import {
	public $data = [
		'modules' => [
			'options' => [
				'pk' => ['sm_module_code'],
				'model' => 'numbers_backend_system_modules_model_collection_modules',
				'method' => 'save'
			],
			'data' => [
				[
					'sm_module_code' => 'SM',
					'sm_module_type' => 10,
					'sm_module_name' => 'S/M System',
					'sm_module_parent_module_code' => null,
					'sm_module_transactions' => 0,
					'sm_module_multiple' => 0,
					'sm_module_inactive' => 0
				]
			]
		],
		'resource_actions' => [
			'options' => [
				'pk' => ['sm_action_id'],
				'model' => 'numbers_backend_system_modules_model_resource_actions',
				'method' => 'save'
			],
			'data' => [
				// list related items
				[
					'sm_action_id' => 1000,
					'sm_action_code' => 'list_view',
					'sm_action_name' => 'View List',
					'sm_action_parent_action_id' => null,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 1010,
					'sm_action_code' => 'list_export',
					'sm_action_name' => 'Export/Print List',
					'sm_action_parent_action_id' => 1000,
					'sm_action_inactive' => 0
				],
				// record related items
				[
					'sm_action_id' => 2000,
					'sm_action_code' => 'record_view',
					'sm_action_name' => 'View Record',
					'sm_action_parent_action_id' => null,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 2010,
					'sm_action_code' => 'record_new',
					'sm_action_name' => 'New Record',
					'sm_action_parent_action_id' => 2000,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 2020,
					'sm_action_code' => 'record_edit',
					'sm_action_name' => 'Edit Record',
					'sm_action_parent_action_id' => 2000,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 2030,
					'sm_action_code' => 'record_inactivate',
					'sm_action_name' => 'Inactivate Record',
					'sm_action_parent_action_id' => 2020,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 2040,
					'sm_action_code' => 'record_delete',
					'sm_action_name' => 'Delete Record',
					'sm_action_parent_action_id' => 2020,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 2050,
					'sm_action_code' => 'record_post',
					'sm_action_name' => 'Post Record',
					'sm_action_parent_action_id' => 2020,
					'sm_action_inactive' => 0
				]
			]
		],
	];
}