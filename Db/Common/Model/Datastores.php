<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common\Model;

use Object\Table;

class Datastores extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Datastores';
    public $schema;
    public $name = 'sm_datastores';
    public $pk = ['sm_datastore_tenant_id', 'sm_datastore_code'];
    public $tenant = true;
    public $orderby = [];
    public $limit;
    public $column_prefix = 'sm_datastore_';
    public $columns = [
        'sm_datastore_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_datastore_code' => ['name' => 'Code', 'domain' => 'lcode'],
        'sm_datastore_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_datastore_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'],
        // table related fields
        'sm_datastore_table_prefix' => ['name' => 'Table Prefix', 'domain' => 'lcode'],
        'sm_datastore_table_suffix' => ['name' => 'Table Suffix', 'domain' => 'lcode'],
        'sm_datastore_column_prefix' => ['name' => 'Column Prefix', 'domain' => 'lcode'],
        'sm_datastore_table_name' => ['name' => 'Table Name', 'domain' => 'lcode'],
        'sm_datastore_table_tenant' => ['name' => 'Table Tenant', 'type' => 'boolean'],
        'sm_datastore_table_module' => ['name' => 'Table Module', 'type' => 'boolean'],
        'sm_datastore_table_readonly' => ['name' => 'Readonly', 'type' => 'boolean'],
        // other
        'sm_datastore_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_datastores_pk' => ['type' => 'pk', 'columns' => ['sm_datastore_tenant_id', 'sm_datastore_code']],
    ];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = false;
    public $options_map = [
        'sm_datastore_name' => 'name',
        'sm_datastore_table_name' => 'name',
        'sm_datastore_inactive' => 'inactive'
    ];
    public $options_active = [
        'sm_datastore_inactive' => 0
    ];
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

    public const TABLE_PREFIX = 'ds_';
    public const TABLE_SUFFIX = '_datastore';

    /**
     * Get Datastore Table Name
     *
     * @param int $tenant_id
     * @param string $code
     * @return string
     */
    public static function getDatastoreTableName(int $tenant_id, string $code): string
    {
        return self::TABLE_PREFIX . 't' . str_pad($tenant_id . '', 4, '0', STR_PAD_LEFT) . $code . self::TABLE_SUFFIX;
    }
}
