<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model\Resource;

use Object\Table;

class Tenants extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Resource Tenants';
    public $name = 'sm_resource_tenants';
    public $pk = ['sm_rsrctenant_resource_id', 'sm_rsrctenant_tenant_code'];
    public $tenant = false;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_rsrctenant_';
    public $columns = [
        'sm_rsrctenant_resource_id' => ['name' => 'Resource #', 'domain' => 'resource_id'],
        'sm_rsrctenant_tenant_code' => ['name' => 'Tenant Code', 'domain' => 'domain_part'],
        'sm_rsrctenant_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
    ];
    public $constraints = [
        'sm_resource_tenants_pk' => ['type' => 'pk', 'columns' => ['sm_rsrctenant_resource_id', 'sm_rsrctenant_tenant_code']],
    ];
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

    public $data_asset = [
        'classification' => 'public',
        'protection' => 0,
        'scope' => 'global'
    ];
}
