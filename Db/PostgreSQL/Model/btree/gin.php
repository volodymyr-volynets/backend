<?php

namespace Numbers\Backend\Db\PostgreSQL\Model\BTree;
class GIN extends \Object\Extension {
	public $db_link;
	public $db_link_flag;
	public $title = 'btree_gin';
	public $schema = 'pg_catalog';
	public $name = 'btree_gin';
	public $backend = 'pgsql';
}