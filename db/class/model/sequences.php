<?php

class numbers_backend_db_class_model_sequences extends object_table {
	public $db_link;
	public $db_link_flag;
	public $name = 'sm.sequences';
	public $pk = ['sm_sequence_name'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_sequence_';
	public $columns = [
		'sm_sequence_name' => ['name' => 'Name', 'type' => 'varchar', 'length' => 50],
		'sm_sequence_description' => ['name' => 'Description', 'type' => 'varchar', 'length' => 255, 'null' => true],
		'sm_sequence_type' => ['name' => 'Type', 'type' => 'varchar', 'length' => 15],
		'sm_sequence_prefix' => ['name' => 'Prefix', 'type' => 'varchar', 'length' => 15, 'null' => true],
		'sm_sequence_length' => ['name' => 'Length', 'type' => 'smallint', 'default' => 0],
		'sm_sequence_suffix' => ['name' => 'Suffix', 'type' => 'varchar', 'length' => 15, 'null' => true],
		'sm_sequence_count' => ['name' => 'Numeric Count', 'type' => 'bigint']
	];
	public $constraints = [
		'sm_sequences_pk' => ['type' => 'pk', 'columns' => ['sm_sequence_name']],
	];
	public $history = false;
	public $audit = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'mysqli' => 'MyISAM'
	];

	public $cache = false;
	public $cache_link;
	public $cache_link_flag;
	public $cache_tags = [];
	public $cache_memory = false;
}