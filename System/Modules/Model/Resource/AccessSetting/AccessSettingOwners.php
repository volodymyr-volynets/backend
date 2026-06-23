<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model\Resource\AccessSetting;

use Object\Data;

class AccessSettingOwners extends Data
{
    public $module_code = 'SM';
    public $title = 'S/M Access Setting Owners';
    public $column_key = 'sm_rsacserowner_id';
    public $column_prefix = 'sm_rsacserowner_';
    public $orderby = [
        'sm_rsacserowner_order' => SORT_ASC,
    ];
    public $columns = [
        'sm_rsacserowner_code' => ['name' => 'Code', 'domain' => 'group_code'],
        'sm_rsacserowner_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_rsacserowner_order' => ['name' => 'Order', 'domain' => 'order'],
    ];
    public $data = [
        'OWNERS::SEE_SELF' => ['sm_rsacserowner_name' => 'See Own Records', 'sm_rsacserowner_order' => 1000],
        'OWNERS::SEE_SHARED' => ['sm_rsacserowner_name' => 'See Shared Records', 'sm_rsacserowner_order' => 2000],
        'OWNERS::RESTRICTED' => ['sm_rsacserowner_name' => 'Restricted', 'sm_rsacserowner_order' => 3000],
    ];
}
