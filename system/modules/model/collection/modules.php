<?php

class numbers_backend_system_modules_model_collection_modules extends object_collection {
	public $data = [
		'model' => 'numbers_backend_system_modules_model_modules',
		'pk' => ['sm_module_code'],
		'details' => [
			'numbers_backend_system_modules_model_module_dependencies' => [
				'pk' => ['sm_mdldep_parent_module_code', 'sm_mdldep_child_module_code', 'sm_mdldep_child_feature_code'],
				'type' => '1M',
				'map' => ['sm_module_code' => 'sm_mdldep_parent_module_code'],
				'where' => [
					'sm_mdldep_parent_feature_code' => null
				]
			]
		]
	];
}