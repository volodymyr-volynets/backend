<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model\Menu;

use Object\Table;

class Searches extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Menu Searches';
    public $schema;
    public $name = 'sm_menu_searches';
    public $pk = ['sm_menusearch_tenant_id', 'sm_menusearch_code'];
    public $tenant = true;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_menusearch_';
    public $columns = [
        'sm_menusearch_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_menusearch_code' => ['name' => 'Search Code', 'domain' => 'group_code'],
        'sm_menusearch_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_menusearch_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'],
        'sm_menusearch_model' => ['name' => 'Model', 'domain' => 'model'],
        'sm_menusearch_sm_model_code' => ['name' => 'Db Model', 'domain' => 'code', 'null' => true],
        'sm_menusearch_sm_resource_code' => ['name' => 'Resource Code', 'domain' => 'code', 'null' => true],
        'sm_menusearch_icon' => ['name' => 'Icon', 'domain' => 'icon', 'null' => true],
        'sm_menusearch_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_menu_searches_pk' => ['type' => 'pk', 'columns' => ['sm_menusearch_tenant_id', 'sm_menusearch_code']],
        'sm_menusearch_code_un' => ['type' => 'unique', 'columns' => ['sm_menusearch_code']],
    ];
    public $indexes = [];
    public $history = false;
    public $audit = [];
    public $optimistic_lock = false;
    public $options_map = [
        'sm_menusearch_name' => 'name',
        'sm_menusearch_code' => 'name',
        'sm_menusearch_inactive' => 'inactive',
    ];
    public $options_active = [
        'sm_menusearch_inactive' => 0
    ];
    public const selectOptionsActive = '\Numbers\Templates\Common\Model\Menu\Searches::optionsActive';
    public $options_skip_i18n = true;
    public $engine = [
        'MySQLi' => 'InnoDB'
    ];

    public $cache = true;
    public $cache_tags = [];
    public $cache_memory = true;

    public $data_asset = [
        'classification' => 'client_confidential',
        'protection' => 2,
        'scope' => 'enterprise'
    ];
}
