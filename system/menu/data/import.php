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
				'pk' => ['sm_menugrp_id'],
				'model' => 'numbers_backend_system_menu_model_groups',
				'method' => 'save_insert_new'
			],
			'data' => []
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