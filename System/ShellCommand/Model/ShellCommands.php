<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\ShellCommand\Model;

use Object\Table;

class ShellCommands extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Shell Commands';
    public $name = 'sm_shell_commands';
    public $pk = ['sm_shellcommand_code'];
    public $tenant = false;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_shellcommand_';
    public $columns = [
        'sm_shellcommand_code' => ['name' => 'Code', 'domain' => 'group_code'],
        'sm_shellcommand_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_shellcommand_description' => ['name' => 'Description', 'domain' => 'name'],
        'sm_shellcommand_model' => ['name' => 'Model', 'domain' => 'code'],
        'sm_shellcommand_command' => ['name' => 'Command', 'domain' => 'command'],
        'sm_shellcommand_module_code' => ['name' => 'Module Code', 'domain' => 'module_code', 'null' => true],
        'sm_shellcommand_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_shell_commands_pk' => ['type' => 'pk', 'columns' => ['sm_shellcommand_code']],
    ];
    public $indexes = [];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = true;
    public $options_map = [
        'sm_shellcommand_name' => 'name',
        'sm_shellcommand_code' => 'name',
        'sm_shellcommand_inactive' => 'inactive',
    ];
    public $options_active = [
        'sm_shellcommand_inactive' => 0,
    ];
    public $engine = [
        'MySQLi' => 'InnoDB'
    ];

    public $cache = true;
    public $cache_tags = [];
    public $cache_memory = false;

    public $who = [];

    public $data_asset = [
        'classification' => 'proprietary',
        'protection' => 1,
        'scope' => 'global'
    ];
}
