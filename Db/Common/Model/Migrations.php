<?php

namespace Numbers\Backend\Db\Common\Model;
class Migrations extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Migrations';
	public $schema;
	public $name = 'sm_migrations';
	public $pk = ['sm_migration_id'];
	public $orderby = [
		'sm_migration_name' => SORT_DESC
	];
	public $limit;
	public $column_prefix = 'sm_migration_';
	public $columns = [
		'sm_migration_id' => ['name' => 'Migration #', 'type' => 'serial'],
		'sm_migration_db_link' => ['name' => 'Db Link', 'domain' => 'code'],
		'sm_migration_type' => ['name' => 'Type', 'domain' => 'type_code', 'options_model' => '\Numbers\Backend\Db\Common\Model\Migration\Types'],
		'sm_migration_action' => ['name' => 'Action', 'domain' => 'type_code', 'options_model' => '\Numbers\Backend\Db\Common\Model\Migration\Actions'],
		'sm_migration_name' => ['name' => 'Name', 'domain' => 'code'],
		'sm_migration_developer' => ['name' => 'Developer', 'domain' => 'name'],
		'sm_migration_inserted' => ['name' => 'Inserted', 'type' => 'timestamp'],
		'sm_migration_rolled_back' => ['name' => 'Rolled Back', 'type' => 'boolean'],
		'sm_migration_legend' => ['name' => 'Legend', 'type' => 'json', 'null' => true],
		'sm_migration_sql_counter' => ['name' => 'SQL Counter', 'domain' => 'counter'],
		'sm_migration_sql_changes' => ['name' => 'SQL Changes', 'type' => 'json', 'null' => true]
	];
	public $constraints = [
		'sm_migrations_pk' => ['type' => 'pk', 'columns' => ['sm_migration_id']],
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