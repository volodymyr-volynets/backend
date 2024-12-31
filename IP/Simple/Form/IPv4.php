<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\IP\Simple\Form;

use Object\Content\Messages;
use Object\Form\Wrapper\Base;

class IPv4 extends Base
{
    public $form_link = 'sm_ipv4';
    public $module_code = 'SM';
    public $title = 'S/M IPv4 Form';
    public $options = [
        'segment' => self::SEGMENT_FORM,
        'actions' => [
            'refresh' => true,
            'back' => true,
            'new' => true,
            'import' => true
        ]
    ];
    public $containers = [
        'top' => ['default_row_type' => 'grid', 'order' => 100],
        'buttons' => ['default_row_type' => 'grid', 'order' => 900]
    ];
    public $rows = [];
    public $elements = [
        'top' => [
            'sm_ipver4_start' => [
                'sm_ipver4_start' => ['order' => 1, 'row_order' => 100, 'label_name' => 'IP Start', 'type' => 'bigint', 'percent' => 50, 'required' => true],
                'sm_ipver4_end' => ['order' => 2, 'label_name' => 'IP End', 'type' => 'bigint', 'percent' => 50, 'required' => true],
            ],
            'sm_ipver4_country_code' => [
                'sm_ipver4_country_code' => ['order' => 1, 'row_order' => 200, 'label_name' => 'Country Code', 'domain' => 'name', 'maxlength' => 2, 'null' => true, 'required' => true, 'percent' => 33],
                'sm_ipver4_province' => ['order' => 2, 'label_name' => 'Province', 'domain' => 'name', 'null' => true, 'percent' => 33],
                'sm_ipver4_city' => ['order' => 3, 'label_name' => 'City', 'domain' => 'name', 'null' => true, 'percent' => 34],
            ],
            'sm_ipver4_latitude' => [
                'sm_ipver4_latitude' => ['order' => 1, 'row_order' => 300, 'label_name' => 'Latitude', 'domain' => 'geo_coordinate', 'null' => true, 'default' => null],
                'sm_ipver4_longitude' => ['order' => 2, 'label_name' => 'Longitude', 'domain' => 'geo_coordinate', 'null' => true, 'default' => null]
            ]
        ],
        'buttons' => [
            self::BUTTONS => self::BUTTONS_DATA_GROUP
        ]
    ];
    public $collection = [
        'name' => 'IPv4',
        'model' => '\Numbers\Backend\IP\Simple\Model\IPv4'
    ];

    public function validate(& $form)
    {
        if (!empty($form->values['sm_ipver4_country_code'])) {
            if (trim($form->values['sm_ipver4_country_code']) == '-') {
                $form->values['sm_ipver4_country_code'] = '--';
            }
            if (strlen($form->values['sm_ipver4_country_code']) != 2) {
                $form->error(DANGER, Messages::INVALID_VALUES, 'sm_ipver4_country_code');
            }
        }
    }
}
