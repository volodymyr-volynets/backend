<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model\Resource;

use Object\Data;

class Types extends Data
{
    public $module_code = 'SM';
    public $title = 'S/M Resource Types';
    public $column_key = 'sm_rsrctype_id';
    public $column_prefix = 'sm_rsrctype_';
    public $orderby;
    public $columns = [
        'sm_rsrctype_id' => ['name' => 'Type #', 'domain' => 'type_id'],
        'sm_rsrctype_name' => ['name' => 'Name', 'type' => 'text']
    ];
    public $data = [
        // controllers
        100 => ['sm_rsrctype_name' => 'Controllers'],
        // APIs
        150 => ['sm_rsrctype_name' => 'APIs'],
        // menu
        200 => ['sm_rsrctype_name' => 'Main Menu - Left Side'],
        210 => ['sm_rsrctype_name' => 'Main Menu - Right Side'],
        220 => ['sm_rsrctype_name' => 'Top Links'],
        230 => ['sm_rsrctype_name' => 'Footer Links'],
        299 => ['sm_rsrctype_name' => 'Submenu Icons'],
    ];
}
