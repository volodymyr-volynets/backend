<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Data\Policy;

use Object\Import;

class ModulePolicies extends Import
{
    public $data = [
        'policy_types' => [
            'options' => [
                'pk' => ['sm_poltype_code'],
                'model' => '\Numbers\Backend\System\Policies\Model\Types',
                'method' => 'save'
            ],
            'data' => [
                [
                    'sm_poltype_code' => 'SM::PAGE_ACCESS_GROUP',
                    'sm_poltype_name' => 'S/M Page Access (Group)',
                    'sm_poltype_description' => null,
                    'sm_poltype_external_json' => null,
                    'sm_poltype_internal_json' => null,
                    'sm_poltype_for_main' => 1,
                    'sm_poltype_model' => '\Numbers\Backend\System\Modules\Class2\PolicyPageAccess',
                    'sm_poltype_inactive' => 0,
                ],
                [
                    'sm_poltype_code' => 'SM::DATA_ROW_ACCESS_GROUP',
                    'sm_poltype_name' => 'S/M Data Row Access (Group)',
                    'sm_poltype_description' => null,
                    'sm_poltype_external_json' => null,
                    'sm_poltype_internal_json' => null,
                    'sm_poltype_for_main' => 1,
                    'sm_poltype_inactive' => 0,
                ],
                [
                    'sm_poltype_code' => 'SM::DATA_CELL_ACCESS_GROUP',
                    'sm_poltype_name' => 'S/M Data Cell Access (Group)',
                    'sm_poltype_description' => null,
                    'sm_poltype_external_json' => null,
                    'sm_poltype_internal_json' => null,
                    'sm_poltype_for_main' => 1,
                    'sm_poltype_inactive' => 0,
                ],
                [
                    'sm_poltype_code' => 'SM::NOTIFICATION_ACCESS_GROUP',
                    'sm_poltype_name' => 'S/M Notification Access (Group)',
                    'sm_poltype_description' => null,
                    'sm_poltype_external_json' => null,
                    'sm_poltype_internal_json' => null,
                    'sm_poltype_for_main' => 1,
                    'sm_poltype_inactive' => 0,
                ],
                [
                    'sm_poltype_code' => 'SM::MODULES',
                    'sm_poltype_name' => 'S/M Modules',
                    'sm_poltype_description' => null,
                    'sm_poltype_external_json' => null,
                    'sm_poltype_internal_json' => null,
                    'sm_poltype_inactive' => 0,
                ],
                [
                    'sm_poltype_code' => 'SM::FEATURES',
                    'sm_poltype_name' => 'S/M Features',
                    'sm_poltype_parent_sm_poltype_code' => 'SM::MODULES',
                    'sm_poltype_description' => null,
                    'sm_poltype_external_json' => null,
                    'sm_poltype_internal_json' => null,
                    'sm_poltype_inactive' => 0,
                ],
                [
                    'sm_poltype_code' => 'SM::RESOURCES',
                    'sm_poltype_name' => 'S/M Resources',
                    'sm_poltype_description' => null,
                    'sm_poltype_external_json' => null,
                    'sm_poltype_internal_json' => null,
                    'sm_poltype_inactive' => 0,
                ],
                [
                    'sm_poltype_code' => 'SM::CONTROLLERS',
                    'sm_poltype_name' => 'S/M Controllers',
                    'sm_poltype_parent_sm_poltype_code' => 'SM::RESOURCES',
                    'sm_poltype_description' => null,
                    'sm_poltype_external_json' => null,
                    'sm_poltype_internal_json' => null,
                    'sm_poltype_inactive' => 0,
                ],
                [
                    'sm_poltype_code' => 'SM::MENUS',
                    'sm_poltype_name' => 'S/M Menus',
                    'sm_poltype_parent_sm_poltype_code' => 'SM::RESOURCES',
                    'sm_poltype_description' => null,
                    'sm_poltype_external_json' => null,
                    'sm_poltype_internal_json' => null,
                    'sm_poltype_inactive' => 0,
                ],
            ],
        ],
    ];
}
