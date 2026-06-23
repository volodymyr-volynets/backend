<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\SMS\Common\Model\Profile;

use Object\Data;

class ProfileTypes extends Data
{
    public $module_code = 'SM';
    public $title = 'S/M SMS Profile Types';
    public $column_key = 'sm_smsproftype_code';
    public $column_prefix = 'sm_smsproftype_';
    public $orderby = [
        'sm_smsproftype_order' => SORT_ASC,
    ];
    public $columns = [
        'sm_smsproftype_code' => ['name' => 'Type', 'domain' => 'group_code'],
        'sm_smsproftype_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_smsproftype_model' => ['name' => 'Model', 'domain' => 'model'],
        'sm_smsproftype_order' => ['name' => 'Order', 'domain' => 'order'],
    ];
    public $data = [
        'TWILIO' => ['sm_smsproftype_name' => 'Twilio', 'sm_smsproftype_model' => '\Numbers\Backend\SMS\Twilio\Base', 'sm_smsproftype_order' => 1000],
    ];
}
