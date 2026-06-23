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

class AccessSettingTypes extends Data
{
    public $module_code = 'SM';
    public $title = 'S/M Access Setting Types';
    public $column_key = 'sm_rsacsertype_id';
    public $column_prefix = 'sm_rsacsertype_';
    public $orderby = [
        'sm_rsacsertype_order' => SORT_ASC,
    ];
    public $options_map = [
        'sm_rsacsertype_name' => 'name',
        'sm_rsacsertype_icon' => 'icon_class',
    ];
    public $columns = [
        'sm_rsacsertype_code' => ['name' => 'Code', 'domain' => 'group_code'],
        'sm_rsacsertype_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_rsacsertype_order' => ['name' => 'Order', 'domain' => 'order'],
        'sm_rsacsertype_icon' => ['name' => 'Icon', 'domain' => 'icon', 'null' => true],
        'sm_rsacsertype_model' => ['name' => 'Model', 'domain' => 'model'],
    ];
    public $data = [
        'OWNERS' => ['sm_rsacsertype_name' => 'Owner Access', 'sm_rsacsertype_icon' => 'fa-regular fa-user', 'sm_rsacsertype_order' => 1000, 'sm_rsacsertype_model' => '\Numbers\Backend\System\Modules\Model\Resource\AccessSetting\AccessSettingOwners'],
        'SHARE' => ['sm_rsacsertype_name' => 'Share Access', 'sm_rsacsertype_icon' => 'fa-regular fa-share-square', 'sm_rsacsertype_order' => 2000, 'sm_rsacsertype_model' => '\Numbers\Backend\System\Modules\Model\Resource\AccessSetting\AccessSettingShares'],
    ];
}
