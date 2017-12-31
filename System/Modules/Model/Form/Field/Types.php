<?php

namespace Numbers\Backend\System\Modules\Model\Form\Field;
class Types extends \Object\Data {
	public $module_code = 'SM';
	public $title = 'S/M Form Field Types';
	public $column_key = 'sm_frmfldtype_id';
	public $column_prefix = 'sm_frmfldtype_';
	public $orderby;
	public $columns = [
		'sm_frmfldtype_id' => ['name' => 'Type #', 'domain' => 'type_id'],
		'sm_frmfldtype_name' => ['name' => 'Name', 'type' => 'text']
	];
	public $data = [
		10 => ['sm_frmfldtype_name' => 'Form'],
		20 => ['sm_frmfldtype_name' => 'List - Filter'],
		30 => ['sm_frmfldtype_name' => 'List - Header'],
		40 => ['sm_frmfldtype_name' => 'List - Sort'],
		50 => ['sm_frmfldtype_name' => 'Report - Filter'],
		60 => ['sm_frmfldtype_name' => 'Report - Header'],
		70 => ['sm_frmfldtype_name' => 'Report - Sort'],
	];
}