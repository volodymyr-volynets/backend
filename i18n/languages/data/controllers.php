<?php

class numbers_backend_i18n_languages_data_controllers extends object_import {
	public $import_data = [
		'groups' => [
			'options' => [
				'pk' => ['sm_cntrgrp_id'],
				'model' => 'numbers_backend_system_controller_model_groups',
				'method' => 'save'
			],
			'data' => [
				[
					'sm_cntrgrp_id' => 32000,
					'sm_cntrgrp_name' => 'System',
					'sm_cntrgrp_parent_id' => 0,
					'sm_cntrgrp_order' => 32000,
					'sm_cntrgrp_inactive' => 0
				],
				[
					'sm_cntrgrp_id' => 32100,
					'sm_cntrgrp_name' => 'Localization',
					'sm_cntrgrp_parent_id' => 32000,
					'sm_cntrgrp_order' => 32100,
					'sm_cntrgrp_inactive' => 0
				]
			]
		],
		'controllers' => [
			'options' => [
				'pk' => ['sm_controller_code'],
				'model' => 'numbers_backend_system_controller_model_controllers',
				'method' => 'save'
			],
			'data' => [
				[
					'sm_controller_code' => 'numbers_backend_i18n_languages_controller_languages',
					'sm_controller_name' => 'Languages',
					'sm_controller_icon' => 'language',
					'sm_controller_group1_id' => 32000,
					'sm_controller_group2_id' => 32100,
					'sm_controller_group3_id' => null,
					'sm_controller_order' => 100,
					'sm_controller_acl_public' => 0,
					'sm_controller_acl_authorized' => 1,
					'sm_controller_acl_permission' => 1,
					'sm_controller_inactive' => 0,
				]
			]
		],
		'controller_map' => [
			'options' => [
				'pk' => ['sm_cntrmap_controller_id', 'sm_cntrmap_action_code', 'sm_cntrmap_action_id'],
				'model' => 'numbers_backend_system_controller_model_map',
				'method' => 'save',
				'multiple' => ['sm_cntrmap_action_id']
			],
			'data' => [
				[
					'sm_cntrmap_controller_id' => '~id~numbers_backend_i18n_languages_controller_languages',
					'sm_cntrmap_action_code' => 'index',
					'sm_cntrmap_action_id' => [1000, 1010],
					'sm_cntrmap_inactive' => 0
				],
				[
					'sm_cntrmap_controller_id' => '~id~numbers_backend_i18n_languages_controller_languages',
					'sm_cntrmap_action_code' => 'edit',
					'sm_cntrmap_action_id' => [2000, 2010, 2020, 2040],
					'sm_cntrmap_inactive' => 0
				]
			]
		]
	];
}