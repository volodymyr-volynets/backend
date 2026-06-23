<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Mail\Common\Form;

use Object\Form\Wrapper\Base;

class Profiles extends Base
{
    public $form_link = 'sm_mail_profiles';
    public $module_code = 'SM';
    public $title = 'S/M Mail Profiles Form';
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
        'emails_container' => [
            'type' => 'details',
            'details_rendering_type' => 'table',
            'details_new_rows' => 1,
            'details_key' => '\Numbers\Backend\Mail\Common\Model\Profile\Senders',
            'details_pk' => ['sm_mailprosndr_id'],
            'details_autoincrement' => ['sm_mailprosndr_id'],
            'order' => 35000,
            'required' => true,
        ],
    ];
    public $rows = [
        'tabs' => [
            'general' => ['order' => 100, 'label_name' => 'General'],
            'emails' => ['order' => 200, 'label_name' => 'Emails'],
        ],
    ];
    public $elements = [
        'top' => [
            'sm_mailprofile_id' => [
                'sm_mailprofile_id' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Resource #', 'domain' => 'resource_id_sequence', 'percent' => 95, 'navigation' => true],
                'sm_mailprofile_inactive' => ['order' => 2, 'label_name' => 'Inactive', 'type' => 'boolean', 'percent' => 5]
            ],
            'sm_mailprofile_name' => [
                'sm_mailprofile_name' => ['order' => 1, 'row_order' => 200, 'label_name' => 'Name', 'domain' => 'name', 'required' => true, 'percent' => 100],
            ],
        ],
        'tabs' => [
            'general' => [
                'general' => ['container' => 'general_container', 'order' => 100],
            ],
            'emails' => [
                'emails' => ['container' => 'emails_container', 'order' => 100],
            ],
        ],
        'general_container' => [
            'sm_mailprofile_sm_mailproftype_code' => [
                'sm_mailprofile_sm_mailproftype_code' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Type', 'domain' => 'group_code', 'null' => true, 'required' => true, 'percent' => 100, 'method' => 'select', 'options_model' => '\Numbers\Backend\Mail\Common\Model\Profile\ProfileTypes'],
            ],
            'sm_mailprofile_host' => [
                'sm_mailprofile_host' => ['order' => 1, 'row_order' => 200, 'label_name' => 'Host', 'domain' => 'host2', 'null' => true, 'required' => true, 'percent' => 50],
                'sm_mailprofile_port' => ['order' => 2, 'label_name' => 'Port', 'domain' => 'port', 'null' => true, 'required' => true, 'percent' => 50],
            ],
            'sm_mailprofile_username' => [
                'sm_mailprofile_username' => ['order' => 1, 'row_order' => 300, 'label_name' => 'Username', 'domain' => 'encrypted_password', 'null' => true, 'required' => true, 'percent' => 50, 'method' => 'password', 'method_renderer' => 'self::renderFieldValueMethodRenderer'],
                'sm_mailprofile_password' => ['order' => 2, 'label_name' => 'Password', 'domain' => 'encrypted_password', 'null' => true, 'required' => true, 'percent' => 50, 'method' => 'password', 'method_renderer' => 'self::renderFieldValueMethodRenderer'],
            ],
            'sm_mailprofile_auth' => [
                'sm_mailprofile_secure' => ['order' => 1, 'row_order' => 400, 'label_name' => 'Secure', 'domain' => 'code', 'null' => true, 'percent' => 50],
                'sm_mailprofile_auth' => ['order' => 2, 'label_name' => 'Auth', 'type' => 'boolean', 'percent' => 50],
            ],
        ],
        'emails_container' => [
            'row1' => [
                'sm_mailprosndr_name' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Name', 'domain' => 'name', 'null' => true, 'required' => true, 'percent' => 50, 'onblur' => 'this.form.submit();'],
                'sm_mailprosndr_email' => ['order' => 2, 'label_name' => 'Email', 'domain' => 'email', 'null' => true, 'required' => true, 'percent' => 45],
                'sm_mailprosndr_inactive' => ['order' => 3, 'label_name' => 'Inactive', 'type' => 'boolean', 'percent' => 5]
            ],
            self::HIDDEN => [
                'sm_mailprosndr_id' => ['label_name' => 'Detail #', 'domain' => 'detail_id', 'null' => true, 'method' => 'hidden'],
            ]
        ],
        'buttons' => [
            self::BUTTONS => self::BUTTONS_DATA_GROUP
        ]
    ];
    public $collection = [
        'name' => 'SM Mail Profiles',
        'model' => '\Numbers\Backend\Mail\Common\Model\Profiles',
        'details' => [
            '\Numbers\Backend\Mail\Common\Model\Profile\Senders' => [
                'name' => 'SM Mail Profile Senders',
                'pk' => ['sm_mailprosndr_tenant_id', 'sm_mailprosndr_sm_mailprofile_id', 'sm_mailprosndr_id'],
                'type' => '1M',
                'map' => ['sm_mailprofile_tenant_id' => 'sm_mailprosndr_tenant_id', 'sm_mailprofile_id' => 'sm_mailprosndr_sm_mailprofile_id']
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
