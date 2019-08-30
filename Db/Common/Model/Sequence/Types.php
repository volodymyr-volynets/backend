<?php

namespace Numbers\Backend\Db\Common\Model\Sequence;
class Types extends \Object\Data {
	public $module_code = 'SM';
	public $title = 'S/M Sequence Types';
	public $column_key = 'sm_seqtype_code';
	public $column_prefix = 'sm_seqtype_';
	public $columns = [
		'sm_seqtype_code' => ['name' => 'Sequence Type', 'domain' => 'type_code'],
		'sm_seqtype_name' => ['name' => 'Name', 'type' => 'text']
	];
	public $data = [
		'global_simple' => ['sm_seqtype_name' => 'Global (Simple)'],
		'global_advanced' => ['sm_seqtype_name' => 'Global (Advanced)'],
		'tenant_simple' => ['sm_seqtype_name' => 'Tenant (Simple)'],
		'tenant_advanced' => ['sm_seqtype_name' => 'Tenant (Advanced)'],
		'module_simple' => ['sm_seqtype_name' => 'Ledger (Simple)'],
		'module_advanced' => ['sm_seqtype_name' => 'Ledger (Advanced)'],
	];
}