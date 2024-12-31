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

class Subresources extends Collection
{
    public $data = [
        'model' => '\Numbers\Backend\System\Modules\Model\Resource\Subresources',
        'pk' => ['sm_rsrsubres_id'],
        'details' => [
            '\Numbers\Backend\System\Modules\Model\Resource\Subresource\Map' => [
                'pk' => ['sm_rsrsubmap_rsrsubres_id', 'sm_rsrsubmap_action_id'],
                'type' => '1M',
                'map' => ['sm_rsrsubres_id' => 'sm_rsrsubmap_rsrsubres_id'],
            ],
            '\Numbers\Backend\System\Modules\Model\Resource\Subresource\Features' => [
                'pk' => ['sm_rsrsubftr_rsrsubres_id', 'sm_rsrsubftr_feature_code'],
                'type' => '1M',
                'map' => ['sm_rsrsubres_id' => 'sm_rsrsubftr_rsrsubres_id'],
            ]
        ]
    ];
}
