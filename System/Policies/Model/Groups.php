<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Policies\Model;

use Object\Table;
use Object\Table\Options;

class Groups extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Policy Groups';
    public $name = 'sm_policy_groups';
    public $pk = ['sm_polgroup_tenant_id', 'sm_polgroup_id'];
    public $tenant = true;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_polgroup_';
    public $columns = [
        'sm_polgroup_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_polgroup_id' => ['name' => 'Group #', 'domain' => 'group_id_sequence'],
        'sm_polgroup_code' => ['name' => 'Code', 'domain' => 'group_code'],
        'sm_polgroup_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_polgroup_description' => ['name' => 'Description', 'domain' => 'description', 'null' => true],
        'sm_polgroup_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'],
        'sm_polgroup_global' => ['name' => 'Global', 'type' => 'boolean'],
        'sm_polgroup_weight' => ['name' => 'Weight', 'domain' => 'weight', 'null' => true],
        'sm_polgroup_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_policy_groups_pk' => ['type' => 'pk', 'columns' => ['sm_polgroup_tenant_id', 'sm_polgroup_id']],
        'sm_polgroup_code_un' => ['type' => 'unique', 'columns' => ['sm_polgroup_tenant_id', 'sm_polgroup_code']],
        'sm_polgroup_module_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_polgroup_module_code'],
            'foreign_model' => '\Numbers\Backend\System\Modules\Model\Modules',
            'foreign_columns' => ['sm_module_code']
        ],
    ];
    public $indexes = [];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = true;
    public $options_map = [
        'sm_polgroup_name' => 'name',
        'sm_polgroup_code' => 'name',
        'sm_polgroup_tenant_id' => 'tenant_id',
        'sm_polgroup_inactive' => 'inactive',
    ];
    public $options_active = [
        'sm_polgroup_inactive' => 0,
    ];
    public $engine = [
        'MySQLi' => 'InnoDB'
    ];

    public $cache = false;
    public $cache_tags = [];
    public $cache_memory = false;

    public $who = [
        'inserted' => true,
        'updated' => true,
    ];

    public $data_asset = [
        'classification' => 'proprietary',
        'protection' => 1,
        'scope' => 'global'
    ];

    /**
     * @see $this->options()
     */
    public function optionsJson($options = [])
    {
        $options['where'][$this->tenant_column] = [0, \Tenant::id()];
        $data = $this->get($options);


        print_r2($data);
        exit;

        $result = [];
        foreach ($data as $k => $v) {
            foreach ($v as $k2 => $v2) {
                foreach ($v2 as $k3 => $v3) {
                    if ($k3 == -1) {
                        $key = Options::optionJsonFormatKey(['action_id' => $k3, 'method_code' => $k2]);
                        $result[$key] = [
                            'name' => $v3['sm_action_name'],
                            'icon_class' => \HTML::icon(['type' => $v3['sm_action_icon'], 'class_only' => true]),
                            //'__selected_name' => i18n(null, $v3['sm_method_name']) . ': ' . i18n(null, $v3['sm_action_name']),
                            'parent' => null,
                            'disabled' => $v3['disabled'],
                            'inactive' => $v3['inactive']
                        ];
                    } else {
                        $parent = Options::optionJsonFormatKey(['method_code' => $k2]);
                        // add method
                        if (!isset($result[$parent])) {
                            $result[$parent] = ['name' => $v3['sm_method_name'], 'parent' => null, 'disabled' => true];
                        }
                        // add item
                        $key = Options::optionJsonFormatKey(['action_id' => $k3, 'method_code' => $k2]);
                        // if we have a parent
                        if (!empty($v3['sm_action_parent_action_id'])) {
                            $parent = Options::optionJsonFormatKey(['action_id' => $v3['sm_action_parent_action_id'], 'method_code' => $k2]);
                        }
                        $result[$key] = [
                            'name' => $v3['sm_action_name'],
                            'icon_class' => \HTML::icon(['type' => $v3['sm_action_icon'], 'class_only' => true]),
                            '__selected_name' => i18n(null, $v3['sm_method_name']) . ': ' . i18n(null, $v3['sm_action_name']),
                            'parent' => $parent,
                            'disabled' => $v3['disabled'],
                            'inactive' => $v3['inactive']
                        ];
                    }
                }
            }
        }
        if (!empty($result)) {
            $converted = Tree::convertByParent($result, 'parent');
            $result = [];
            Tree::convertTreeToOptionsMulti($converted, 0, ['name_field' => 'name'], $result);
        }
        return $result;
    }
}
