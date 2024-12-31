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

class Subscribers extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Event Subscribers';
    public $name = 'sm_event_subscribers';
    public $pk = ['sm_evtsubscriber_code'];
    public $tenant = false;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_evtsubscriber_';
    public $columns = [
        'sm_evtsubscriber_code' => ['name' => 'Code', 'domain' => 'group_code'],
        'sm_evtsubscriber_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_evtsubscriber_model' => ['name' => 'Model', 'domain' => 'code'],
        'sm_evtsubscriber_module_code' => ['name' => 'Module Code', 'domain' => 'module_code', 'null' => true],
        'sm_evtsubscriber_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
    ];
    public $constraints = [
        'sm_subscribers_pk' => ['type' => 'pk', 'columns' => ['sm_evtsubscriber_code']],
        'sm_evtsubscriber_module_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_evtsubscriber_module_code'],
            'foreign_model' => '\Numbers\Backend\System\Modules\Model\Modules',
            'foreign_columns' => ['sm_module_code']
        ],
    ];
    public $indexes = [];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = false;
    public $options_map = [
        'sm_evtsubscriber_name' => 'name',
        'sm_evtsubscriber_code' => 'name',
        'sm_evtsubscriber_inactive' => 'inactive',
    ];
    public $options_active = [
        'sm_evtsubscriber_inactive' => 0,
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
