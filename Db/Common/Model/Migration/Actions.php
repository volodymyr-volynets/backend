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

class Actions extends Data
{
    public $module_code = 'SM';
    public $title = 'S/M Migration Actions';
    public $column_key = 'sm_migraction_code';
    public $column_prefix = 'sm_migraction_';
    public $columns = [
        'sm_migraction_code' => ['name' => 'Migration Action', 'domain' => 'type_code'],
        'sm_migraction_name' => ['name' => 'Name', 'type' => 'text']
    ];
    public $data = [
        'up' => ['sm_migraction_name' => 'Up'],
        'down' => ['sm_migraction_name' => 'Down'],
    ];
}
