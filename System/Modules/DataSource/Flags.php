<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\DataSource;

use Object\DataSource;

class Flags extends DataSource
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

    public $primary_model = '\Numbers\Backend\System\Modules\Model\System\Flags';
    public $parameters = [];

    public function query($parameters, $options = [])
    {
        // columns
        $this->query->columns([
            'id' => 'sm_sysflag_id',
            'parent_sysflag_id' => 'sm_sysflag_parent_sysflag_id',
            'code' => 'sm_sysflag_code',
            'name' => 'sm_sysflag_name',
            'module_code' => 'sm_sysflag_module_code',
            'inactive' => 'sm_sysflag_inactive'
        ]);
        // where
        $this->query->where('AND', ['a.sm_sysflag_inactive', '=', 0]);
    }
}
