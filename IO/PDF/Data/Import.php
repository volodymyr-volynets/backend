<?php

namespace Numbers\Backend\IO\PDF\Data;
class Import extends \Object\Import {
	public $data = [
		'features' => [
			'options' => [
				'pk' => ['sm_feature_code'],
				'model' => '\Numbers\Backend\System\Modules\Model\Collection\Module\Features',
				'method' => 'save'
			],
			'data' => [
				[
					'sm_feature_module_code' => 'SM',
					'sm_feature_code' => 'SM::RENDERER_PDF',
					'sm_feature_type' => 10,
					'sm_feature_name' => 'S/M PDF Renderer',
					'sm_feature_icon' => 'far fa-file-pdf',
					'sm_feature_activation_model' => null,
					'sm_feature_activated_by_default' => 1,
					'sm_feature_inactive' => 0,
				],
			]
		]
	];
}