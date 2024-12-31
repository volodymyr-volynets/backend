<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Test\Model\View;

use Object\View;

class Employees extends View
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $schema;
    public $name = 'sm_employees_view';
    public $pk = ['id'];
    public $backend = ['Oracle', 'MySQLi', 'PostgreSQL'];
    public $sql_version = '1.0.0';
    public $tenant;

    public $cache = false;
    public $cache_tags = [];
    public $cache_memory = true;

    public function definition()
    {
        $this->query->from(new \Numbers\Backend\Db\Test\Model\Employees(), 'a');
        $this->query->columns([
            'id' => 'a.id',
            'first_name' => 'a.first_name',
            'last_name' => 'a.last_name',
        ]);
    }
}
