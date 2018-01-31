<?php

namespace Numbers\Backend\System\Modules\Model\Collection;
class Resources extends \Object\Collection {
	public $data = [
		'model' => '\Numbers\Backend\System\Modules\Model\Resources',
		'pk' => ['sm_resource_id'],
		'details' => [
			'\Numbers\Backend\System\Modules\Model\Resource\Map' => [
				'pk' => ['sm_rsrcmp_resource_id', 'sm_rsrcmp_method_code', 'sm_rsrcmp_action_id'],
				'type' => '1M',
				'map' => ['sm_resource_id' => 'sm_rsrcmp_resource_id'],
			],
			'\Numbers\Backend\System\Modules\Model\Resource\Features' => [
				'pk' => ['sm_rsrcftr_resource_id', 'sm_rsrcftr_feature_code'],
				'type' => '1M',
				'map' => ['sm_resource_id' => 'sm_rsrcftr_resource_id'],
			]
		]
	];
}