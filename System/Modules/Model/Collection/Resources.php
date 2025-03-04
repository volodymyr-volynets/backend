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

class Resources extends Collection
{
    public $data = [
        'model' => '\Numbers\Backend\System\Modules\Model\Resources',
        'pk' => ['sm_resource_id'],
        'details' => [
            '\Numbers\Backend\System\Modules\Model\Resource\Map' => [
                'pk' => ['sm_rsrcmp_resource_id', 'sm_rsrcmp_method_code', 'sm_rsrcmp_action_id'],
                'type' => '1M',
                'map' => ['sm_resource_id' => 'sm_rsrcmp_resource_id'],
            ],
            '\Numbers\Backend\System\Modules\Model\Resource\Features' => [
                'pk' => ['sm_rsrcftr_resource_id', 'sm_rsrcftr_feature_code'],
                'type' => '1M',
                'map' => ['sm_resource_id' => 'sm_rsrcftr_resource_id'],
            ],
            '\Numbers\Backend\System\Modules\Model\Resource\Tenants' => [
                'pk' => ['sm_rsrctenant_resource_id', 'sm_rsrctenant_tenant_code'],
                'type' => '1M',
                'map' => ['sm_resource_id' => 'sm_rsrctenant_resource_id'],
            ],
            '\Numbers\Backend\System\Modules\Model\Resource\APIMethods' => [
                'pk' => ['sm_rsrcapimeth_resource_id', 'sm_rsrcapimeth_method_code'],
                'type' => '1M',
                'map' => ['sm_resource_id' => 'sm_rsrcapimeth_resource_id'],
            ]
        ]
    ];
}
