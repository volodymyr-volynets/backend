<?php

class numbers_backend_documents_basic_model_storages extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'dc.storages';
	public $pk = ['dc_storage_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'dc_storage_';
	public $columns = [
		'dc_storage_id' => ['name' => 'Storage #', 'domain' => 'type_id'],
		'dc_storage_name' => ['name' => 'Name', 'domain' => 'name']
	];
	public $constraints = [
		'dc_storages_pk' => ['type' => 'pk', 'columns' => ['dc_storage_id']]
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