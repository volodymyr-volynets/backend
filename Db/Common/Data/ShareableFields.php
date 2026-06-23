<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common\Data;

use Object\Import;

class ShareableFields extends Import
{
    public $data = [
        'shareable_groups' => [
            'options' => [
                'pk' => ['sm_sharegrp_code'],
                'model' => '\Numbers\Backend\Db\Common\Model\Shareable\Groups',
                'method' => 'save'
            ],
            'data' => [
                [
                    'sm_sharegrp_code' => 'SM::GROUPS',
                    'sm_sharegrp_name' => 'S/M Groups',
                    'sm_sharegrp_module_code' => 'SM',
                    'sm_sharegrp_t9_forms' => 0,
                    'sm_sharegrp_disabled' => 1,
                    'sm_sharegrp_inactive' => 0,
                ],
                [
                    'sm_sharegrp_code' => 'SM::FIELDS',
                    'sm_sharegrp_name' => 'S/M Fields',
                    'sm_sharegrp_module_code' => 'SM',
                    'sm_sharegrp_t9_forms' => 0,
                    'sm_sharegrp_disabled' => 1,
                    'sm_sharegrp_inactive' => 0,
                ],
                [
                    'sm_sharegrp_code' => 'NF::GLOBALS',
                    'sm_sharegrp_name' => 'N/F Globals',
                    'sm_sharegrp_module_code' => 'NF',
                    'sm_sharegrp_t9_forms' => 0,
                    'sm_sharegrp_disabled' => 1,
                    'sm_sharegrp_inactive' => 0,
                ],
            ]
        ],
        'shareable_fields_parents' => [
            'options' => [
                'pk' => ['sm_sharefield_code'],
                'model' => '\Numbers\Backend\Db\Common\Model\Shareable\Fields',
                'method' => 'save'
            ],
            'data' => [
                [
                    'sm_sharefield_code' => '__sharable_group',
                    'sm_sharefield_name' => 'Group',
                    'sm_sharefield_type_code' => 'GROUP',
                    'sm_sharefield_types' => 'GROUP',
                    'sm_sharefield_module_code' => 'SM',
                    'sm_sharefield_sm_sharegrp_code' => 'SM::GROUPS',
                    'sm_sharefield_parent_sm_sharefield_code' => null,
                    'sm_sharefield_originated_model_code' => null,
                    'sm_sharefield_options_model_code' => null,
                    'sm_sharefield_order' => -32_000,
                    'sm_sharefield_placeholder' => '',
                    'sm_sharefield_disabled' => 1,
                    'sm_sharefield_inactive' => 0
                ],
                [
                    'sm_sharefield_code' => '__sharable_field',
                    'sm_sharefield_name' => 'Field',
                    'sm_sharefield_type_code' => 'FIELD',
                    'sm_sharefield_types' => 'FIELD',
                    'sm_sharefield_module_code' => 'SM',
                    'sm_sharefield_sm_sharegrp_code' => 'SM::FIELDS',
                    'sm_sharefield_parent_sm_sharefield_code' => null,
                    'sm_sharefield_originated_model_code' => null,
                    'sm_sharefield_options_model_code' => null,
                    'sm_sharefield_order' => -31_000,
                    'sm_sharefield_placeholder' => '',
                    'sm_sharefield_disabled' => 1,
                    'sm_sharefield_inactive' => 0
                ],
                [
                    'sm_sharefield_code' => '__global_field',
                    'sm_sharefield_name' => 'Field (Global)',
                    'sm_sharefield_type_code' => 'FIELD',
                    'sm_sharefield_types' => 'FIELD',
                    'sm_sharefield_module_code' => 'NF',
                    'sm_sharefield_sm_sharegrp_code' => 'NF::GLOBALS',
                    'sm_sharefield_parent_sm_sharefield_code' => null,
                    'sm_sharefield_originated_model_code' => null,
                    'sm_sharefield_options_model_code' => null,
                    'sm_sharefield_order' => 999_000,
                    'sm_sharefield_placeholder' => '',
                    'sm_sharefield_disabled' => 1,
                    'sm_sharefield_global' => 1,
                    'sm_sharefield_inactive' => 0
                ],
                [
                    'sm_sharefield_code' => '__nf_hostname',
                    'sm_sharefield_name' => 'N/F Hostname (Full)',
                    'sm_sharefield_sql_name' => '__nf_hostname',
                    'sm_sharefield_type_code' => 'CODE',
                    'sm_sharefield_types' => 'CODE',
                    'sm_sharefield_module_code' => 'NF',
                    'sm_sharefield_sm_sharegrp_code' => 'NF::GLOBALS',
                    'sm_sharefield_parent_sm_sharefield_code' => '__global_field',
                    'sm_sharefield_originated_model_code' => null,
                    'sm_sharefield_options_model_code' => null,
                    'sm_sharefield_detail_model_code' => null,
                    'sm_sharefield_global_get_model_code' => '\Request::host',
                    'sm_sharefield_order' => 999_100,
                    'sm_sharefield_placeholder' => 'Hostname (Current)',
                    'sm_sharefield_disabled' => 0,
                    'sm_sharefield_global' => 1,
                    'sm_sharefield_inactive' => 0,
                ],
                [
                    'sm_sharefield_code' => '__nf_host_short',
                    'sm_sharefield_name' => 'N/F Hostname (Short)',
                    'sm_sharefield_sql_name' => '__nf_host_short',
                    'sm_sharefield_type_code' => 'CODE',
                    'sm_sharefield_types' => 'CODE',
                    'sm_sharefield_module_code' => 'NF',
                    'sm_sharefield_sm_sharegrp_code' => 'NF::GLOBALS',
                    'sm_sharefield_parent_sm_sharefield_code' => '__global_field',
                    'sm_sharefield_originated_model_code' => null,
                    'sm_sharefield_options_model_code' => null,
                    'sm_sharefield_detail_model_code' => null,
                    'sm_sharefield_global_get_model_code' => '\Request::hostShort',
                    'sm_sharefield_order' => 999_200,
                    'sm_sharefield_placeholder' => 'Hostname (Current)',
                    'sm_sharefield_disabled' => 0,
                    'sm_sharefield_global' => 1,
                    'sm_sharefield_inactive' => 0,
                ],
                [
                    'sm_sharefield_code' => '__nf_message_plain',
                    'sm_sharefield_name' => 'N/F Message Plain',
                    'sm_sharefield_sql_name' => '__nf_message_plain',
                    'sm_sharefield_type_code' => 'CODE',
                    'sm_sharefield_types' => 'CODE',
                    'sm_sharefield_module_code' => 'NF',
                    'sm_sharefield_sm_sharegrp_code' => 'NF::GLOBALS',
                    'sm_sharefield_parent_sm_sharefield_code' => '__global_field',
                    'sm_sharefield_originated_model_code' => null,
                    'sm_sharefield_options_model_code' => null,
                    'sm_sharefield_detail_model_code' => null,
                    'sm_sharefield_global_get_model_code' => '',
                    'sm_sharefield_order' => 999_300,
                    'sm_sharefield_placeholder' => 'Message (Plain)',
                    'sm_sharefield_disabled' => 0,
                    'sm_sharefield_global' => 1,
                    'sm_sharefield_inactive' => 0,
                ],
            ]
        ],
    ];
}
