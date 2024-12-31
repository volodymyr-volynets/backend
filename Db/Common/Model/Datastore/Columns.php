<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common\Model\Datastore;

use Object\Table;

class Columns extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Datastore Columns';
    public $schema;
    public $name = 'sm_datastore_columns';
    public $pk = ['sm_datastcolumn_tenant_id', 'sm_datastcolumn_sm_datastore_code', 'sm_datastcolumn_code'];
    public $tenant = true;
    public $orderby = ['sm_datastcolumn_order' => SORT_ASC];
    public $limit;
    public $column_prefix = 'sm_datastcolumn_';
    public $columns = [
        'sm_datastcolumn_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_datastcolumn_sm_datastore_code' => ['name' => 'Datastore Code', 'domain' => 'lcode'],
        'sm_datastcolumn_code' => ['name' => 'Code', 'domain' => 'lcode'],
        'sm_datastcolumn_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_datastcolumn_domain' => ['name' => 'Domain', 'domain' => 'ldomain', 'null' => true],
        'sm_datastcolumn_type' => ['name' => 'Type', 'domain' => 'ltype'],
        'sm_datastcolumn_null' => ['name' => 'Null', 'type' => 'boolean'],
        'sm_datastcolumn_order' => ['name' => 'Order', 'domain' => 'order', 'default' => 0],
        'sm_datastcolumn_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_datastore_columns_pk' => ['type' => 'pk', 'columns' => ['sm_datastcolumn_tenant_id', 'sm_datastcolumn_sm_datastore_code', 'sm_datastcolumn_code']],
        'sm_datastcolumn_sm_datastore_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_datastcolumn_tenant_id', 'sm_datastcolumn_sm_datastore_code'],
            'foreign_model' => '\Numbers\Backend\Db\Common\Model\Datastores',
            'foreign_columns' => ['sm_datastore_tenant_id', 'sm_datastore_code'],
        ],
    ];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = false;
    public $options_map = [
        'sm_datastcolumn_name' => 'name',
        'sm_datastcolumn_code' => 'name',
        'sm_datastcolumn_inactive' => 'inactive'
    ];
    public $options_active = [
        'sm_datastcolumn_inactive' => 0
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
}
