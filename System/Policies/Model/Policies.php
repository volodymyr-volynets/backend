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

class Policies extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Policies';
    public $name = 'sm_policies';
    public $pk = ['sm_policy_tenant_id', 'sm_policy_code'];
    public $tenant = true;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_policy_';
    public $columns = [
        'sm_policy_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_policy_code' => ['name' => 'Code', 'domain' => 'group_code'],
        'sm_policy_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_policy_sm_poltype_code' => ['name' => 'Code', 'domain' => 'big_code', 'null' => true],
        'sm_policy_description' => ['name' => 'Description', 'domain' => 'description', 'null' => true],
        'sm_policy_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'],
        'sm_policy_external_json' => ['name' => 'External Json', 'type' => 'json'],
        'sm_policy_internal_json' => ['name' => 'Internal Json', 'type' => 'json'],
        'sm_policy_global' => ['name' => 'Global', 'type' => 'boolean'],
        'sm_policy_weight' => ['name' => 'Weight', 'domain' => 'weight', 'null' => true],
        'sm_policy_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_policies_pk' => ['type' => 'pk', 'columns' => ['sm_policy_tenant_id', 'sm_policy_code']],
        'sm_policy_module_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_policy_module_code'],
            'foreign_model' => '\Numbers\Backend\System\Modules\Model\Modules',
            'foreign_columns' => ['sm_module_code']
        ],
    ];
    public $indexes = [];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = false;
    public $options_map = [
        'sm_policy_name' => 'name',
        'sm_policy_code' => 'name',
        'sm_policy_inactive' => 'inactive',
    ];
    public $options_active = [
        'sm_policy_inactive' => 0,
    ];
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
