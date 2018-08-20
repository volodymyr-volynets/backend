<?php

namespace Numbers\Backend\Db\Common\Migration;
class Types extends \Object\Data {
	public $column_key = 'sm_migrtype_code';
	public $column_prefix = 'sm_migrtype_';
	public $columns = [
		'sm_migrtype_code' => ['name' => 'Migration Type', 'domain' => 'type_code'],
		'sm_migrtype_name' => ['name' => 'Name', 'type' => 'text']
	];
	public $data = [
		'schema' => ['sm_migrtype_name' => 'Direct Schema Changes'],
		'migration' => ['sm_migrtype_name' => 'Changes Through Migrations'],
	];
}