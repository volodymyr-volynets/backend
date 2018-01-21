<?php

namespace Numbers\Backend\System\Modules\Data;
class Import extends \Object\Import {
	public $data = [
		'modules' => [
			'options' => [
				'pk' => ['sm_module_code'],
				'model' => '\Numbers\Backend\System\Modules\Model\Collection\Modules',
				'method' => 'save'
			],
			'data' => [
				[
					'sm_module_code' => 'SM',
					'sm_module_type' => 20,
					'sm_module_name' => 'S/M System',
					'sm_module_abbreviation' => 'S/M',
					'sm_module_icon' => 'fas fa-wrench',
					'sm_module_transactions' => 0,
					'sm_module_multiple' => 0,
					'sm_module_inactive' => 0
				]
			]
		],
		'resource_actions' => [
			'options' => [
				'pk' => ['sm_action_id'],
				'model' => '\Numbers\Backend\System\Modules\Model\Resource\Actions',
				'method' => 'save'
			],
			'data' => [
				// all items
				[
					'sm_action_id' => -1,
					'sm_action_code' => 'All_Actions',
					'sm_action_name' => 'All Actions',
					'sm_action_icon' => 'fas fa-cubes',
					'sm_action_parent_action_id' => null,
					'sm_action_inactive' => 0
				],
				// list related items
				[
					'sm_action_id' => 1000,
					'sm_action_code' => 'List_View',
					'sm_action_name' => 'View List',
					'sm_action_icon' => 'fas fa-list',
					'sm_action_parent_action_id' => null,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 1010,
					'sm_action_code' => 'List_Export',
					'sm_action_name' => 'Export/Print List',
					'sm_action_icon' => 'fas fa-print',
					'sm_action_parent_action_id' => 1000,
					'sm_action_inactive' => 0
				],
				// record related items
				[
					'sm_action_id' => 2000,
					'sm_action_code' => 'Record_View',
					'sm_action_name' => 'View Record',
					'sm_action_icon' => 'fas fa-eye',
					'sm_action_parent_action_id' => null,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 2010,
					'sm_action_code' => 'Record_New',
					'sm_action_name' => 'New Record',
					'sm_action_icon' => 'far fa-file',
					'sm_action_parent_action_id' => 2000,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 2020,
					'sm_action_code' => 'Record_Edit',
					'sm_action_name' => 'Edit Record',
					'sm_action_icon' => 'fas fa-pen-square',
					'sm_action_parent_action_id' => 2000,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 2030,
					'sm_action_code' => 'Record_Inactivate',
					'sm_action_name' => 'Inactivate Record',
					'sm_action_icon' => 'fas fa-info',
					'sm_action_parent_action_id' => 2020,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 2040,
					'sm_action_code' => 'Record_Delete',
					'sm_action_name' => 'Delete Record',
					'sm_action_icon' => 'far fa-trash-alt',
					'sm_action_parent_action_id' => 2020,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 2050,
					'sm_action_code' => 'Record_Post',
					'sm_action_name' => 'Post Record',
					'sm_action_icon' => 'fas fa-archive',
					'sm_action_parent_action_id' => 2020,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 2100,
					'sm_action_code' => 'Record_View_Comment',
					'sm_action_name' => 'View Comments',
					'sm_action_icon' => 'far fa-comment',
					'sm_action_parent_action_id' => 2020,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 2101,
					'sm_action_code' => 'Record_Make_Comment',
					'sm_action_name' => 'Make a Comment',
					'sm_action_icon' => 'far fa-comment-alt',
					'sm_action_parent_action_id' => 2100,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 2110,
					'sm_action_code' => 'Record_Audit',
					'sm_action_name' => 'View Audit',
					'sm_action_icon' => 'fab fa-linux',
					'sm_action_parent_action_id' => 2020,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 2120,
					'sm_action_code' => 'Record_View_Document',
					'sm_action_name' => 'View Documents',
					'sm_action_icon' => 'far fa-file-alt',
					'sm_action_parent_action_id' => 2020,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 2121,
					'sm_action_code' => 'Record_Add_Document',
					'sm_action_name' => 'Add Documents',
					'sm_action_icon' => 'far fa-file-code',
					'sm_action_parent_action_id' => 2120,
					'sm_action_inactive' => 0
				],
				[
					'sm_action_id' => 2900,
					'sm_action_code' => 'Record_TreeView',
					'sm_action_name' => 'Tree View',
					'sm_action_icon' => 'fas fa-sitemap',
					'sm_action_parent_action_id' => null,
					'sm_action_inactive' => 0
				],
				// import related items
				[
					'sm_action_id' => 3000,
					'sm_action_code' => 'Import_Records',
					'sm_action_name' => 'Import Records',
					'sm_action_icon' => 'fas fa-upload',
					'sm_action_parent_action_id' => null,
					'sm_action_inactive' => 0
				],
				// report related items
				[
					'sm_action_id' => 4000,
					'sm_action_code' => 'Report_View',
					'sm_action_name' => 'View Report',
					'sm_action_icon' => 'fas fa-table',
					'sm_action_parent_action_id' => null,
					'sm_action_inactive' => 0
				]
			]
		],
		'resource_methods' => [
			'options' => [
				'pk' => ['sm_method_code'],
				'model' => '\Numbers\Backend\System\Modules\Model\Resource\Methods',
				'method' => 'save'
			],
			'data' => [
				[
					'sm_method_code' => 'Index',
					'sm_method_name' => 'Index / List'
				],
				[
					'sm_method_code' => 'Edit',
					'sm_method_name' => 'Edit / Form'
				],
				[
					'sm_method_code' => 'Import',
					'sm_method_name' => 'Import / Form'
				],
				[
					'sm_method_code' => 'AllActions',
					'sm_method_name' => 'All Actions'
				],
				[
					'sm_method_code' => 'TreeView',
					'sm_method_name' => 'Tree View / Form'
				]
			]
		],
	];
}