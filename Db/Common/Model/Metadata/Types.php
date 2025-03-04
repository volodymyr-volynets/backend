<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common\Metadata;

use Object\Data;

class Types extends Data
{
    public $column_key = 'sm_mdatatype_code';
    public $column_prefix = 'sm_mdatatype_';
    public $columns = [
        'sm_mdatatype_code' => ['name' => 'Metadata Type', 'domain' => 'type_code'],
        'sm_mdatatype_name' => ['name' => 'Name', 'type' => 'text']
    ];
    public $data = [
        'check' => ['sm_mdatatype_name' => 'Check Constraint'],
        'function' => ['sm_mdatatype_name' => 'Function'],
        'trigger' => ['sm_mdatatype_name' => 'Trigger'],
        'view' => ['sm_mdatatype_name' => 'View'],
    ];
}
