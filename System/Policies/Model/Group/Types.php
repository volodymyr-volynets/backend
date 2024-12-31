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

class Types extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Policy Group Types';
    public $name = 'sm_policy_group_types';
    public $pk = ['sm_polgrotype_tenant_id', 'sm_polgrotype_sm_polgroup_id', 'sm_polgrotype_sm_poltype_code'];
    public $tenant = true;
    public $orderby = ['sm_polgrotype_timestamp' => SORT_ASC];
    public $limit;
    public $column_prefix = 'sm_polgrotype_';
    public $columns = [
        'sm_polgrotype_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_polgrotype_sm_polgroup_id' => ['name' => 'Group #', 'domain' => 'group_id'],
        'sm_polgrotype_sm_poltype_code' => ['name' => 'Type Code', 'domain' => 'big_code'],
        'sm_polgrotype_timestamp' => ['name' => 'Timestamp', 'domain' => 'timestamp_now'],
        'sm_polgrotype_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_policy_group_types_pk' => ['type' => 'pk', 'columns' => ['sm_polgrotype_tenant_id', 'sm_polgrotype_sm_polgroup_id', 'sm_polgrotype_sm_poltype_code']],
        'sm_polgrotype_sm_poltype_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_polgrotype_sm_poltype_code'],
            'foreign_model' => '\Numbers\Backend\System\Policies\Model\Types',
            'foreign_columns' => ['sm_poltype_code']
        ],
        'sm_polgrotype_sm_polgroup_id_fk' => [
            'type' => 'fk',
            'columns' => ['sm_polgrotype_tenant_id', 'sm_polgrotype_sm_polgroup_id'],
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
