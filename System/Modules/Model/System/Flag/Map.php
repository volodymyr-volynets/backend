<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model\System\Flag;

use Object\Table;

class Map extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Resource Map';
    public $name = 'sm_system_flag_map';
    public $pk = ['sm_sysflgmap_sysflag_id', 'sm_sysflgmap_action_id'];
    public $tenant = false;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_sysflgmap_';
    public $columns = [
        'sm_sysflgmap_sysflag_id' => ['name' => 'Subresource #', 'domain' => 'resource_id'],
        'sm_sysflgmap_action_id' => ['name' => 'Action #', 'domain' => 'action_id'],
        'sm_sysflgmap_disabled' => ['name' => 'Disabled', 'type' => 'boolean'],
        'sm_sysflgmap_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
    ];
    public $constraints = [
        'sm_system_flag_map_pk' => ['type' => 'pk', 'columns' => ['sm_sysflgmap_sysflag_id', 'sm_sysflgmap_action_id']],
        'sm_sysflgmap_sysflag_id_fk' => [
            'type' => 'fk',
            'columns' => ['sm_sysflgmap_sysflag_id'],
            'foreign_model' => '\Numbers\Backend\System\Modules\Model\System\Flags',
            'foreign_columns' => ['sm_sysflag_id']
        ],
        'sm_sysflgmap_action_id_fk' => [
            'type' => 'fk',
            'columns' => ['sm_sysflgmap_action_id'],
            'foreign_model' => '\Numbers\Backend\System\Modules\Model\Resource\Actions',
            'foreign_columns' => ['sm_action_id']
        ],
    ];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = false;
    public $options_map = [];
    public $options_active = [];
    public $engine = [
        'MySQLi' => 'InnoDB'
    ];

    public $cache = false;
    public $cache_tags = [];
    public $cache_memory = false;

    public $data_asset = [
        'classification' => 'public',
        'protection' => 0,
        'scope' => 'global'
    ];
}
