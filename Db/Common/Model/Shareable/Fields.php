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
use Helper\Tree;

class Fields extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Shareable Fields';
    public $schema;
    public $name = 'sm_shareable_fields';
    public $pk = ['sm_sharefield_code'];
    public $orderby = [
        'sm_sharefield_order' => SORT_ASC,
    ];
    public $limit;
    public $column_prefix = 'sm_sharefield_';
    public $columns = [
        'sm_sharefield_code' => ['name' => 'Field Code', 'domain' => 'code'],
        'sm_sharefield_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_sharefield_type_code' => ['name' => 'Type Code', 'domain' => 'type_code', 'options_model' => '\Numbers\Backend\Db\Common\Model\Shareable\Types'],
        'sm_sharefield_types' => ['name' => 'Types', 'domain' => 'types', 'null' => true],
        'sm_sharefield_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'],
        'sm_sharefield_sm_sharegrp_code' => ['name' => 'Shareable Group', 'domain' => 'code', 'null' => true],
        'sm_sharefield_parent_sm_sharefield_code' => ['name' => 'Parent Field Code', 'domain' => 'code', 'null' => true],
        'sm_sharefield_originated_model_code' => ['name' => 'Options Model', 'domain' => 'model', 'null' => true],
        'sm_sharefield_options_model_code' => ['name' => 'Options Model', 'domain' => 'model', 'null' => true],
        'sm_sharefield_detail_model_code' => ['name' => 'Detail Model', 'domain' => 'model', 'null' => true],
        'sm_sharefield_global_get_model_code' => ['name' => 'Global Get Model', 'domain' => 'model', 'null' => true], // for global fields
        'sm_sharefield_order' => ['name' => 'Order', 'domain' => 'order'],
        'sm_sharefield_placeholder' => ['name' => 'Placeholder', 'domain' => 'placeholder', 'null' => true],
        'sm_sharefield_sql_name' => ['name' => 'SQL Name', 'domain' => 'field_code', 'null' => true],
        // other
        'sm_sharefield_disabled' => ['name' => 'Disabled', 'type' => 'boolean'],
        'sm_sharefield_global' => ['name' => 'Global', 'type' => 'boolean'],
        'sm_sharefield_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_shareable_fields_pk' => ['type' => 'pk', 'columns' => ['sm_sharefield_code']],
        'sm_sharefield_parent_sm_sharefield_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_sharefield_parent_sm_sharefield_code'],
            'foreign_model' => '\Numbers\Backend\Db\Common\Model\Shareable\Fields',
            'foreign_columns' => ['sm_sharefield_code'],
        ],
        'sm_sharefield_sm_sharegrp_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_sharefield_sm_sharegrp_code'],
            'foreign_model' => '\Numbers\Backend\Db\Common\Model\Shareable\Groups',
            'foreign_columns' => ['sm_sharegrp_code'],
        ]
    ];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = false;
    public $options_map = [
        'sm_sharefield_name' => 'name',
        'sm_sharefield_parent_sm_sharefield_code' => 'parent',
        'sm_sharefield_options_model_code' => 'options_model',
        'sm_sharefield_disabled' => 'disabled',
        'sm_sharefield_inactive' => 'inactive'
    ];
    public $options_active = [
        'sm_sharefield_inactive' => 0
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

    /**
    * @see $this->options()
    */
    public function optionsGrouped($options = [])
    {
        $result = $this->options($options);
        if (!empty($result)) {
            $converted = Tree::convertByParent($result, 'parent');
            $result = [];
            Tree::convertTreeToOptionsMulti($converted, 0, ['name_field' => 'name'], $result);
        }
        return $result;
    }
}
