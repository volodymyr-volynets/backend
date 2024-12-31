<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Policies\Data;

class Import extends \Object\Import
{
    public $data = [
        'features' => [
            'options' => [
                'pk' => ['sm_feature_code'],
                'model' => '\Numbers\Backend\System\Modules\Model\Collection\Module\Features',
                'method' => 'save'
            ],
            'data' => [
                [
                    'sm_feature_module_code' => 'SM',
                    'sm_feature_code' => 'SM::SYSTEM_POLICIES',
                    'sm_feature_type' => 10,
                    'sm_feature_name' => 'S/M System Policies',
                    'sm_feature_icon' => 'fas fa-wrench',
                    'sm_feature_activation_model' => null,
                    'sm_feature_activated_by_default' => 1,
                    'sm_feature_inactive' => 0,
                ],
            ]
        ]
    ];
}
