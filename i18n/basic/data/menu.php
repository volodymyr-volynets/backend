<?php

class numbers_backend_i18n_basic_data_menu extends object_import {
	public $import_data = [
		'items' => [
			'options' => [
				'pk' => ['sm_menuitm_code'],
				'model' => 'numbers_backend_system_menu_model_items',
				'method' => 'save_insert_new'
			],
			'data' => [
				[
					'sm_menuitm_code' => 'operations.system.localization.translations',
					'sm_menuitm_name' => 'Translations',
					'sm_menuitm_icon' => 'globe',
					'sm_menuitm_type_id' => 1,
					'sm_menuitm_group1_code' => 'operations',
					'sm_menuitm_group2_code' => 'operations.system',
					'sm_menuitm_group3_code' => 'operations.system.localization',
					'sm_menuitm_order' => 1200,
					'sm_menuitm_acl_controller_id' => '~id~numbers_backend_i18n_basic_controller_translations',
					'sm_menuitm_acl_action_id' => 1000,
					'sm_menuitm_url' => '/numbers/backend/i18n/basic/controller/translations',
					'sm_menuitm_inactive' => 0
				],
				[
					'sm_menuitm_code' => 'operations.system.localization.missing',
					'sm_menuitm_name' => 'Missing Translations',
					'sm_menuitm_icon' => 'times',
					'sm_menuitm_type_id' => 1,
					'sm_menuitm_group1_code' => 'operations',
					'sm_menuitm_group2_code' => 'operations.system',
					'sm_menuitm_group3_code' => 'operations.system.localization',
					'sm_menuitm_order' => 1300,
					'sm_menuitm_acl_controller_id' => '~id~numbers_backend_i18n_basic_controller_missing',
					'sm_menuitm_acl_action_id' => 1000,
					'sm_menuitm_url' => '/numbers/backend/i18n/basic/controller/missing',
					'sm_menuitm_inactive' => 0
				]
			]
		],
	];
}