<?php

namespace Numbers\Backend\System\Modules\Model\Collection\System;
class Flags extends \Object\Collection {
	public $data = [
		'model' => '\Numbers\Backend\System\Modules\Model\System\Flags',
		'pk' => ['sm_sysflag_id'],
		'details' => [
			'\Numbers\Backend\System\Modules\Model\System\Flag\Map' => [
				'pk' => ['sm_sysflgmap_sysflag_id', 'sm_sysflgmap_action_id'],
				'type' => '1M',
				'map' => ['sm_sysflag_id' => 'sm_sysflgmap_sysflag_id'],
			],
			'\Numbers\Backend\System\Modules\Model\System\Flag\Features' => [
				'pk' => ['sm_sysflgftr_sysflag_id', 'sm_sysflgftr_feature_code'],
				'type' => '1M',
				'map' => ['sm_sysflag_id' => 'sm_sysflgftr_sysflag_id'],
			]
		]
	];
}