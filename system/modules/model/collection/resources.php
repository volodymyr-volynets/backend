<?php

class numbers_backend_system_modules_model_collection_resources extends object_collection {
	public $data = [
		'model' => 'numbers_backend_system_modules_model_resources',
		'pk' => ['sm_resource_id'],
		'details' => [
			'numbers_backend_system_modules_model_resource_map' => [
				'pk' => ['sm_rsrcmp_resource_id', 'sm_rsrcmp_method_code', 'sm_rsrcmp_action_id'],
				'type' => '1M',
				'map' => ['sm_resource_id' => 'sm_rsrcmp_resource_id'],
			],
			'numbers_backend_system_modules_model_resource_features' => [
				'pk' => ['sm_rsrcftr_resource_id', 'sm_rsrcftr_feature_code'],
				'type' => '1M',
				'map' => ['sm_resource_id' => 'sm_rsrcftr_resource_id'],
			]
		]
	];
}