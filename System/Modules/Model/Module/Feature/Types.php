<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model\Module\Feature;

use Object\Data;

class Types extends Data
{
    public $module_code = 'SM';
    public $title = 'S/M Module Feature Types';
    public $column_key = 'sm_ftrtype_id';
    public $column_prefix = 'sm_ftrtype_';
    public $orderby;
    public $columns = [
        'sm_ftrtype_id' => ['name' => 'Type #', 'domain' => 'type_id'],
        'sm_ftrtype_name' => ['name' => 'Name', 'type' => 'text']
    ];
    public $data = [
        10 => ['sm_ftrtype_name' => 'General'], // activated by default
        20 => ['sm_ftrtype_name' => 'Notification (Optional)'],
        21 => ['sm_ftrtype_name' => 'Notification (Mandatory)'],
        25 => ['sm_ftrtype_name' => 'SMS (Optional)'],
        26 => ['sm_ftrtype_name' => 'SMS (Mandatory)'],
        30 => ['sm_ftrtype_name' => 'Data'], // can be reactivated
        40 => ['sm_ftrtype_name' => 'User Activated Feature'],
    ];
}
