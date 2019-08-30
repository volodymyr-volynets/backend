<?php

namespace Numbers\Backend\Db\PostgreSQL\Model;
class DBLink extends \Object\Extension {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'DBLink';
	public $schema = 'extensions';
	public $name = 'dblink';
	public $backend = 'PostgreSQL';
}