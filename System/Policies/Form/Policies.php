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
use Numbers\Backend\System\Policies\Model\TypesAR;
use NF\Error;

class Policies extends Base
{
    public $form_link = 'sm_system_policies';
    public $module_code = 'SM';
    public $title = 'S/M System Policies Form';
    public $options = [
        'segment' => self::SEGMENT_FORM,
        'actions' => [
            'refresh' => true,
            'back' => true,
            'new' => true,
        ],
        'no_ajax_form_reload' => true
    ];
    public $containers = [
        'top' => ['default_row_type' => 'grid', 'order' => 100],
        'buttons' => ['default_row_type' => 'grid', 'order' => 900]
    ];
    public $rows = [];
    public $elements = [
        'top' => [
            'sm_policy_code' => [
                'sm_policy_code' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Code', 'domain' => 'group_code', 'required' => true, 'percent' => 95, 'navigation' => true],
                'sm_policy_inactive' => ['order' => 2, 'label_name' => 'Inactive', 'type' => 'boolean', 'percent' => 5]
            ],
            'sm_policy_name' => [
                'sm_policy_name' => ['order' => 1, 'row_order' => 200, 'label_name' => 'Name', 'domain' => 'name', 'null' => true, 'required' => true, 'percent' => 50],
                'sm_policy_module_code' => ['order' => 2, 'label_name' => 'Module', 'domain' => 'module_code', 'null' => true, 'required' => true, 'percent' => 35, 'method' => 'select', 'options_model' => '\Numbers\Backend\System\Modules\Model\Modules::optionsActive'],
                'sm_policy_global' => ['order' => 3, 'label_name' => 'Global', 'type' => 'boolean', 'percent' => 10, 'readonly' => true],
                'sm_policy_weight' => ['order' => 4, 'label_name' => 'Weight', 'domain' => 'weight', 'percent' => 25, 'null' => true, 'required' => true],
            ],
            'sm_policy_sm_poltype_code' => [
                'sm_policy_sm_poltype_code' => ['order' => 1, 'row_order' => 250, 'label_name' => 'Type', 'domain' => 'big_code', 'null' => true, 'required' => true, 'percent' => 100, 'method' => 'select', 'options_model' => '\Numbers\Backend\System\Policies\Model\Types::optionsActive', 'options_params' => ['sm_poltype_for_main' => 1], 'onchange' => 'this.form.submit();'],
            ],
            'sm_policy_description' => [
                'sm_policy_description' => ['order' => 1, 'row_order' => 300, 'label_name' => 'Description', 'domain' => 'description', 'null' => true, 'percent' => 100, 'method' => 'textarea', 'rows' => 3],
            ],
            'sm_policy_external_json' => [
                'sm_policy_external_json' => ['order' => 1, 'row_order' => 400, 'label_name' => 'JSON Document', 'type' => 'json', 'json_nice' => true, 'null' => true, 'required' => true, 'percent' => 100, 'method' => 'textarea', 'rows' => 12],
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
        'name' => 'SM System Policies',
        'model' => '\Numbers\Backend\System\Policies\Model\Policies',
    ];

    public $helper_objects = [
        'type_ar' => ['class' => TypesAR::class, 'column' => 'sm_policy_sm_poltype_code', 'inner_where' => 'sm_poltype_code', 'inner_column' => 'sm_poltype_model']
    ];

    public function refresh(& $form)
    {
        $type_ar_model = $this->getHelperObject('type_ar', $form);
        if ($type_ar_model) {
            if (empty($form->values['sm_policy_external_json'])) {
                $form->values['sm_policy_external_json'] = $type_ar_model->preset();
            }
        }
    }

    public function validate(& $form)
    {
        // fix json data
        $sm_policy_external_json = $form->values['sm_policy_external_json'];
        $json = (new \Json2($sm_policy_external_json));
        if ($json->isValid()) {
            $json = $json->sortByKey();
            $form->values['sm_policy_external_json'] = $json->toJSON(true);
            // validate through type object
            $type_ar_model = $this->getHelperObject('type_ar', $form);
            if (!$type_ar_model) {
                $form->error(DANGER, Error::REQUIRED_FIELD, 'sm_policy_sm_poltype_code');
                return;
            }
            $result = $type_ar_model->validate($json->toArray());
            if (!$result['success']) {
                $form->error(DANGER, $result['error'], 'sm_policy_external_json');
                return;
            }
            // put normilized json back
            $form->values['sm_policy_internal_json'] = $result['data'];
        } else {
            $form->values['sm_policy_external_json'] = $json->toArrayOrScalar();
            $form->values['sm_policy_internal_json'] = null;
            $form->error(DANGER, Error::INVALID_VALUES, 'sm_policy_external_json');
        }
    }
}
