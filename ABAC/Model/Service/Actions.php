<?php

namespace Numbers\Backend\ABAC\Model\Service;
class Actions extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M ABAC Service Actions';
	public $name = 'sm_abac_service_actions';
	public $pk = ['sm_abacservact_abacservice_id', 'sm_abacservact_action_id'];
	public $tenant = false;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_abacservact_';
	public $columns = [
		'sm_abacservact_abacservice_id' => ['name' => 'Service #', 'domain' => 'service_id'],
		'sm_abacservact_action_id' => ['name' => 'Action #', 'domain' => 'action_id'],
		'sm_abacservact_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
	];
	public $constraints = [
		'sm_abac_service_actions_pk' => ['type' => 'pk', 'columns' => ['sm_abacservact_abacservice_id', 'sm_abacservact_action_id']],
		'sm_abacservact_abacservice_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_abacservact_abacservice_id'],
			'foreign_model' => '\Numbers\Backend\ABAC\Model\Services',
			'foreign_columns' => ['sm_abacservice_id']
		],
		'sm_abacservact_action_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_abacservact_action_id'],
			'foreign_model' => '\Numbers\Backend\System\Modules\Model\Resource\Actions',
			'foreign_columns' => ['sm_action_id']
		]
	];
	public $history = false;
	public $audit = false;
	public $optimistic_lock = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'MySQLi' => 'InnoDB'
	];

	public $cache = false;
	public $cache_tags = [];
	public $cache_memory = false;

	public $data_asset = [
		'classification' => 'public',
		'protection' => 0,
		'scope' => 'global'
	];
}