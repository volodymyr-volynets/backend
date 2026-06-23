<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\SMS\Common\Form;

use Object\Form\Wrapper\Base;

class Profiles extends Base
{
    public $form_link = 'sm_sms_profiles';
    public $module_code = 'SM';
    public $title = 'S/M SMS Profiles Form';
    public $options = [
        'segment' => self::SEGMENT_FORM,
        'actions' => [
            'refresh' => true,
            'new' => true,
            'back' => true,
            'import' => true
        ]
    ];
    public $containers = [
        'top' => ['default_row_type' => 'grid', 'order' => 100],
        'tabs' => ['default_row_type' => 'grid', 'order' => 500, 'type' => 'tabs'],
        'buttons' => ['default_row_type' => 'grid', 'order' => 900],
        'phones_container' => [
            'type' => 'details',
            'details_rendering_type' => 'table',
            'details_new_rows' => 1,
            'details_key' => '\Numbers\Backend\SMS\Common\Model\Profile\Senders',
            'details_pk' => ['sm_smsprosndr_id'],
            'details_autoincrement' => ['sm_smsprosndr_id'],
            'order' => 35000,
            'required' => true,
        ],
    ];
    public $rows = [
        'tabs' => [
            'general' => ['order' => 100, 'label_name' => 'General'],
            'phones' => ['order' => 200, 'label_name' => 'Phones'],
        ],
    ];
    public $elements = [
        'top' => [
            'sm_smsprofile_id' => [
                'sm_smsprofile_id' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Resource #', 'domain' => 'resource_id_sequence', 'percent' => 95, 'navigation' => true],
                'sm_smsprofile_inactive' => ['order' => 2, 'label_name' => 'Inactive', 'type' => 'boolean', 'percent' => 5]
            ],
            'sm_smsprofile_name' => [
                'sm_smsprofile_name' => ['order' => 1, 'row_order' => 200, 'label_name' => 'Name', 'domain' => 'name', 'required' => true, 'percent' => 100],
            ],
        ],
        'tabs' => [
            'general' => [
                'general' => ['container' => 'general_container', 'order' => 100],
            ],
            'phones' => [
                'phones' => ['container' => 'phones_container', 'order' => 100],
            ],
        ],
        'general_container' => [
            'sm_smsprofile_sm_smsproftype_code' => [
                'sm_smsprofile_sm_smsproftype_code' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Type', 'domain' => 'group_code', 'null' => true, 'required' => true, 'percent' => 100, 'method' => 'select', 'options_model' => '\Numbers\Backend\SMS\Common\Model\Profile\ProfileTypes'],
            ],
            'sm_smsprofile_account_sid' => [
                'sm_smsprofile_account_sid' => ['order' => 1, 'row_order' => 200, 'label_name' => 'Account SID', 'domain' => 'encrypted_password', 'null' => true, 'required' => true, 'percent' => 50, 'method' => 'password', 'method_renderer' => 'self::renderFieldValueMethodRenderer'],
                'sm_smsprofile_auth_token' => ['order' => 2, 'label_name' => 'Auth Token', 'domain' => 'encrypted_password', 'null' => true, 'required' => true, 'percent' => 50, 'method' => 'password', 'method_renderer' => 'self::renderFieldValueMethodRenderer'],
            ],
        ],
        'phones_container' => [
            'row1' => [
                'sm_smsprosndr_name' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Name', 'domain' => 'name', 'null' => true, 'required' => true, 'percent' => 50, 'onblur' => 'this.form.submit();'],
                'sm_smsprosndr_phone' => ['order' => 2, 'label_name' => 'Phone', 'domain' => 'phone', 'null' => true, 'required' => true, 'percent' => 45],
                'sm_smsprosndr_inactive' => ['order' => 3, 'label_name' => 'Inactive', 'type' => 'boolean', 'percent' => 5]
            ],
            self::HIDDEN => [
                'sm_smsprosndr_id' => ['label_name' => 'Detail #', 'domain' => 'detail_id', 'null' => true, 'method' => 'hidden'],
            ]
        ],
        'buttons' => [
            self::BUTTONS => self::BUTTONS_DATA_GROUP
        ]
    ];
    public $collection = [
        'name' => 'SM SMS Profiles',
        'model' => '\Numbers\Backend\SMS\Common\Model\Profiles',
        'details' => [
            '\Numbers\Backend\SMS\Common\Model\Profile\Senders' => [
                'name' => 'SM SMS Profile Senders',
                'pk' => ['sm_smsprosndr_tenant_id', 'sm_smsprosndr_sm_smsprofile_id', 'sm_smsprosndr_id'],
                'type' => '1M',
                'map' => ['sm_smsprofile_tenant_id' => 'sm_smsprosndr_tenant_id', 'sm_smsprofile_id' => 'sm_smsprosndr_sm_smsprofile_id']
            ]
        ]
    ];

    public function renderFieldValueMethodRenderer(& $form, & $options, & $value, & $neighbouring_values)
    {
        $id = $options['options']['id'];
        $result = [
            'left' => loc('NF.Form.Key', 'Key'),
            'value' => $value,
            'right' => [],
        ];
        $result['right'][] = \HTML::a(['href' => 'javascript:void(0);', 'value' => loc('NF.Form.View', 'View'), 'onclick' => "$('#" . $id . "').attr('type', 'input');"]);
        $result['right'][] = \HTML::a(['href' => 'javascript:void(0);', 'value' => loc('NF.Form.Copy', 'Copy'), 'onclick' => "Numbers.Form.copyToClipboard($('#" . $id . "').val());"]);
        return \HTML::inputGroup($result);
    }
}
