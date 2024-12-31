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

class Modules extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Modules';
    public $name = 'sm_modules';
    public $pk = ['sm_module_code'];
    public $tenant = false;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_module_';
    public $columns = [
        'sm_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'],
        'sm_module_type' => ['name' => 'Type', 'domain' => 'type_id', 'options_model' => '\Numbers\Backend\System\Modules\Model\Module\Types'],
        'sm_module_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_module_abbreviation' => ['name' => 'Abbreviation', 'domain' => 'name'],
        'sm_module_icon' => ['name' => 'Name', 'domain' => 'icon', 'null' => true],
        'sm_module_transactions' => ['name' => 'Transactions', 'type' => 'boolean'],
        'sm_module_multiple' => ['name' => 'Multiple', 'type' => 'boolean'],
        'sm_module_activation_model' => ['name' => 'Activation Model', 'domain' => 'code', 'null' => true],
        'sm_module_reset_model' => ['name' => 'Reset Model', 'domain' => 'code', 'null' => true],
        'sm_module_custom_activation' => ['name' => 'Custom Activation', 'type' => 'boolean'],
        'sm_module_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_modules_pk' => ['type' => 'pk', 'columns' => ['sm_module_code']]
    ];
    public $indexes = [];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = false;
    public $options_map = [
        'sm_module_name' => 'name',
        'sm_module_icon' => 'icon_class',
        'sm_module_inactive' => 'inactive'
    ];
    public $options_active = [
        'sm_module_inactive' => 0
    ];
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

    /**
     * Options short
     *
     * @param array $options
     * @return array
     */
    public function optionsAbbreviation($options = [])
    {
        $options['options_map'] = [
            'sm_module_abbreviation' => 'name',
            'sm_module_name' => 'title'
        ];
        return parent::options($options);
    }
}
