<?php

class numbers_backend_db_class_model_sequence_types extends object_data {
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
		'ledger_simple' => ['sm_seqtype_name' => 'Ledger (Simple)'],
		'ledger_advanced' => ['sm_seqtype_name' => 'Ledger (Advanced)'],
	];
}