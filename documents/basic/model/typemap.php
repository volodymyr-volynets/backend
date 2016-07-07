<?php

class numbers_backend_documents_basic_model_typemap extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'dc.type_map';
	public $pk = ['dc_tpctlg_type_code', 'dc_tpctlg_catalog_code'];
	public $orderby;
	public $limit;
	public $column_prefix = 'dc_tpctlg_';
	public $columns = [
		'dc_tpctlg_type_code' => ['name' => 'Type Code', 'domain' => 'type_code'],
		'dc_tpctlg_catalog_code' => ['name' => 'Catalog Code', 'domain' => 'type_code']
	];
	public $constraints = [
		'dc_type_map_pk' => ['type' => 'pk', 'columns' => ['dc_tpctlg_type_code', 'dc_tpctlg_catalog_code']],
		'dc_tpctlg_type_code_fk' => [
			'type' => 'fk',
			'columns' => ['dc_tpctlg_type_code'],
			'foreign_model' => 'numbers_backend_documents_basic_model_types',
			'foreign_columns' => ['dc_type_code'],
			'options' => [
				'match' => 'simple',
				'update' => 'no action',
				'delete' => 'no action'
			]
		],
		'dc_tpctlg_catalog_code_fk' => [
			'type' => 'fk',
			'columns' => ['dc_tpctlg_catalog_code'],
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