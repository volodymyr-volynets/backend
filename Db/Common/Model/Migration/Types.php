<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common\Model\Migration;

use Object\Data;

class Types extends Data
{
    public $module_code = 'SM';
    public $title = 'S/M Migration Types';
    public $column_key = 'sm_migrtype_code';
    public $column_prefix = 'sm_migrtype_';
    public $columns = [
        'sm_migrtype_code' => ['name' => 'Migration Type', 'domain' => 'type_code'],
        'sm_migrtype_name' => ['name' => 'Name', 'type' => 'text']
    ];
    public $data = [
        'schema' => ['sm_migrtype_name' => 'Direct Schema Changes'],
        'migration' => ['sm_migrtype_name' => 'Changes Through Migrations'],
    ];
}
