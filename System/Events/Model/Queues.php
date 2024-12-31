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

class Queues extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Event Queues';
    public $name = 'sm_event_queues';
    public $pk = ['sm_evtqueue_code'];
    public $tenant = false;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_evtqueue_';
    public $columns = [
        'sm_evtqueue_code' => ['name' => 'Code', 'domain' => 'group_code'],
        'sm_evtqueue_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_evtqueue_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_event_queues_pk' => ['type' => 'pk', 'columns' => ['sm_evtqueue_code']],
    ];
    public $indexes = [];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = false;
    public $options_map = [
        'sm_evtqueue_name' => 'name',
        'sm_evtqueue_code' => 'name',
        'sm_evtqueue_inactive' => 'inactive',
    ];
    public $options_active = [
        'sm_evtqueue_inactive' => 0,
    ];
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
