<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Configuration\Db\Form;

use Object\Form\Wrapper\Base;

class ConfigurationValues extends Base
{
    public $form_link = 'sm_configuration_db_values';
    public $module_code = 'SM';
    public $title = 'S/M Configuration Db Values Form';
    public $options = [
        'segment' => self::SEGMENT_FORM,
        'actions' => [
            'refresh' => true,
            'back' => true,
            'new' => true
        ]
    ];
    public $containers = [
        'top' => ['default_row_type' => 'grid', 'order' => 100],
        'buttons' => ['default_row_type' => 'grid', 'order' => 900],
    ];
    public $rows = [];
    public $elements = [
        'top' => [
            'sm_confdbvalue_tenant_id' => [
                'sm_confdbvalue_tenant_id' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Tenant #', 'domain' => 'tenant_id', 'percent' => 50, 'required' => 'c'],
                'sm_confdbvalue_section' => ['order' => 2, 'label_name' => 'Section', 'domain' => 'code', 'percent' => 45, 'required' => true, 'method' => 'select', 'options_model' => '\Numbers\Backend\Configuration\Db\Model\ConfigurationSections'],
                'sm_confdbvalue_inactive' => ['order' => 5, 'label_name' => 'Inactive', 'type' => 'boolean', 'percent' => 5]
            ],
            'sm_confdbvalue_key' => [
                'sm_confdbvalue_key' => ['order' => 1, 'row_order' => 200, 'label_name' => 'Key', 'domain' => 'code', 'percent' => 100, 'required' => true],
            ],
            'sm_confdbvalue_value' => [
                'sm_confdbvalue_value' => ['order' => 1, 'row_order' => 300, 'label_name' => 'Value', 'type' => 'json', 'percent' => 100, 'required' => 'c', 'method' => 'textarea'],
            ]
        ],
        'buttons' => [
            self::BUTTONS => self::BUTTONS_DATA_GROUP
        ]
    ];
    public $collection = [
        'name' => 'SM Configuration Db Values',
        'model' => '\Numbers\Backend\Configuration\Db\Model\ConfigurationValues',
    ];

    public function validate(\Object\Form\Base $form)
    {
        if (is_blank($form->values['sm_confdbvalue_tenant_id'])) {
            $form->validateQuickRequired('sm_confdbvalue_tenant_id');
        }
        if (is_blank($form->values['sm_confdbvalue_value'])) {
            $form->validateQuickRequired('sm_confdbvalue_value');
        }
    }
}
