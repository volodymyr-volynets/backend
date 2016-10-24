<?php

class numbers_backend_system_menu_data_import extends object_import {
	public $import_data = [
		'types' => [
			'options' => [
				'pk' => ['sm_menutype_id'],
				'model' => 'numbers_backend_system_menu_model_types',
				'method' => 'save'
			],
			'data' => [
				[
					'sm_menutype_id' => 1,
					'sm_menutype_name' => 'Main Menu',
					'sm_menutype_inactive' => 0
				],
				[
					'sm_menutype_id' => 2,
					'sm_menutype_name' => 'Top Links',
					'sm_menutype_inactive' => 0
				],
				[
					'sm_menutype_id' => 3,
					'sm_menutype_name' => 'Footer Links',
					'sm_menutype_inactive' => 0
				]
			]
		],
		'groups' => [
			'options' => [
				'pk' => ['sm_menugrp_code'],
				'model' => 'numbers_backend_system_menu_model_groups',
				'method' => 'save_insert_new'
			],
			'data' => [
				[
					'sm_menugrp_code' => 'operations',
					'sm_menugrp_name' => 'Operations',
					'sm_menugrp_icon' => 'cogs',
					'sm_menugrp_parent_code' => null,
					'sm_menugrp_order' => 32000,
					'sm_menugrp_url' => null,
					'sm_menugrp_inactive' => 0
				],
				[
					'sm_menugrp_code' => 'account',
					'sm_menugrp_name' => 'Account',
					'sm_menugrp_icon' => 'user',
					'sm_menugrp_parent_code' => null,
					'sm_menugrp_order' => 32001,
					'sm_menugrp_url' => null,
					'sm_menugrp_inactive' => 0
				],
				[
					'sm_menugrp_code' => 'operations.system',
					'sm_menugrp_name' => 'System',
					'sm_menugrp_icon' => 'cogs',
					'sm_menugrp_order' => 32100,
					'sm_menugrp_parent_code' => 'operations',
					'sm_menugrp_inactive' => 0
				],
				[
					'sm_menugrp_code' => 'operations.system.settings',
					'sm_menugrp_name' => 'Settings',
					'sm_menugrp_icon' => 'wrench',
					'sm_menugrp_order' => 50000,
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
			'data' => []
		],
	];
}