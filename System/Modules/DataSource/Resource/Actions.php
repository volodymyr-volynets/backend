<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\DataSource\Resource;

use Object\DataSource;

class Actions extends DataSource
{
    public $db_link;
    public $db_link_flag;
    public $pk = ['code'];
    public $columns;
    public $orderby;
    public $limit;
    public $single_row;
    public $single_value;
    public $options_map = [];
    public $column_prefix;

    public $cache = true;
    public $cache_tags = [];
    public $cache_memory = false;

    public $primary_model = '\Numbers\Backend\System\Modules\Model\Resource\Actions';
    public $parameters = [];

    public function query($parameters, $options = [])
    {
        // columns
        $this->query->columns([
            'id' => 'a.sm_action_id',
            'code' => 'a.sm_action_code',
            'name' => 'a.sm_action_name',
            'parent_action_id' => 'a.sm_action_parent_action_id',
            'prohibitive' => 'a.sm_action_prohibitive',
            'inactive' => 'a.sm_action_inactive'
        ]);
        // where
        $this->query->where('AND', ['a.sm_action_inactive', '=', 0]);
    }
}
