<?php

namespace Numbers\Backend\System\Modules\Model;
class Forms extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Forms';
	public $name = 'sm_forms';
	public $pk = ['sm_form_code'];
	public $tenant = false;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_form_';
	public $columns = [
		'sm_form_code' => ['name' => 'Code', 'domain' => 'code'],
		'sm_form_type' => ['name' => 'Type', 'domain' => 'type_id', 'options_model' => '\Numbers\Backend\System\Modules\Model\Form\Types'],
		'sm_form_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'],
		'sm_form_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_form_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_forms_pk' => ['type' => 'pk', 'columns' => ['sm_form_code']],
		'sm_form_code_un' => ['type' => 'unique', 'columns' => ['sm_form_code']],
		'sm_form_module_code_fk' => [
			'type' => 'fk',
			'columns' => ['sm_form_module_code'],
			'foreign_model' => '\Numbers\Backend\System\Modules\Model\Modules',
			'foreign_columns' => ['sm_module_code']
		]
	];
	public $indexes = [];
	public $history = false;
	public $audit = false;
	public $optimistic_lock = false;
	public $options_map = [
		'sm_form_name' => 'name'
	];
	public $options_active = [
		'sm_form_inactive' => 0
	];
	public $engine = [
		'MySQLi' => 'InnoDB'
	];

	public $cache = true;
	public $cache_tags = [];
	public $cache_memory = false;

	public $data_asset = [
		'classification' => 'public',
		'protection' => 0,
		'scope' => 'global'
	];
}