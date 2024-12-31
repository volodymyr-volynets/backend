<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Policies\Model\Collection;

use Object\Collection;

class Groups extends Collection
{
    public $data = [
        'name' => 'SM System Policy Groups',
        'pk' => ['sm_polgroup_tenant_id', 'sm_polgroup_id'],
        'model' => '\Numbers\Backend\System\Policies\Model\Groups',
        'details' => [
            '\Numbers\Backend\System\Policies\Model\Group\Groups' => [
                'name' => 'SM System Policy Group Groups',
                'pk' => ['sm_polgrogroup_tenant_id', 'sm_polgrogroup_sm_polgroup_id', 'sm_polgrogroup_child_sm_polgroup_tenant_id', 'sm_polgrogroup_child_sm_polgroup_id'],
                'type' => '1M',
                'map' => ['sm_polgroup_tenant_id' => 'sm_polgrogroup_tenant_id', 'sm_polgroup_id' => 'sm_polgrogroup_sm_polgroup_id']
            ],
            '\Numbers\Backend\System\Policies\Model\Group\Policies' => [
                'name' => 'SM System Policy Group Policies',
                'pk' => ['sm_polgropolicy_tenant_id', 'sm_polgropolicy_sm_polgroup_id', 'sm_polgropolicy_sm_policy_tenant_id', 'sm_polgropolicy_sm_policy_code'],
                'type' => '1M',
                'map' => ['sm_polgroup_tenant_id' => 'sm_polgropolicy_tenant_id', 'sm_polgroup_id' => 'sm_polgropolicy_sm_polgroup_id']
            ]
        ],
    ];
}
