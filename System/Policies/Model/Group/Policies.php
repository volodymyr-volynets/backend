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

class Policies extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Policy Group Policies';
    public $name = 'sm_policy_group_policies';
    public $pk = ['sm_polgropolicy_tenant_id', 'sm_polgropolicy_sm_polgroup_id', 'sm_polgropolicy_sm_policy_tenant_id', 'sm_polgropolicy_sm_policy_code'];
    public $tenant = true;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_polgropolicy_';
    public $columns = [
        'sm_polgropolicy_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_polgropolicy_sm_polgroup_id' => ['name' => 'Group #', 'domain' => 'group_id'],
        'sm_polgropolicy_sm_policy_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_polgropolicy_sm_policy_code' => ['name' => 'Code', 'domain' => 'group_code'],
        'sm_polgropolicy_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_policy_group_policies_pk' => ['type' => 'pk', 'columns' => ['sm_polgropolicy_tenant_id', 'sm_polgropolicy_sm_polgroup_id', 'sm_polgropolicy_sm_policy_tenant_id', 'sm_polgropolicy_sm_policy_code']],
        'sm_polgropolicy_sm_polgroup_id_fk' => [
            'type' => 'fk',
            'columns' => ['sm_polgropolicy_tenant_id', 'sm_polgropolicy_sm_polgroup_id'],
            'foreign_model' => '\Numbers\Backend\System\Policies\Model\Groups',
            'foreign_columns' => ['sm_polgroup_tenant_id', 'sm_polgroup_id']
        ],
        'sm_polgropolicy_sm_policy_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_polgropolicy_sm_policy_tenant_id', 'sm_polgropolicy_sm_policy_code'],
            'foreign_model' => '\Numbers\Backend\System\Policies\Model\Policies',
            'foreign_columns' => ['sm_policy_tenant_id', 'sm_policy_code']
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
