<?php

namespace Numbers\Backend\Db\Test\Model\Temporary;
class Employees extends \Object\Table\Temporary {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Test Employees (Temporary)';
	public $name = 'temp_sm_test_employees';
	public $pk = ['id'];
	public $tenant;
	public $orderby;
	public $limit;
	public $column_prefix = '';
	public $columns = [
		'id' => ['name' => '#', 'domain' => 'group_id'],
		'first_name' => ['name' => 'First name', 'domain' => 'name'],
		'last_name' => ['name' => 'Last name', 'domain' => 'name'],
	];
	public $engine = [
		'MySQLi' => 'InnoDB'
	];
}