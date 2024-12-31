<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Session\Db\Model\Session;

use Object\Table;

class IPs extends Table
{
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
        'sm_sessips_session_id' => ['name' => 'Session #', 'domain' => 'session_id'],
        'sm_sessips_last_requested' => ['name' => 'Datetime Last Requested', 'type' => 'timestamp'],
        'sm_sessips_user_id' => ['name' => 'User #', 'domain' => 'user_id', 'null' => true],
        'sm_sessips_user_ip' => ['name' => 'User IP', 'domain' => 'ip'],
        'sm_sessips_pages_count' => ['name' => 'Pages Count', 'domain' => 'counter'],
        'sm_sessips_request_count' => ['name' => 'Request Count', 'domain' => 'counter', 'default' => 0],
        'sm_sessips_bearer_token' => ['name' => 'Bearer Token', 'domain' => 'token', 'null' => true],
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
