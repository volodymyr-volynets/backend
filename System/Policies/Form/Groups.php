<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Policies\Form;

use Object\Form\Wrapper\Base;

class Groups extends Base
{
    public $form_link = 'sm_system_policy_groups';
    public $module_code = 'SM';
    public $title = 'S/M System Policy Groups Form';
    public $options = [
        'segment' => self::SEGMENT_FORM,
        'actions' => [
            'refresh' => true,
            'back' => true,
            'new' => true,
        ]
    ];
    public $containers = [
        'top' => ['default_row_type' => 'grid', 'order' => 100],
        'tabs' => ['default_row_type' => 'grid', 'order' => 500, 'type' => 'tabs'],
        'buttons' => ['default_row_type' => 'grid', 'order' => 900],
        'general_container' => ['default_row_type' => 'grid', 'order' => 32000],
        'groups_container' => [
            'type' => 'details',
            'details_rendering_type' => 'table',
            'details_new_rows' => 1,
            'details_key' => '\Numbers\Backend\System\Policies\Model\Group\Groups',
            'details_pk' => ['sm_polgrogroup_child_sm_polgroup_tenant_id', 'sm_polgrogroup_child_sm_polgroup_id'],
            'order' => 35000
        ],
        'policies_container' => [
            'type' => 'details',
            'details_rendering_type' => 'table',
            'details_new_rows' => 1,
            'details_key' => '\Numbers\Backend\System\Policies\Model\Group\Policies',
            'details_pk' => ['sm_polgropolicy_sm_policy_tenant_id', 'sm_polgropolicy_sm_policy_code'],
            'order' => 35000
        ]
    ];
    public $rows = [
        'tabs' => [
            'general' => ['order' => 100, 'label_name' => 'General'],
            'groups' => ['order' => 150, 'label_name' => 'Groups'],
            'policies' => ['order' => 200, 'label_name' => 'Policies'],
        ]
    ];
    public $elements = [
        'top' => [
            'sm_polgroup_code' => [
                'sm_polgroup_id' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Group #', 'domain' => 'group_id_sequence', 'percent' => 50, 'navigation' => true],
                'sm_polgroup_code' => ['order' => 2, 'label_name' => 'Code', 'domain' => 'group_code', 'required' => true, 'percent' => 45, 'navigation' => true],
                'sm_polgroup_inactive' => ['order' => 3, 'label_name' => 'Inactive', 'type' => 'boolean', 'percent' => 5]
            ],
            'sm_polgroup_name' => [
                'sm_polgroup_name' => ['order' => 1, 'row_order' => 200, 'label_name' => 'Name', 'domain' => 'name', 'null' => true, 'required' => true, 'percent' => 50],
                'sm_polgroup_module_code' => ['order' => 2, 'label_name' => 'Module', 'domain' => 'module_code', 'null' => true, 'required' => true, 'percent' => 35, 'method' => 'select', 'options_model' => '\Numbers\Backend\System\Modules\Model\Modules::optionsActive'],
                'sm_polgroup_global' => ['order' => 3, 'label_name' => 'Global', 'type' => 'boolean', 'percent' => 10, 'readonly' => true],
                'sm_polgroup_weight' => ['order' => 4, 'label_name' => 'Weight', 'domain' => 'weight', 'percent' => 25, 'null' => true, 'required' => true],
            ],
        ],
        'tabs' => [
            'general' => [
                'general' => ['container' => 'general_container', 'order' => 100],
            ],
            'groups' => [
                'groups' => ['container' => 'groups_container', 'order' => 100],
            ],
            'policies' => [
                'policies' => ['container' => 'policies_container', 'order' => 100],
            ],
        ],
        'general_container' => [
            'sm_polgrotype_sm_poltype_code' => [
                '\Numbers\Backend\System\Policies\Model\Group\Types' => ['order' => 1, 'row_order' => 250, 'label_name' => 'Types', 'domain' => 'big_code', 'null' => true, 'multiple_column' => 'sm_polgrotype_sm_poltype_code', 'required' => true, 'percent' => 100, 'method' => 'multiselect', 'options_model' => '\Numbers\Backend\System\Policies\Model\Types::optionsActive', 'options_params' => ['sm_poltype_for_main' => 1]],
            ],
            'sm_polgroup_description' => [
                'sm_polgroup_description' => ['order' => 1, 'row_order' => 300, 'label_name' => 'Description', 'domain' => 'description', 'null' => true, 'percent' => 100, 'method' => 'textarea', 'rows' => 3],
            ],
        ],
        'groups_container' => [
            'row1' => [
                'sm_polgrogroup_child_sm_polgroup_id' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Group', 'domain' => 'group_id', 'required' => true, 'null' => true, 'details_unique_select' => true, 'percent' => 95, 'method' => 'select', 'searchable' => true, 'options_model' => '\Numbers\Backend\System\Policies\DataSource\Groups::optionsJson', 'onchange' => 'this.form.submit();', 'json_contains' => ['sm_polgroup_tenant_id' => 'sm_polgrogroup_child_sm_polgroup_tenant_id', 'sm_polgroup_id' => 'sm_polgrogroup_child_sm_polgroup_id']],
                'sm_polgrogroup_inactive' => ['order' => 2, 'label_name' => 'Inactive', 'type' => 'boolean', 'percent' => 5],
            ],
            self::HIDDEN => [
                'sm_polgrogroup_child_sm_polgroup_tenant_id' => ['label_name' => 'Child Tenant #', 'domain' => 'tenant_id', 'null' => true, 'method' => 'hidden'],
            ]
        ],
        'policies_container' => [
            'row1' => [
                'sm_polgropolicy_sm_policy_code' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Policy', 'domain' => 'group_code', 'required' => true, 'null' => true, 'details_unique_select' => true, 'percent' => 95, 'method' => 'select', 'searchable' => true, 'options_model' => '\Numbers\Backend\System\Policies\DataSource\Policies::optionsJson', 'onchange' => 'this.form.submit();', 'json_contains' => ['sm_policy_tenant_id' => 'sm_polgropolicy_sm_policy_tenant_id', 'sm_policy_code' => 'sm_polgropolicy_sm_policy_code']],
                'sm_polgropolicy_inactive' => ['order' => 2, 'label_name' => 'Inactive', 'type' => 'boolean', 'percent' => 5],
            ],
            self::HIDDEN => [
                'sm_polgropolicy_sm_policy_tenant_id' => ['label_name' => 'Child Tenant #', 'domain' => 'tenant_id', 'null' => true, 'method' => 'hidden'],
            ]
        ],
        'buttons' => [
            self::BUTTONS => [
                self::BUTTON_SUBMIT_SAVE => self::BUTTON_SUBMIT_SAVE_DATA,
                self::BUTTON_SUBMIT_DELETE => self::BUTTON_SUBMIT_DELETE_DATA,
            ]
        ]
    ];
    public $collection = [
        'name' => 'SM System Policy Groups',
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
            ],
            '\Numbers\Backend\System\Policies\Model\Group\Types' => [
                'name' => 'SM System Policy Group Types',
                'pk' => ['sm_polgrotype_tenant_id', 'sm_polgrotype_sm_polgroup_id', 'sm_polgrotype_sm_poltype_code'],
                'type' => '1M',
                'map' => ['sm_polgroup_tenant_id' => 'sm_polgrotype_tenant_id', 'sm_polgroup_id' => 'sm_polgrotype_sm_polgroup_id']
            ]
        ],
    ];
}
