<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Policies\Model;

use Object\Table;

class Types extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Policy Types';
    public $name = 'sm_policy_types';
    public $pk = ['sm_poltype_code'];
    public $tenant = false;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_poltype_';
    public $columns = [
        'sm_poltype_code' => ['name' => 'Code', 'domain' => 'big_code'],
        'sm_poltype_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_poltype_parent_sm_poltype_code' => ['name' => 'Code', 'domain' => 'big_code', 'null' => true],
        'sm_poltype_description' => ['name' => 'Description', 'domain' => 'description', 'null' => true],
        'sm_poltype_external_json' => ['name' => 'External Json', 'type' => 'json', 'null' => true],
        'sm_poltype_internal_json' => ['name' => 'Internal Json', 'type' => 'json', 'null' => true],
        'sm_poltype_for_main' => ['name' => 'For Main Groups/Policies', 'type' => 'boolean'],
        'sm_poltype_model' => ['name' => 'Model', 'domain' => 'model', 'null' => true],
        'sm_poltype_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_policy_types_pk' => ['type' => 'pk', 'columns' => ['sm_poltype_code']],
        'sm_poltype_parent_sm_poltype_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_poltype_parent_sm_poltype_code'],
            'foreign_model' => '\Numbers\Backend\System\Policies\Model\Types',
            'foreign_columns' => ['sm_poltype_code']
        ],
    ];
    public $indexes = [];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = false;
    public $options_map = [
        'sm_poltype_name' => 'name',
        //'sm_poltype_code' => 'name',
        'sm_poltype_for_main' => 'for_main',
        'sm_poltype_inactive' => 'inactive',
    ];
    public $options_active = [
        'sm_poltype_inactive' => 0,
    ];
    public $engine = [
        'MySQLi' => 'InnoDB'
    ];

    public $cache = true;
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
