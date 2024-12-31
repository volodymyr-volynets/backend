<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Policies\Model\Group;

use Object\Table;

class Groups extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Policy Group Groups';
    public $name = 'sm_policy_group_groups';
    public $pk = ['sm_polgrogroup_tenant_id', 'sm_polgrogroup_sm_polgroup_id', 'sm_polgrogroup_child_sm_polgroup_tenant_id', 'sm_polgrogroup_child_sm_polgroup_id'];
    public $tenant = true;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_polgrogroup_';
    public $columns = [
        'sm_polgrogroup_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_polgrogroup_sm_polgroup_id' => ['name' => 'Group #', 'domain' => 'group_id'],
        'sm_polgrogroup_child_sm_polgroup_tenant_id' => ['name' => 'Child Tenant #', 'domain' => 'tenant_id'],
        'sm_polgrogroup_child_sm_polgroup_id' => ['name' => 'Child Group #', 'domain' => 'group_id'],
        'sm_polgrogroup_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_policy_group_groups_pk' => ['type' => 'pk', 'columns' => ['sm_polgrogroup_tenant_id', 'sm_polgrogroup_sm_polgroup_id', 'sm_polgrogroup_child_sm_polgroup_tenant_id', 'sm_polgrogroup_child_sm_polgroup_id']],
        'sm_polgrogroup_sm_polgroup_id_fk' => [
            'type' => 'fk',
            'columns' => ['sm_polgrogroup_tenant_id', 'sm_polgrogroup_sm_polgroup_id'],
            'foreign_model' => '\Numbers\Backend\System\Policies\Model\Groups',
            'foreign_columns' => ['sm_polgroup_tenant_id', 'sm_polgroup_id']
        ],
        'sm_polgrogroup_child_sm_polgroup_id_fk' => [
            'type' => 'fk',
            'columns' => ['sm_polgrogroup_child_sm_polgroup_tenant_id', 'sm_polgrogroup_child_sm_polgroup_id'],
            'foreign_model' => '\Numbers\Backend\System\Policies\Model\Groups',
            'foreign_columns' => ['sm_polgroup_tenant_id', 'sm_polgroup_id']
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

    public $who = [];

    public $data_asset = [
        'classification' => 'proprietary',
        'protection' => 1,
        'scope' => 'global'
    ];
}
