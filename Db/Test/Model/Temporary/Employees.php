<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Test\Model\Temporary;

use Object\Table\Temporary;

class Employees extends Temporary
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Test Employees (Temporary)';
    public $name = 'temp_sm_test_employees';
    public $pk = ['id'];
    public $tenant;
    public $orderby;
    public $limit;
    public $column_prefix = '';
    public $columns = [
        'id' => ['name' => '#', 'domain' => 'group_id'],
        'first_name' => ['name' => 'First name', 'domain' => 'name'],
        'last_name' => ['name' => 'Last name', 'domain' => 'name'],
    ];
    public $engine = [
        'MySQLi' => 'InnoDB'
    ];
}
