<?php

class numbers_backend_db_class_model_migrations extends object_table {
	public $db_link;
	public $db_link_flag;
	public $name = 'sm_migrations';
	public $pk = ['sm_migration_name'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_migration_';
	public $columns = [
		'sm_migration_timestamp' => ['name' => 'Timestamp', 'type' => 'timestamp'],
		'sm_migration_inserted' => ['name' => 'Inserted', 'type' => 'timestamp'],
		'sm_migration_changes' => ['name' => 'Changes', 'type' => 'json', 'null' => true]
	];
	public $constraints = [
		'sm_migrations_pk' => ['type' => 'pk', 'columns' => ['sm_migration_timestamp']],
	];
	public $history = false;
	public $audit = false;
	public $optimistic_lock = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'mysqli' => 'InnoDB'
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