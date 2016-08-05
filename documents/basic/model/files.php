<?php

class numbers_backend_documents_basic_model_files extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'dc.files';
	public $pk = ['dc_file_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'dc_file_';
	public $columns = [
		'dc_file_id' => ['name' => 'File #', 'domain' => 'file_id_sequence'],
		'dc_file_storage_id' => ['name' => 'Storage #', 'domain' => 'type_id'],
		'dc_file_content_type' => ['name' => 'Content Type', 'type' => 'text'],
		'dc_file_storage_name' => ['name' => 'Storage Name', 'type' => 'text']
	];
	public $constraints = [
		'dc_files_pk' => ['type' => 'pk', 'columns' => ['dc_file_id']],
		'dc_file_storage_id_fk' => [
			'type' => 'fk',
			'columns' => ['dc_file_storage_id'],
			'foreign_model' => 'numbers_backend_documents_basic_model_storages',
			'foreign_columns' => ['dc_storage_id'],
			'options' => [
				'match' => 'simple',
				'update' => 'no action',
				'delete' => 'no action'
			]
		]
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