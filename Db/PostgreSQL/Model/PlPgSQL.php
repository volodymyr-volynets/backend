<?php

namespace Numbers\Backend\Db\PostgreSQL\Model;
class PlPgSQL extends \Object\Extension {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'PL/pgSQL - SQL Procedural Language';
	public $schema = 'pg_catalog';
	public $name = 'plpgsql';
	public $backend = 'PostgreSQL';
}