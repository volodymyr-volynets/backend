<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model\Form;

use Helper\Tree;
use Numbers\Backend\System\Modules\Model\Form\Field\Types;
use Object\Table;

class Fields extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Form Fields';
    public $name = 'sm_form_fields';
    public $pk = ['sm_frmfield_form_code', 'sm_frmfield_code'];
    public $tenant = false;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_frmfield_';
    public $columns = [
        'sm_frmfield_form_code' => ['name' => 'Code', 'domain' => 'code'],
        'sm_frmfield_code' => ['name' => 'Code', 'domain' => 'code'],
        'sm_frmfield_type' => ['name' => 'Type', 'domain' => 'type_id', 'options_model' => '\Numbers\Backend\System\Modules\Model\Form\Field\Types'],
        'sm_frmfield_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_frmfield_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_form_fields_pk' => ['type' => 'pk', 'columns' => ['sm_frmfield_form_code', 'sm_frmfield_code']],
        'sm_frmfield_form_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_frmfield_form_code'],
            'foreign_model' => '\Numbers\Backend\System\Modules\Model\Forms',
            'foreign_columns' => ['sm_form_code']
        ]
    ];
    public $indexes = [];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = false;
    public $options_map = [
        'sm_frmfield_name' => 'name'
    ];
    public $options_active = [
        'sm_frmfield_inactive' => 0
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
     * @see $this->options()
     */
    public function optionsGrouped($options = [])
    {
        $options['options_map'] = [
            'sm_frmfield_name' => 'name',
            'sm_frmfield_type' => 'parent'
        ];
        $options['i18n'] = false;
        $result = $this->optionsActive($options);
        if (!empty($result)) {
            $parent_types = Types::optionsStatic(['i18n' => true]);
            $result2 = $result;
            foreach ($result as $k => $v) {
                $result2[$v['parent']] = ['name' => $parent_types[$v['parent']]['name'], 'disabled' => true, 'parent' => null];
                // find range fields
                if (substr($k, -1) == '1' && isset($result[substr($k, 0, strlen($k) - 1) . '2'])) {
                    $result2[$k]['name'] .= ' (From)';
                }
                if (substr($k, -1) == '2' && isset($result[substr($k, 0, strlen($k) - 1) . '1'])) {
                    $result2[$k]['name'] .= ' (To)';
                }
            }
            $converted = Tree::convertByParent($result2, 'parent');
            $result = [];
            Tree::convertTreeToOptionsMulti($converted, 0, ['name_field' => 'name'], $result);
        }
        return $result;
    }
}
