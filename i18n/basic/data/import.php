<?php

class numbers_backend_i18n_basic_data_import extends object_import {
	public $import_data = [
		'languages' => [
			'options' => [
				'pk' => ['lc_language_code'],
				'model' => 'numbers_backend_i18n_basic_model_languages',
				'method' => 'save_insert_new'
			],
			'data' => [
				[
					'lc_language_code' => 'sys',
					'lc_language_name' => 'System',
					'lc_language_locale' => 'en_US.utf8',
					'lc_language_inactive' => 0,
				],
				[
					'lc_language_code' => 'eng',
					'lc_language_name' => 'English',
					'lc_language_locale' => 'en_US.utf8',
					'lc_language_inactive' => 0,
				]
			]
		]
	];
}