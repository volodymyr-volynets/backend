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

class APIMethods extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Resource API Methods';
    public $name = 'sm_resource_api_methods';
    public $pk = ['sm_rsrcapimeth_resource_id', 'sm_rsrcapimeth_method_code'];
    public $tenant = false;
    public $orderby = ['sm_rsrcapimeth_timestamp' => SORT_ASC];
    public $limit;
    public $column_prefix = 'sm_rsrcapimeth_';
    public $columns = [
        'sm_rsrcapimeth_resource_id' => ['name' => 'Resource #', 'domain' => 'resource_id'],
        'sm_rsrcapimeth_timestamp' => ['name' => 'Timestamp', 'domain' => 'timestamp_now'],
        'sm_rsrcapimeth_method_code' => ['name' => 'Method Code', 'domain' => 'code'], // controlls access to controller's action in the code
        'sm_rsrcapimeth_method_name' => ['name' => 'Method Name', 'domain' => 'name'],
        'sm_rsrcapimeth_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
    ];
    public $constraints = [
        'sm_resource_api_methods_pk' => ['type' => 'pk', 'columns' => ['sm_rsrcapimeth_resource_id', 'sm_rsrcapimeth_method_code']],
        'sm_rsrcapimeth_resource_id_fk' => [
            'type' => 'fk',
            'columns' => ['sm_rsrcapimeth_resource_id'],
            'foreign_model' => '\Numbers\Backend\System\Modules\Model\Resources',
            'foreign_columns' => ['sm_resource_id']
        ],
    ];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = false;
    public $options_map = [
        'sm_rsrcapimeth_method_name' => 'name',
        'sm_rsrcapimeth_inactive' => 'inactive',
    ];
    public $options_active = [
        'sm_rsrcapimeth_inactive' => 0,
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
