<?php

namespace Numbers\Backend\Db\Test\Model\Employee;
class Audits extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Employee Audits';
	public $name = 'sm_employee_audits';
	public $pk = ['id'];
	public $tenant;
	public $orderby;
	public $limit;
	public $column_prefix = '';
	public $columns = [
		'id' => ['name' => '#', 'domain' => 'group_id_sequence'],
		'employee_id' => ['name' => 'Employee #', 'domain' => 'group_id'],
		'first_name' => ['name' => 'First name', 'domain' => 'name', 'null' => true],
		'last_name' => ['name' => 'Last name', 'domain' => 'name'],
		'changed_on' => ['name' => 'Changed On', 'type' => 'timestamp'],
	];
	public $constraints = [
		'sm_employee_audits_pk' => ['type' => 'pk', 'columns' => ['id']],
		'employee_id_fk' => [
			'type' => 'fk',
			'columns' => ['employee_id'],
			'foreign_model' => '\Numbers\Backend\Db\Test\Model\Employees',
			'foreign_columns' => ['id']
		],
	];
	public $indexes = [];
	public $history = false;
	public $audit = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'MySQLi' => 'InnoDB'
	];

	public $cache = false;
	public $cache_tags = [];
	public $cache_memory = true;

	public $data_asset = [
		'classification' => 'public',
		'protection' => 0,
		'scope' => 'global'
	];
}