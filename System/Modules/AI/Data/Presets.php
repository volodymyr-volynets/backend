<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\AI\Data;

use Object\Import;

class Presets extends Import
{
    public $data = [
        'presets' => [
            'options' => [
                'pk' => ['um_imppreset_id'],
                'model' => '\Numbers\Users\Users\Model\Import\Presets',
                'method' => 'save',
                'submodule_exists' => ['Numbers.Users.Users']
            ],
            'data' => [
                [
                    'um_imppreset_id' => '::id::SM_MenuResourcesETL',
                    'um_imppreset_code' => 'SM_MenuResourcesETL',
                    'um_imppreset_name' => 'S/M Menu Resources ETL',
                    'um_imppreset_module_code' => 'SM',
                    'um_imppreset_sm_model_id' => '::id::\Numbers\AI\SDK\Model\Embeddings',
                    'um_imppreset_sm_model_code' => '\Numbers\AI\SDK\Model\Embeddings',
                    'um_imppreset_activation_method' => '\Numbers\Backend\System\Modules\AI\ETL\MenuResourcesETL',
                    'um_imppreset_um_imppretype_code' => 'ETL',
                    'um_imppreset_inactive' => 0,
                ],
            ]
        ],
    ];
}
