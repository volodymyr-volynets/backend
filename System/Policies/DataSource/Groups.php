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

class Groups extends DataSource
{
    public $db_link;
    public $db_link_flag;
    public $pk = ['sm_polgroup_tenant_id', 'sm_polgroup_id'];
    public $columns;
    public $orderby;
    public $limit;
    public $single_row;
    public $single_value;
    public $options_map = [
        'sm_polgroup_name' => 'name',
        'sm_polgroup_code' => 'name',
        'sm_polgroup_tenant_id' => 'tenant_id',
        'sm_polgroup_inactive' => 'inactive',
    ];
    public $column_prefix;

    public $cache = true;
    public $cache_tags = [];
    public $cache_memory = false;

    public $primary_model = '\Numbers\Backend\System\Policies\Model\Groups';
    public $parameters = [];
    public $skip_tenant = true;

    public function query($parameters, $options = [])
    {
        $this->query->columns('*');
        $this->query->where('AND', ['a.sm_polgroup_tenant_id', 'IN', [1, \Tenant::id()]]);
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
                $parent = Options::optionJsonFormatKey(['sm_polgroup_tenant_id' => $k]);
                // item key
                $key = Options::optionJsonFormatKey(['sm_polgroup_id' => $k2, 'sm_polgroup_tenant_id' => $k]);
                // filter
                if (!Options::processOptionsExistingValuesAndSkipValues($key, $options['existing_values'] ?? null, $options['skip_values'] ?? null)) {
                    continue;
                }
                // add parent
                if (!isset($result[$parent])) {
                    $result[$parent] = ['name' => ($k == 1 ? 'Global Groups' : 'Local Groups'), 'parent' => null, 'disabled' => true];
                }
                // add item
                $result[$key] = ['name' => $v2['sm_polgroup_name'], '__selected_name' => ($k == 1 ? 'Global Groups' : 'Local Groups') . ' - ' . $v2['sm_polgroup_name'], 'parent' => $parent];
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
