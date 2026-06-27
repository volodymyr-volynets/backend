<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Configuration\Db\Model;

use Object\Table;

class ConfigurationValues extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Configuration Db Values';
    public $name = 'sm_configuration_db_values';
    public $pk = ['sm_confdbvalue_tenant_id', 'sm_confdbvalue_section', 'sm_confdbvalue_key'];
    public $tenant = true;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_confdbvalue_';
    public $columns = [
        'sm_confdbvalue_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_confdbvalue_section' => ['name' => 'Section', 'domain' => 'code'],
        'sm_confdbvalue_key' => ['name' => 'Key', 'domain' => 'code'],
        'sm_confdbvalue_value' => ['name' => 'Value', 'type' => 'json'],
        'sm_confdbvalue_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_configuration_db_values_pk' => ['type' => 'pk', 'columns' => ['sm_confdbvalue_tenant_id', 'sm_confdbvalue_section', 'sm_confdbvalue_key']]
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
    public $cache_memory = true;

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
