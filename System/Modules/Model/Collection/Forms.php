<?php

namespace Numbers\Backend\System\Modules\Model\Collection;
class Forms extends \Object\Collection {
	public $data = [
		'model' => '\Numbers\Backend\System\Modules\Model\Forms',
		'pk' => ['sm_form_code'],
		'details' => [
			'\Numbers\Backend\System\Modules\Model\Form\Fields' => [
				'pk' => ['sm_frmfield_form_code', 'sm_frmfield_code'],
				'type' => '1M',
				'map' => ['sm_form_code' => 'sm_frmfield_form_code'],
			]
		]
	];
}