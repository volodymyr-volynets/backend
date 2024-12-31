<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Events\Model;

use Object\Table;

class Responses extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Event Responses';
    public $name = 'sm_event_responses';
    public $pk = ['sm_evtresponse_tenant_id', 'sm_evtresponse_id'];
    public $tenant = false;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_evtresponse_';
    public $columns = [
        'sm_evtresponse_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_evtresponse_id' => ['name' => 'UUID', 'domain' => 'uuid'],
        'sm_evtresponse_sm_evtrequest_id' => ['name' => 'Request UUID', 'domain' => 'uuid'],
        'sm_evtresponse_um_user_id' => ['name' => 'User #', 'domain' => 'user_id', 'null' => true],
        'sm_evtresponse_sm_event_code' => ['name' => 'Event Code', 'domain' => 'group_code'],
        'sm_evtresponse_sm_event_name' => ['name' => 'Event Name', 'domain' => 'name'],
        'sm_evtresponse_sm_evtqueue_code' => ['name' => 'Queue Code', 'domain' => 'group_code'],
        'sm_evtresponse_sm_evtsubscriber_code' => ['name' => 'Subscriber Code', 'domain' => 'group_code'],
        'sm_evtresponse_sm_evtsubscriber_name' => ['name' => 'Subscriber Name', 'domain' => 'name'],
        'sm_evtresponse_status_id' => ['name' => 'Status', 'domain' => 'type_id', 'enum' => '\Numbers\Backend\System\Events\Class2\RequestEventStatuses'],
        'sm_evtresponse_result' => ['name' => 'Data', 'type' => 'json'],
        'sm_evtresponse_retry' => ['name' => 'Retry', 'domain' => 'counter', 'default' => 0],
        'sm_evtresponse_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
    ];
    public $constraints = [
        'sm_event_responses_pk' => ['type' => 'pk', 'columns' => ['sm_evtresponse_tenant_id', 'sm_evtresponse_id']],
        'sm_evtresponse_sm_event_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_evtresponse_sm_event_code'],
            'foreign_model' => '\Numbers\Backend\System\Events\Model\Events',
            'foreign_columns' => ['sm_event_code'],
        ],
        'sm_evtresponse_sm_evtqueue_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_evtresponse_sm_evtqueue_code'],
            'foreign_model' => '\Numbers\Backend\System\Events\Model\Queues',
            'foreign_columns' => ['sm_evtqueue_code'],
        ],
        'sm_evtresponse_sm_evtrequest_id_fk' => [
            'type' => 'fk',
            'columns' => ['sm_evtresponse_tenant_id', 'sm_evtresponse_sm_evtrequest_id'],
            'foreign_model' => '\Numbers\Backend\System\Events\Model\Requests',
            'foreign_columns' => ['sm_evtrequest_tenant_id', 'sm_evtrequest_id'],
        ],
    ];
    public $indexes = [];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = false;
    public $options_map = [];
    public $options_active = [];
    public $engine = [
        'MySQLi' => 'InnoDB'
    ];

    public $cache = false;
    public $cache_tags = [];
    public $cache_memory = false;

    public $who = [
        'inserted' => true,
        'updated' => true,
    ];

    public $data_asset = [
        'classification' => 'proprietary',
        'protection' => 1,
        'scope' => 'global'
    ];
}
