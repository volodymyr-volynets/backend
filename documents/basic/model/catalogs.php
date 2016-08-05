<?php

class numbers_backend_documents_basic_model_catalogs extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'dc.catalogs';
	public $pk = ['dc_catalog_code'];
	public $orderby;
	public $limit;
	public $column_prefix = 'dc_catalog_';
	public $columns = [
		'dc_catalog_code' => ['name' => 'Catalog Code', 'domain' => 'type_code'],
		'dc_catalog_name' => ['name' => 'Name', 'domain' => 'name'],
		'dc_catalog_storage_id' => ['name' => 'Storage #', 'domain' => 'type_id'],
		'dc_catalog_multiple' => ['name' => 'Multiple', 'type' => 'boolean'],
		'dc_catalog_id_required' => ['name' => 'Number Required', 'type' => 'boolean'],
		'dc_catalog_date_required' => ['name' => 'Date Required', 'type' => 'boolean'],
		'dc_catalog_comment_required' => ['name' => 'Comment Required', 'type' => 'boolean'],
		'dc_catalog_valid_extensions' => ['name' => 'Valid Extensions', 'type' => 'text', 'null' => true],
		'dc_catalog_thumbnail_settings' => ['name' => 'Thumbnail Settings', 'type' => 'text', 'null' => true],
		'dc_catalog_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'dc_catalogs_pk' => ['type' => 'pk', 'columns' => ['dc_catalog_code']],
		'dc_catalog_storage_id_fk' => [
			'type' => 'fk',
			'columns' => ['dc_catalog_storage_id'],
			'foreign_model' => 'numbers_backend_documents_basic_model_storages',
			'foreign_columns' => ['dc_storage_id'],
			'options' => [
				'match' => 'simple',
				'update' => 'no action',
				'delete' => 'no action'
			]
		]
	];
	public $indexes = [
		'dc_catalogs_fulltext_idx' => ['type' => 'fulltext', 'columns' => ['dc_catalog_code', 'dc_catalog_name']]
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