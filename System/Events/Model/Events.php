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

class Events extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Events';
    public $name = 'sm_events';
    public $pk = ['sm_event_code'];
    public $tenant = false;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_event_';
    public $columns = [
        'sm_event_code' => ['name' => 'Code', 'domain' => 'group_code'],
        'sm_event_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_event_description' => ['name' => 'Description', 'domain' => 'name'],
        'sm_event_model' => ['name' => 'Model', 'domain' => 'code', 'null' => true],
        'sm_event_module_code' => ['name' => 'Module Code', 'domain' => 'module_code', 'null' => true],
        'sm_event_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_events_pk' => ['type' => 'pk', 'columns' => ['sm_event_code']],
        'sm_event_module_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_event_module_code'],
            'foreign_model' => '\Numbers\Backend\System\Modules\Model\Modules',
            'foreign_columns' => ['sm_module_code']
        ],
    ];
    public $indexes = [];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = false;
    public $options_map = [
        'sm_event_name' => 'name',
        'sm_event_code' => 'name',
        'sm_event_inactive' => 'inactive',
    ];
    public $options_active = [
        'sm_event_inactive' => 0,
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
