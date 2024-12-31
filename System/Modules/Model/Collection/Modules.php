<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model\Collection;

use Object\Collection;

class Modules extends Collection
{
    public $data = [
        'model' => '\Numbers\Backend\System\Modules\Model\Modules',
        'pk' => ['sm_module_code'],
        'details' => [
            '\Numbers\Backend\System\Modules\Model\Module\Dependencies' => [
                'pk' => ['sm_mdldep_parent_module_code', 'sm_mdldep_child_module_code', 'sm_mdldep_child_feature_code'],
                'type' => '1M',
                'map' => ['sm_module_code' => 'sm_mdldep_parent_module_code'],
                'where' => [
                    'sm_mdldep_parent_feature_code' => null
                ]
            ]
        ]
    ];
}
