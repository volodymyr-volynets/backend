<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\DataSource\Menu;

use Helper\Tree;
use Object\DataSource;
use Object\Table\Options;

class Searches extends DataSource
{
    public $db_link;
    public $db_link_flag;
    public $pk = ['code'];
    public $columns;
    public $orderby;
    public $limit;
    public $single_row;
    public $single_value;
    public $options_map = [
        'name' => 'name',
        'icon' => 'icon_class',
        'inactive' => 'inactive',
    ];
    public $options_active = [
        'inactive' => 0
    ];
    public $column_prefix;

    public $cache = true;
    public $cache_tags = [];
    public $cache_memory = false;

    public $primary_model = '\Numbers\Backend\System\Modules\Model\Menu\Searches';
    public $parameters = [
        'sm_menusearch_code' => ['name' => 'Search Code', 'domain' => 'group_code'],
    ];
    public $skip_tenant = true;

    public function query($parameters, $options = [])
    {
        $this->query->columns([
            'tenant_id' => 'a.sm_menusearch_tenant_id',
            'code' => 'a.sm_menusearch_code',
            'name' => 'a.sm_menusearch_name',
            'module_code' => 'a.sm_menusearch_module_code',
            'model' => 'a.sm_menusearch_model',
            'sm_model_code' => 'a.sm_menusearch_sm_model_code',
            'sm_resource_code' => 'a.sm_menusearch_sm_resource_code',
            'icon' => 'a.sm_menusearch_icon',
            'inactive' => 'a.sm_menusearch_inactive',
        ]);
        $this->query->where('AND', ['a.sm_menusearch_tenant_id', 'IN', [1, \Tenant::id()]]);
        if (!empty($parameters['sm_menusearch_code'])) {
            $this->query->where('AND', condition: ['a.sm_menusearch_code', '=', $parameters['sm_menusearch_code']]);
        }
    }

    public function processNotCached($data, $options = [])
    {
        foreach ($data as $k => $v) {
            if (!empty($v['sm_resource_code'])) {
                if (\Can::controllerActionPermitted($v['sm_resource_code'], 'Index', 'List_View', null)) {
                    goto logic_label;
                }
                if (\Can::controllerActionPermitted($v['sm_resource_code'], 'Edit', 'Record_View', null)) {
                    goto logic_label;
                }
                unset($data[$k]);
            }
            logic_label:
                        // other logic
        }
        return $data;
    }
}
