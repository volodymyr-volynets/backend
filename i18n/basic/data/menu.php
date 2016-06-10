<?php

class numbers_backend_i18n_basic_data_menu extends object_import {
	public $import_data = [
		'groups' => [
			'options' => [
				'pk' => ['sm_menugrp_code'],
				'model' => 'numbers_backend_system_menu_model_groups',
				'method' => 'save_insert_new'
			],
			'data' => [
				[
					'sm_menugrp_code' => 'operations.system',
					'sm_menugrp_name' => 'System',
					'sm_menugrp_icon' => 'cogs',
					'sm_menugrp_order' => 32100,
					'sm_menugrp_parent_code' => 'operations',
					'sm_menugrp_inactive' => 0
				],
				[
					'sm_menugrp_code' => 'operations.system.localization',
					'sm_menugrp_name' => 'Localization',
					'sm_menugrp_icon' => 'globe',
					'sm_menugrp_order' => 32200,
					'sm_menugrp_parent_code' => 'operations.system',
					'sm_menugrp_inactive' => 0
				]
			]
		],
		'items' => [
			'options' => [
				'pk' => ['sm_menuitm_code'],
				'model' => 'numbers_backend_system_menu_model_items',
				'method' => 'save_insert_new'
			],
			'data' => [
				[
					'sm_menuitm_code' => 'operations.system.localization.languages',
					'sm_menuitm_name' => 'Languages',
					'sm_menuitm_icon' => 'language',
					'sm_menuitm_type_id' => 1,
					'sm_menuitm_group1_code' => 'operations',
					'sm_menuitm_group2_code' => 'operations.system',
					'sm_menuitm_group3_code' => 'operations.system.localization',
					'sm_menuitm_order' => 1100,
					'sm_menuitm_acl_controller_id' => '~id~numbers_backend_i18n_basic_controller_languages',
					'sm_menuitm_acl_action_id' => 1000,
					'sm_menuitm_url' => '/numbers/backend/i18n/basic/controller/languages',
					'sm_menuitm_inactive' => 0
				],
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