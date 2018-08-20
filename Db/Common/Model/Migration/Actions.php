<?php

namespace Numbers\Backend\Db\Common\Migration;
class Actions extends \Object\Data {
	public $column_key = 'sm_migraction_code';
	public $column_prefix = 'sm_migraction_';
	public $columns = [
		'sm_migraction_code' => ['name' => 'Migration Action', 'domain' => 'type_code'],
		'sm_migraction_name' => ['name' => 'Name', 'type' => 'text']
	];
	public $data = [
		'up' => ['sm_migraction_name' => 'Up'],
		'down' => ['sm_migraction_name' => 'Down'],
	];
}