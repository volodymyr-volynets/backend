<?php

namespace Numbers\Backend\Db\Test\Model\Employee;
class Tenanted extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Test Employee Tenanted';
	public $name = 'sm_test_employee_tenanted';
	public $pk = ['tenant_id', 'id'];
	public $tenant = true;
	public $orderby;
	public $limit;
	public $column_prefix = '';
	public $columns = [
		'tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
		'id' => ['name' => '#', 'domain' => 'group_id_sequence'],
		'first_name' => ['name' => 'First name', 'domain' => 'name'],
		'last_name' => ['name' => 'Last name', 'domain' => 'name']
	];
	public $constraints = [
		'sm_test_employee_tenanted_pk' => ['type' => 'pk', 'columns' => ['tenant_id', 'id']],
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