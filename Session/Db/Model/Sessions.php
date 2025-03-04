<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Session\Db\Model;

use Object\Table;

class Sessions extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Sessions';
    public $schema;
    public $name = 'sm_sessions';
    public $pk = ['sm_session_id'];
    public $tenant = true;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_session_';
    public $columns = [
        'sm_session_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id', 'null' => true],
        'sm_session_id' => ['name' => 'Session #', 'domain' => 'session_id'],
        'sm_session_started' => ['name' => 'Datetime Started', 'type' => 'timestamp'],
        'sm_session_expires' => ['name' => 'Datetime Expires', 'type' => 'timestamp'],
        'sm_session_last_requested' => ['name' => 'Datetime Last Requested', 'type' => 'timestamp'],
        'sm_session_pages_count' => ['name' => 'Pages Count', 'domain' => 'counter'],
        'sm_session_user_id' => ['name' => 'User #', 'domain' => 'user_id', 'null' => true],
        'sm_session_user_ip' => ['name' => 'User IP', 'domain' => 'ip'],
        'sm_session_data' => ['name' => 'Session Data', 'type' => 'text', 'null' => true],
        'sm_session_country_code' => ['name' => 'Country Code', 'domain' => 'country_code', 'null' => true],
        'sm_session_request_count' => ['name' => 'Request Count', 'domain' => 'counter', 'default' => 0],
        'sm_session_bearer_token' => ['name' => 'Bearer Token', 'domain' => 'token', 'null' => true],
    ];
    public $constraints = [
        'sm_sessions_pk' => ['type' => 'pk', 'columns' => ['sm_session_id']],
    ];
    public $indexes = [
        'sm_sessions_expires_idx' => ['type' => 'btree', 'columns' => ['sm_session_expires']],
        'sm_session_user_id_idx' => ['type' => 'btree', 'columns' => ['sm_session_user_id']]
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
