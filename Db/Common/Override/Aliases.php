<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common\Override;

class Aliases
{
    public $data = [
        'model_id' => [
            'no_data_alias_name' => 'Model #',
            'no_data_alias_model' => '\Numbers\Backend\Db\Common\Model\Models',
            'no_data_alias_column' => 'sm_model_code'
        ],
    ];
}
