<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Policies\DataSource;

use Helper\Tree;
use Object\DataSource;
use Object\Table\Options;

class Policies extends DataSource
{
    public $db_link;
    public $db_link_flag;
    public $pk = ['sm_policy_tenant_id', 'sm_policy_code'];
    public $columns;
    public $orderby;
    public $limit;
    public $single_row;
    public $single_value;
    public $options_map = [
        'sm_policy_name' => 'name',
        //'sm_policy_code' => 'name',
        'sm_policy_tenant_id' => 'tenant_id',
        'sm_policy_inactive' => 'inactive',
    ];
    public $column_prefix;

    public $cache = true;
    public $cache_tags = [];
    public $cache_memory = false;

    public $primary_model = '\Numbers\Backend\System\Policies\Model\Policies';
    public $parameters = [];
    public $skip_tenant = true;

    public function query($parameters, $options = [])
    {
        $this->query->columns('*');
        $this->query->where('AND', ['a.sm_policy_tenant_id', 'IN', [1, \Tenant::id()]]);
    }

    /**
     * @see $this->options()
     */
    public function optionsJson($options = [])
    {
        $data = $this->get($options);
        $result = [];
        foreach ($data as $k => $v) {
            foreach ($v as $k2 => $v2) {
                $parent = Options::optionJsonFormatKey(['sm_policy_tenant_id' => $k]);
                // item key
                $key = Options::optionJsonFormatKey(['sm_policy_code' => $k2, 'sm_policy_tenant_id' => $k]);
                // filter
                if (!Options::processOptionsExistingValuesAndSkipValues($key, $options['existing_values'] ?? null, $options['skip_values'] ?? null)) {
                    continue;
                }
                // add parent
                if (!isset($result[$parent])) {
                    $result[$parent] = ['name' => ($k == 1 ? 'Global Policies' : 'Local Policies'), 'parent' => null, 'disabled' => true];
                }
                // add item
                $result[$key] = ['name' => $v2['sm_policy_name'], '__selected_name' => ($k == 1 ? 'Global Policies' : 'Local Policies') . ' - ' . $v2['sm_policy_name'], 'parent' => $parent];
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
