<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model;

use Object\Table;

class PrintTemplates extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Print Templates';
    public $name = 'sm_print_templates';
    public $pk = ['sm_printtemplate_code'];
    public $tenant;
    public $module;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_printtemplate_';
    public $columns = [
        'sm_printtemplate_code' => ['name' => 'Code', 'domain' => 'group_code'],
        'sm_printtemplate_type_code' => ['name' => 'Type', 'domain' => 'group_code'],
        'sm_printtemplate_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_printtemplate_activation_model' => ['name' => 'Activation Model', 'domain' => 'code', 'null' => true],
        'sm_printtemplate_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_print_templates_pk' => ['type' => 'pk', 'columns' => ['sm_printtemplate_code']],
    ];
    public $indexes = [
        'sm_print_templates_fulltext_idx' => ['type' => 'fulltext', 'columns' => ['sm_printtemplate_code', 'sm_printtemplate_name']]
    ];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = true;
    public $options_map = [
        'sm_printtemplate_name' => 'name',
        'sm_printtemplate_code' => 'name',
        'sm_printtemplate_inactive' => 'inactive'
    ];
    public $options_active = [
        'sm_printtemplate_inactive' => 0
    ];
    public $engine = [
        'MySQLi' => 'InnoDB'
    ];

    public $cache = true;
    public $cache_tags = [];
    public $cache_memory = false;

    public $data_asset = [
        'classification' => 'public',
        'protection' => 1,
        'scope' => 'global'
    ];
}
