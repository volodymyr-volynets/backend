<?php

class numbers_backend_system_modules_model_collection_module_features extends \Object\Collection {
	public $data = [
		'model' => 'numbers_backend_system_modules_model_module_features',
		'pk' => ['sm_feature_module_code', 'sm_feature_code'],
		'details' => [
			'numbers_backend_system_modules_model_module_dependencies' => [
				'pk' => ['sm_mdldep_parent_module_code', 'sm_mdldep_parent_feature_code', 'sm_mdldep_child_module_code', 'sm_mdldep_child_feature_code'],
				'type' => '1M',
				'map' => ['sm_feature_module_code' => 'sm_mdldep_parent_module_code', 'sm_feature_code' => 'sm_mdldep_parent_feature_code']
			]
		]
	];
}
