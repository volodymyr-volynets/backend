<?php

class numbers_backend_documents_basic_model_documents extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'dc.documents';
	public $pk = ['dc_document_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'dc_document_';
	public $columns = [
		'dc_document_id' => ['name' => 'File #', 'domain' => 'document_id_sequence'],
		'dc_document_file_id' => ['name' => 'Hash', 'domain' => 'document_id'],
		'dc_document_catalog_code' => ['name' => 'Catalog Code', 'domain' => 'type_code'],
		'dc_document_content_type' => ['name' => 'Content Type', 'type' => 'text'],
		'dc_document_name' => ['name' => 'Name', 'type' => 'text']
	];
	public $constraints = [
		'dc_documents_pk' => ['type' => 'pk', 'columns' => ['dc_document_id']],
		'dc_document_file_id_fk' => [
			'type' => 'fk',
			'columns' => ['dc_document_file_id'],
			'foreign_model' => 'numbers_backend_documents_basic_model_files',
			'foreign_columns' => ['dc_file_id'],
			'options' => [
				'match' => 'simple',
				'update' => 'no action',
				'delete' => 'no action'
			]
		],
		'dc_document_catalog_code_fk' => [
			'type' => 'fk',
			'columns' => ['dc_document_catalog_code'],
			'foreign_model' => 'numbers_backend_documents_basic_model_catalogs',
			'foreign_columns' => ['dc_catalog_code'],
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