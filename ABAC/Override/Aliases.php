<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\ABAC\Override;

class Aliases
{
    public $data = [
        'abacattr_id' => [
            'no_data_alias_name' => 'ABAC Attribute #',
            'no_data_alias_model' => '\Numbers\Backend\ABAC\Model\Attributes',
            'no_data_alias_column' => 'sm_abacattr_code'
        ],
    ];
}
