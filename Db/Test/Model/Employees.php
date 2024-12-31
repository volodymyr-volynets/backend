<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Test\Model;

use Object\Table;

class Employees extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Test Employees';
    public $name = 'sm_test_employees';
    public $pk = ['id'];
    public $tenant;
    public $orderby;
    public $limit;
    public $column_prefix = '';
    public $columns = [
        'id' => ['name' => '#', 'domain' => 'group_id_sequence'],
        'first_name' => ['name' => 'First name', 'domain' => 'name'],
        'last_name' => ['name' => 'Last name', 'domain' => 'name'],
        // testing add, change and drop column
        //'description' => ['name' => 'Description', 'type' => 'varchar', 'default' => null, 'null' => true, 'length' => 255]
    ];
    public $constraints = [
        'sm_test_employees_pk' => ['type' => 'pk', 'columns' => ['id']],
    ];
    public $indexes = [
        'sm_test_employees_fulltext_idx' => ['type' => 'fulltext', 'columns' => ['first_name', 'last_name']]
    ];
    public $history = false;
    public $audit = false;
    public $options_map = [];
    public $options_active = [];
    public $engine = [
        'MySQLi' => 'InnoDB'
    ];

    public $cache = false;
    public $cache_tags = [];
    public $cache_memory = true;

    public $data_asset = [
        'classification' => 'public',
        'protection' => 0,
        'scope' => 'global'
    ];
}
