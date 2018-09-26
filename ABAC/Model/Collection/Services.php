<?php

namespace Numbers\Backend\ABAC\Model\Collection;
class Services extends \Object\Collection {
	public $data = [
		'model' => '\Numbers\Backend\ABAC\Model\Services',
		'pk' => ['sm_abacservice_id'],
		'details' => [
			'\Numbers\Backend\ABAC\Model\Service\Actions' => [
				'pk' => ['sm_abacservact_abacservice_id', 'sm_abacservact_action_id'],
				'type' => '1M',
				'map' => ['sm_abacservice_id' => 'sm_abacservact_abacservice_id'],
			],
		]
	];
}