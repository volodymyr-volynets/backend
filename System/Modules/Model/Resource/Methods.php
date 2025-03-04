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

class Methods extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Resource Methods';
    public $name = 'sm_resource_methods';
    public $pk = ['sm_method_code'];
    public $tenant = false;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_method_';
    public $columns = [
        'sm_method_code' => ['name' => 'Code', 'domain' => 'code'],
        'sm_method_name' => ['name' => 'Name', 'domain' => 'name'],
    ];
    public $constraints = [
        'sm_resource_methods_pk' => ['type' => 'pk', 'columns' => ['sm_method_code']],
    ];
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
    public $cache_memory = false;

    public $data_asset = [
        'classification' => 'public',
        'protection' => 0,
        'scope' => 'global'
    ];
}
