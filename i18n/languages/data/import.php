<?php

class numbers_backend_i18n_languages_data_import extends object_import {
	public $import_data = [
		'languages' => [
			'options' => [
				'pk' => ['lc_language_code'],
				'model' => 'numbers_backend_i18n_languages_model_languages',
				'method' => 'save_insert_new'
			],
			'data' => [
				[
					'lc_language_code' => 'sys',
					'lc_language_name' => 'System',
					'lc_language_locale' => 'en_CA.UTF-8',
					'lc_language_rtl' => 0,
					'lc_language_inactive' => 1,
				],
				[
					'lc_language_code' => 'eng',
					'lc_language_name' => 'English',
					'lc_language_rtl' => 0,
					'lc_language_locale' => 'en_CA.UTF-8',
					'lc_language_inactive' => 0,
					'lc_language_timezone' => 'America/Toronto',
					'lc_language_date' => 'm/d/Y',
					'lc_language_time' => 'g:i:s a',
					'lc_language_datetime' => 'm/d/Y g:i:s a',
					'lc_language_timestamp' => 'm/d/Y g:i:s.u a',
					'lc_language_amount_frm' => 10,
					'lc_language_amount_fs' => 30
				],
				[
					'lc_language_code' => 'ara',
					'lc_language_name' => 'Arabic',
					'lc_language_locale' => 'ar_SA.UTF-8',
					'lc_language_rtl' => 1,
					'lc_language_inactive' => 0,
					'lc_language_timezone' => 'America/Toronto',
					'lc_language_date' => 'd/m/Y',
					'lc_language_time' => 'H:i:s',
					'lc_language_datetime' => 'd/m/Y H:i:s',
					'lc_language_timestamp' => 'd/m/Y H:i:s.u',
					'lc_language_amount_frm' => 10,
					'lc_language_amount_fs' => 30
				]
			]
		]
	];
}