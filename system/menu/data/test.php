<?php

class numbers_backend_system_menu_data_test extends object_import {
	public $import_data = [
		'groups' => [
			'options' => [
				'pk' => ['sm_menugrp_id'],
				'model' => 'numbers_backend_system_menu_model_groups',
				'method' => 'save_insert_new'
			],
			'data' => [
				[
					'sm_menugrp_id' => 1000,
					'sm_menugrp_name' => 'Admin',
					'sm_menugrp_order' => 1000,
					'sm_menugrp_parent_id' => 0,
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
					'sm_menuitm_code' => 'admin.testing.misc',
					'sm_menuitm_name' => 'Test Misc.',
					'sm_menuitm_type_id' => 1,
					'sm_menuitm_group1_id' => 1000,
					'sm_menuitm_group2_id' => null,
					'sm_menuitm_group3_id' => null,
					'sm_menuitm_order' => 1000,
					'sm_menuitm_acl_controller_id' => null,
					'sm_menuitm_acl_action_id' => null,
					'sm_menuitm_url' => '/testing/misc',
					'sm_menuitm_inactive' => 0
				],
				[
					'sm_menuitm_code' => 'admin.testing.crypt',
					'sm_menuitm_name' => 'Test Crypt',
					'sm_menuitm_type_id' => 1,
					'sm_menuitm_group1_id' => 1000,
					'sm_menuitm_group2_id' => null,
					'sm_menuitm_group3_id' => null,
					'sm_menuitm_order' => 1100,
					'sm_menuitm_acl_controller_id' => null,
					'sm_menuitm_acl_action_id' => null,
					'sm_menuitm_url' => '/testing/crypt',
					'sm_menuitm_inactive' => 0
				]
			]
		],
	];
}