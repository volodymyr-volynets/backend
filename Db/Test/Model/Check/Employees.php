<?php

namespace Numbers\Backend\Db\Test\Model\Check;
class Employees extends \Object\Check {
	public $db_link;
	public $db_link_flag;
	public $schema;
	public $name = 'sm_test_employees_first_name_check';
	public $full_table_name = 'sm_test_employees';
	public $backend = ['Oracle', 'MySQLi', 'PostgreSQL'];
	public $sql_version = '1.0.0';

	public function definition() {
		$this->query->where('AND', function (& $query) {
			$query->where('OR', ['[NEW].first_name', '<>', '[NEW].last_name', true]);
		});
	}
}