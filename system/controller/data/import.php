<?php

class numbers_backend_system_controller_data_import extends object_import {
	public $import_data = [
		// common group items
		'groups' => [
			'options' => [
				'pk' => ['sm_cntrgrp_id'],
				'model' => 'numbers_backend_system_controller_model_groups',
				'method' => 'save'
			],
			'data' => [
				[
					'sm_cntrgrp_id' => 1100,
					'sm_cntrgrp_name' => 'Settings',
					'sm_cntrgrp_parent_id' => 1000,
					'sm_cntrgrp_order' => 1100,
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
			'data' => []
		],
		'actions' => [
			'options' => [
				'pk' => ['sm_cntractn_id'],
				'model' => 'numbers_backend_system_controller_model_actions',
				'method' => 'save'
			],
			'data' => [
				// list related items
				['sm_cntractn_id' => 1000, 'sm_cntractn_code' => 'list_view', 'sm_cntractn_name' => 'View List', 'sm_cntractn_inactive' => 0],
				['sm_cntractn_id' => 1010, 'sm_cntractn_code' => 'list_export', 'sm_cntractn_name' => 'Export/Print List', 'sm_cntractn_parent_id' => 1000, 'sm_cntractn_inactive' => 0],
				// record related items
				['sm_cntractn_id' => 2000, 'sm_cntractn_code' => 'record_view', 'sm_cntractn_name' => 'View Record', 'sm_cntractn_inactive' => 0],
				['sm_cntractn_id' => 2010, 'sm_cntractn_code' => 'record_new', 'sm_cntractn_name' => 'New Record', 'sm_cntractn_parent_id' => 2000, 'sm_cntractn_inactive' => 0],
				['sm_cntractn_id' => 2020, 'sm_cntractn_code' => 'record_edit', 'sm_cntractn_name' => 'Edit Record', 'sm_cntractn_parent_id' => 2000, 'sm_cntractn_inactive' => 0],
				// do inactivation later
				//['sm_cntractn_id' => 2030, 'sm_cntractn_code' => 'record_inactivate', 'sm_cntractn_name' => 'Inactivate Record', 'sm_cntractn_parent_id' => 2020, 'sm_cntractn_inactive' => 0],
				['sm_cntractn_id' => 2040, 'sm_cntractn_code' => 'record_delete', 'sm_cntractn_name' => 'Delete Record', 'sm_cntractn_parent_id' => 2020, 'sm_cntractn_inactive' => 0],
				// login related items, requires View Record permission
				//['sm_cntractn_id' => 2100, 'sm_cntractn_code' => 'login_freeze_account', 'sm_cntractn_name' => 'Login Freeze Account', 'sm_cntractn_parent_id' => 2000, 'sm_cntractn_inactive' => 0],
				//['sm_cntractn_id' => 2110, 'sm_cntractn_code' => 'login_reset_password', 'sm_cntractn_name' => 'Login Reset Password', 'sm_cntractn_parent_id' => 2000, 'sm_cntractn_inactive' => 0]
			]
		],
	];
}