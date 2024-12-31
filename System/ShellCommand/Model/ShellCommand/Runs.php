<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\ShellCommand\Model\ShellCommand;

use Object\Table;

class Runs extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Shell Command Runs';
    public $name = 'sm_shell_command_runs';
    public $pk = ['sm_shellcomrun_tenant_id', 'sm_shellcomrun_id'];
    public $tenant = true;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_shellcomrun_';
    public $columns = [
        'sm_shellcomrun_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id', 'default' => 0],
        'sm_shellcomrun_id' => ['name' => 'Run #', 'domain' => 'big_id_sequence'],
        'sm_shellcomrun_status_id' => ['name' => 'Status', 'domain' => 'type_id', 'enum' => '\Numbers\Backend\System\ShellCommand\Class2\ShellCommand\Statuses'],
        'sm_shellcomrun_start_timestamp' => ['name' => 'Start Timestamp', 'domain' => 'timestamp_now'],
        'sm_shellcomrun_finish_timestamp' => ['name' => 'Finish Timestamp', 'type' => 'timestamp', 'null' => true, 'default' => null],
        'sm_shellcomrun_percent_complete' => ['name' => 'Percent Complate', 'domain' => 'percent_float', 'default' => 0],
        'sm_shellcomrun_user_id' => ['name' => 'User #', 'domain' => 'user_id'],
        'sm_shellcomrun_shellcommand_code' => ['name' => 'Code', 'domain' => 'group_code'],
        'sm_shellcomrun_shellcommand_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_shellcomrun_shell_output' => ['name' => 'Shell Output', 'type' => 'text', 'null' => true, 'default' => null],
        'sm_shellcomrun_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
    ];
    public $constraints = [
        'sm_shell_command_runs_pk' => ['type' => 'pk', 'columns' => ['sm_shellcomrun_tenant_id', 'sm_shellcomrun_id']],
        'sm_shellcomrun_shellcommand_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_shellcomrun_shellcommand_code'],
            'foreign_model' => '\Numbers\Backend\System\ShellCommand\Model\ShellCommands',
            'foreign_columns' => ['sm_shellcommand_code']
        ],
    ];
    public $indexes = [];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = false;
    public $options_map = [];
    public $options_active = [
        'sm_shellcomrun_inactive' => 0,
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
