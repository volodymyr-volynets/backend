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

class Subscriptions extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Subscriptions';
    public $name = 'sm_event_subscriptions';
    public $pk = ['sm_evtsubscription_sm_evtsubscriber_code', 'sm_evtsubscription_sm_event_code', 'sm_evtsubscription_sm_evtqueue_code'];
    public $tenant = false;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_evtsubscription_';
    public $columns = [
        'sm_evtsubscription_sm_evtsubscriber_code' => ['name' => 'Subscriber Code', 'domain' => 'group_code'],
        'sm_evtsubscription_sm_event_code' => ['name' => 'Event Code', 'domain' => 'group_code'],
        'sm_evtsubscription_sm_evtqueue_code' => ['name' => 'Queue Code', 'domain' => 'group_code'],
        'sm_evtsubscription_type_code' => ['name' => 'Type', 'domain' => 'code', 'enum' => '\Object\Enum\EventTypes'],
        'sm_evtsubscription_cron' => ['name' => 'Cron', 'domain' => 'cron_expression', 'null' => true],
        'sm_evtsubscription_max_retries' => ['name' => 'Max Retries', 'domain' => 'counter', 'default' => 0],
        'sm_evtsubscription_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
    ];
    public $constraints = [
        'sm_event_subscriptions_pk' => ['type' => 'pk', 'columns' => ['sm_evtsubscription_sm_evtsubscriber_code', 'sm_evtsubscription_sm_event_code', 'sm_evtsubscription_sm_evtqueue_code']],
        'sm_evtsubscription_sm_evtsubscriber_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_evtsubscription_sm_evtsubscriber_code'],
            'foreign_model' => '\Numbers\Backend\System\Events\Model\Subscribers',
            'foreign_columns' => ['sm_evtsubscriber_code'],
        ],
        'sm_evtsubscription_sm_event_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_evtsubscription_sm_event_code'],
            'foreign_model' => '\Numbers\Backend\System\Events\Model\Events',
            'foreign_columns' => ['sm_event_code'],
        ],
        'sm_evtsubscription_sm_evtqueue_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_evtsubscription_sm_evtqueue_code'],
            'foreign_model' => '\Numbers\Backend\System\Events\Model\Queues',
            'foreign_columns' => ['sm_evtqueue_code'],
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

    public $cache = true;
    public $cache_tags = [];
    public $cache_memory = false;

    public $who = [];

    public $data_asset = [
        'classification' => 'proprietary',
        'protection' => 1,
        'scope' => 'global'
    ];
}
