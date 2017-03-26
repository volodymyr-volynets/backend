<?php

class numbers_backend_documents_storages_folder_data_import extends \Object\Import {
	public $import_data = [
		'languages' => [
			'options' => [
				'pk' => ['dc_storage_id'],
				'model' => 'numbers_backend_documents_basic_model_storages',
				'method' => 'save'
			],
			'data' => [
				[
					'dc_storage_id' => 100,
					'dc_storage_name' => 'Storage Folder'
				]
			]
		]
	];
}