<?php

class numbers_backend_documents_basic_data_menu extends object_import {
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
					'sm_menugrp_code' => 'operations.system.documents',
					'sm_menugrp_name' => 'Documents',
					'sm_menugrp_icon' => 'file-text',
					'sm_menugrp_order' => 32300,
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
					'sm_menuitm_code' => 'operations.system.documents.catalogs',
					'sm_menuitm_name' => 'Catalogs',
					'sm_menuitm_icon' => 'files-o',
					'sm_menuitm_type_id' => 1,
					'sm_menuitm_group1_code' => 'operations',
					'sm_menuitm_group2_code' => 'operations.system',
					'sm_menuitm_group3_code' => 'operations.system.documents',
					'sm_menuitm_order' => 1100,
					'sm_menuitm_acl_controller_id' => '~id~numbers_backend_documents_basic_controller_catalogs',
					'sm_menuitm_acl_action_id' => 1000,
					'sm_menuitm_url' => '/numbers/backend/documents/basic/controller/catalogs',
					'sm_menuitm_inactive' => 0
				]
			]
		],
	];
}