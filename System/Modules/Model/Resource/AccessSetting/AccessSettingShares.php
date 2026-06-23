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

class AccessSettingShares extends Data
{
    public $module_code = 'SM';
    public $title = 'S/M Access Setting Shares';
    public $column_key = 'sm_rsacsershare_id';
    public $column_prefix = 'sm_rsacsershare_';
    public $orderby = [
        'sm_rsacsershare_order' => SORT_ASC,
    ];
    public $columns = [
        'sm_rsacsershare_code' => ['name' => 'Code', 'domain' => 'group_code'],
        'sm_rsacsershare_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_rsacsershare_order' => ['name' => 'Order', 'domain' => 'order'],
    ];
    public $data = [
        'SHARE::ALL' => ['sm_rsacsershare_name' => 'All User', 'sm_rsacsershare_order' => 1000],
        'SHARE::OWNERS' => ['sm_rsacsershare_name' => 'Owners User', 'sm_rsacsershare_order' => 2000],
        'SHARE::SHARED' => ['sm_rsacsershare_name' => 'Shared Users', 'sm_rsacsershare_order' => 3000],
    ];
}
