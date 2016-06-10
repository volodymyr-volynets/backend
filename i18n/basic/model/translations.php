<?php

class numbers_backend_i18n_basic_model_translations extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'lc.translations';
	public $pk = ['lc_translation_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'lc_translation_';
	public $columns = [
		'lc_translation_id' => ['name' => 'Translation #', 'type' => 'serial'],
		'lc_translation_language_code' => ['name' => 'Language Code', 'domain' => 'language_code'],
		'lc_translation_text_sys' => ['name' => 'System Text', 'type' => 'varchar', 'length' => 2500],
		'lc_translation_text_new' => ['name' => 'Translated Text', 'type' => 'varchar', 'length' => 2500]
	];
	public $constraints = [
		'lc_translations_pk' => ['type' => 'pk', 'columns' => ['lc_translation_id']],
		'lc_translation_text_sys_un' => ['type' => 'unique', 'columns' => ['lc_translation_language_code', 'lc_translation_text_sys']],
		'lc_translation_language_code_fk' => [
			'type' => 'fk',
			'columns' => ['lc_translation_language_code'],
			'foreign_model' => 'numbers_backend_i18n_basic_model_languages',
			'foreign_columns' => ['lc_language_code'],
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
		'mysqli' => 'MyISAM'
	];

	public $cache = false;
	public $cache_tags = ['translations'];
	public $cache_memory = false;
}