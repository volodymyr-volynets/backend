<?php

namespace Numbers\Backend\Session\Db\Model\Session;
class IPs extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Session IPs';
	public $schema;
	public $name = 'sm_session_ips';
	public $pk = ['sm_sessips_user_ip'];
	public $tenant = true;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_sessips_';
	public $columns = [
		'sm_sessips_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id', 'null' => true],
		'sm_sessips_session_id' => ['name' => 'Session #', 'type' => 'varchar', 'length' => 40],
		'sm_sessips_last_requested' => ['name' => 'Datetime Last Requested', 'type' => 'timestamp'],
		'sm_sessips_user_id' => ['name' => 'User #', 'domain' => 'user_id', 'null' => true],
		'sm_sessips_user_ip' => ['name' => 'User IP', 'domain' => 'ip'],
		'sm_sessips_pages_count' => ['name' => 'Pages Count', 'domain' => 'counter'],
		'sm_sessips_request_count' => ['name' => 'Request Count', 'domain' => 'counter', 'default' => 0],
	];
	public $constraints = [];
	public $indexes = [
		'sm_sessips_user_ip_idx' => ['type' => 'btree', 'columns' => ['sm_sessips_tenant_id', 'sm_sessips_user_ip', 'sm_sessips_last_requested']],
	];
	public $history = false;
	public $audit = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'MySQLi' => 'InnoDB'
	];

	public $cache = false;
	public $cache_tags = [];
	public $cache_memory = false;

	public $data_asset = [
		'classification' => 'client_confidential',
		'protection' => 2,
		'scope' => 'global'
	];
}