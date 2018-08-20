<?php

namespace Numbers\Backend\Db\Test\Model\View;
class Employees extends \Object\View {
	public $db_link;
	public $db_link_flag;
	public $schema;
	public $name = 'sm_employees_view';
	public $backend = ['Oracle', 'MySQLi', 'PostgreSQL'];
	public $tenant;
	public $column_prefix = '';

	public function definition() {
		$this->query->from(new \Numbers\Backend\Db\Test\Model\Employees(), 'a');
		$this->query->columns([
			'id' => 'a.id',
			'first_name' => 'a.first_name',
			'last_name' => 'a.last_name',
		]);
	}
}