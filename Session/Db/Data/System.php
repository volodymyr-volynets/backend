<?php

namespace Numbers\Backend\Session\Db\Data;
class System extends \Object\Import {
	public $data = [
		'controllers' => [
			'options' => [
				'pk' => ['sm_resource_id'],
				'model' => '\Numbers\Backend\System\Modules\Model\Collection\Resources',
				'method' => 'save'
			],
			'data' => [
				[
					'sm_resource_id' => '::id::\Numbers\Backend\Session\Db\Controller\Report\SessionLog',
					'sm_resource_code' => '\Numbers\Backend\Session\Db\Controller\Report\SessionLog',
					'sm_resource_type' => 100,
					'sm_resource_name' => 'S/M Session Log',
					'sm_resource_description' => null,
					'sm_resource_icon' => 'sign-in',
					'sm_resource_module_code' => 'SM',
					'sm_resource_group1_name' => 'Operations',
					'sm_resource_group2_name' => 'System Management',
					'sm_resource_group3_name' => 'Miscellaneous',
					'sm_resource_group4_name' => 'Reports',
					'sm_resource_group5_name' => null,
					'sm_resource_group6_name' => null,
					'sm_resource_group7_name' => null,
					'sm_resource_group8_name' => null,
					'sm_resource_group9_name' => null,
					'sm_resource_acl_public' => 0,
					'sm_resource_acl_authorized' => 1,
					'sm_resource_acl_permission' => 1,
					'sm_resource_menu_acl_resource_id' => null,
					'sm_resource_menu_acl_method_code' => null,
					'sm_resource_menu_acl_action_id' => null,
					'sm_resource_menu_url' => null,
					'sm_resource_menu_options_generator' => null,
					'sm_resource_inactive' => 0,
					'\Numbers\Backend\System\Modules\Model\Resource\Features' => [],
					'\Numbers\Backend\System\Modules\Model\Resource\Map' => [
						[
							'sm_rsrcmp_method_code' => 'Index',
							'sm_rsrcmp_action_id' => '::id::List_View',
							'sm_rsrcmp_inactive' => 0
						]
					]
				]
			]
		],
		'menu' => [
			'options' => [
				'pk' => ['sm_resource_id'],
				'model' => '\Numbers\Backend\System\Modules\Model\Collection\Resources',
				'method' => 'save'
			],
			'data' => [
				[
					'sm_resource_id' => '::id::\Menu\Numbers\Backend\Session\Db\Controller\Report\SessionLog',
					'sm_resource_code' => '\Menu\Numbers\Backend\Session\Db\Controller\Report\SessionLog',
					'sm_resource_type' => 200,
					'sm_resource_name' => 'Session Log',
					'sm_resource_description' => null,
					'sm_resource_icon' => 'sign-in',
					'sm_resource_module_code' => 'SM',
					'sm_resource_group1_name' => 'Operations',
					'sm_resource_group2_name' => 'System Management',
					'sm_resource_group3_name' => 'Miscellaneous',
					'sm_resource_group4_name' => 'Reports',
					'sm_resource_group5_name' => null,
					'sm_resource_group6_name' => null,
					'sm_resource_group7_name' => null,
					'sm_resource_group8_name' => null,
					'sm_resource_group9_name' => null,
					'sm_resource_acl_public' => 0,
					'sm_resource_acl_authorized' => 0,
					'sm_resource_acl_permission' => 1,
					'sm_resource_menu_acl_resource_id' => '::id::\Numbers\Backend\Session\Db\Controller\Report\SessionLog',
					'sm_resource_menu_acl_method_code' => 'index',
					'sm_resource_menu_acl_action_id' => '::id::List_View',
					'sm_resource_menu_url' => '/Numbers/Backend/Session/Db/Controller/Report/SessionLog',
					'sm_resource_menu_options_generator' => null,
					'sm_resource_inactive' => 0
				]
			]
		]
	];
}