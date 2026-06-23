<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common\DataSource\Shareable;

use Object\DataSource;
use Helper\Tree;

class Fields extends DataSource
{
    public $db_link;
    public $db_link_flag;
    public $pk = ['sm_sharefield_code'];
    public $columns;
    public $orderby;
    public $limit;
    public $single_row;
    public $single_value;
    public $options_map = [
        'sm_sharefield_name' => 'name',
        'sm_sharefield_parent_sm_sharefield_code' => 'parent',
        'sm_sharefield_options_model_code' => 'options_model',
        'sm_sharefield_parameter' => 'parameter',
        'sm_sharefield_disabled' => 'disabled',
        'sm_sharefield_inactive' => 'inactive'
    ];
    public $options_active = [
        'sm_sharefield_inactive' => 0
    ];
    public $column_prefix = 'um_team_';

    public $cache = true;
    public $cache_tags = [];
    public $cache_memory = true;

    public $primary_model = '\Numbers\Backend\Db\Common\Model\Shareable\Fields';
    public $parameters = [
        'sm_sharefield_sm_sharegrp_code' => ['name' => 'Shareable Group', 'domain' => 'code', 'null' => true],
        'include_global_fields' => ['name' => 'Include Global Fields', 'type' => 'boolean']
    ];

    public function query($parameters, $options = [])
    {
        $this->query->where('AND', function (& $query) use ($parameters) {
            if (!empty($parameters['include_global_fields'])) {
                $query->where('OR', ['a.sm_sharefield_global', '=', 1]);
            }
            if (!empty($parameters['sm_sharefield_sm_sharegrp_code'])) {
                $query->where('OR', ['a.sm_sharefield_sm_sharegrp_code', '=', $parameters['sm_sharefield_sm_sharegrp_code']]);
            }
        });
    }

    /**
    * @see $this->options()
    */
    public function optionsGrouped($options = [])
    {
        $result = $this->options($options);
        if (!empty($result)) {
            // append to name parameter name
            foreach ($result as $k => &$v) {
                $v['name'] = $v['name'] . \Format::$symbol_semicolon . ' ' . '{{' . $k . '}}';
                $v['parameter'] = '{{' . $k . '}}';
            }
            // convert to tree
            $converted = Tree::convertByParent($result, 'parent');
            $result = [];
            Tree::convertTreeToOptionsMulti($converted, 0, ['name_field' => 'name'], $result);
        }
        return $result;
    }
}
