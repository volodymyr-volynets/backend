<?php

namespace Numbers\Backend\System\Modules\Model\Collection;
class Modules extends \Object\Collection {
	public $data = [
		'model' => '\Numbers\Backend\System\Modules\Model\Modules',
		'pk' => ['sm_module_code'],
		'details' => [
			'\Numbers\Backend\System\Modules\Model\Module\Dependencies' => [
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