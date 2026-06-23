<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common\Model\Shareable;

use Object\Table;

class Groups extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Shareable Groups';
    public $schema;
    public $name = 'sm_shareable_groups';
    public $pk = ['sm_sharegrp_code'];
    public $orderby = [
        'sm_sharegrp_name' => SORT_ASC,
    ];
    public $limit;
    public $column_prefix = 'sm_sharegrp_';
    public $columns = [
        'sm_sharegrp_code' => ['name' => 'Field Code', 'domain' => 'code'],
        'sm_sharegrp_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_sharegrp_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'],
        'sm_sharegrp_t9_forms' => ['name' => 'T/9 Forms', 'type' => 'boolean'],
        'sm_sharegrp_collection_model_code' => ['name' => 'Collection Model', 'domain' => 'model', 'null' => true],
        // other
        'sm_sharegrp_disabled' => ['name' => 'Disabled', 'type' => 'boolean'],
        'sm_sharegrp_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_shareable_groups_pk' => ['type' => 'pk', 'columns' => ['sm_sharegrp_code']],
    ];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = false;
    public $options_map = [
        'sm_sharegrp_name' => 'name',
        'sm_sharegrp_code' => 'name',
        'sm_sharegrp_disabled' => 'disabled',
        'sm_sharegrp_inactive' => 'inactive'
    ];
    public $options_active = [
        'sm_sharegrp_inactive' => 0
    ];
    public $engine = [
        'MySQLi' => 'InnoDB'
    ];

    public $cache = true;
    public $cache_tags = [];
    public $cache_memory = true;

    public $data_asset = [
        'classification' => 'public',
        'protection' => 0,
        'scope' => 'global'
    ];
}
