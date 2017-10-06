<?php

namespace Numbers\Backend\IO\CSV\Override;
class Exports {
	public $data = [
		'csv' => [
			'name' => 'CSV (Comma Delimited)',
			'model' => '\Numbers\Backend\IO\CSV\Exports',
			'delimiter' => ',',
			'enclosure' => '"',
			'extension' => 'csv',
			'content_type' => 'application/octet-stream'
		],
		'txt' => [
			'name' => 'Text (Tab Delimited)',
			'model' => '\Numbers\Backend\IO\CSV\Exports',
			'delimiter' => "\t",
			'enclosure' => '"',
			'extension' => 'txt',
			'content_type' => 'application/octet-stream'
		]
	];
}