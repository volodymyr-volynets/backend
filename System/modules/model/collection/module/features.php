<?php

namespace Numbers\Backend\System\Modules\Model\Collection\Module;
class Features extends \Object\Collection {
	public $data = [
		'model' => '\Numbers\Backend\System\Modules\Model\Module\Features',
		'pk' => ['sm_feature_module_code', 'sm_feature_code'],
		'details' => [
			'\Numbers\Backend\System\Modules\Model\Module\Dependencies' => [
				'pk' => ['sm_mdldep_parent_module_code', 'sm_mdldep_parent_feature_code', 'sm_mdldep_child_module_code', 'sm_mdldep_child_feature_code'],
				'type' => '1M',
				'map' => ['sm_feature_module_code' => 'sm_mdldep_parent_module_code', 'sm_feature_code' => 'sm_mdldep_parent_feature_code']
			]
		]
	];
}
