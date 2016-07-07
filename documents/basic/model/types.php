<?php

class numbers_backend_documents_basic_model_types extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'dc.types';
	public $pk = ['dc_type_code'];
	public $orderby;
	public $limit;
	public $column_prefix = 'dc_type_';
	public $columns = [
		'dc_type_code' => ['name' => 'Type Code', 'domain' => 'type_code'],
		'dc_type_name' => ['name' => 'Name', 'domain' => 'name'],
		'dc_type_id_required' => ['name' => 'Number Required', 'type' => 'boolean'],
		'dc_type_date_required' => ['name' => 'Date Required', 'type' => 'boolean'],
		'dc_type_comment_required' => ['name' => 'Comment Required', 'type' => 'boolean'],
		'dc_type_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'dc_types_pk' => ['type' => 'pk', 'columns' => ['dc_type_code']]
	];
	public $history = false;
	public $audit = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'mysqli' => 'InnoDB'
	];

	public $cache = true;
	public $cache_tags = [];
	public $cache_memory = false;
}