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

use Object\Data;

class Types extends Data
{
    public $module_code = 'SM';
    public $title = 'S/M Shareable Types';
    public $column_key = 'sm_sharetype_code';
    public $column_prefix = 'sm_sharetype_';
    public $columns = [
        'sm_sharetype_code' => ['name' => 'Field Type', 'domain' => 'type_code'],
        'sm_sharetype_name' => ['name' => 'Name', 'type' => 'text'],
        'sm_sharetype_actual_type' => ['name' => 'Actual', 'type' => 'boolean']
    ];
    public $data = [
        'PARENT' => ['sm_sharetype_name' => 'Parent', 'sm_sharetype_actual_type' => 0],
        'GROUP' => ['sm_sharetype_name' => 'Group', 'sm_sharetype_actual_type' => 0],
        'FIELD' => ['sm_sharetype_name' => 'Field', 'sm_sharetype_actual_type' => 0],
        'OPTIONS' => ['sm_sharetype_name' => 'Options (Multiple)', 'sm_sharetype_actual_type' => 1],
        'OPTION' => ['sm_sharetype_name' => 'Options (Single)', 'sm_sharetype_actual_type' => 1],
        'RANGE' => ['sm_sharetype_name' => 'Range', 'sm_sharetype_actual_type' => 1],
        'CODE' => ['sm_sharetype_name' => 'Code', 'sm_sharetype_actual_type' => 1],
    ];

    public function optionsFiltered($options = [])
    {
        $filtered = [];
        if (isset($options['where']['au_segdetail_field'])) {
            $filtered = [];
            $field = Fields::getStatic([
                'where' => [
                    'sm_sharefield_module_code' => ['AU', 'SM'],
                    'sm_sharefield_code' => $options['where']['au_segdetail_field'] . '',
                ],
                'pk' => null,
                'single_row' => true,
            ]);
            $filtered = explode(',', $field['sm_sharefield_types']);
            unset($options['where']['au_segdetail_field']);
        }
        $result = $this->options();
        if ($filtered) {
            foreach ($result as $k => $v) {
                if (!in_array($k, $filtered)) {
                    unset($result[$k]);
                }
            }
            return $result;
        }
        return [];
    }
}
