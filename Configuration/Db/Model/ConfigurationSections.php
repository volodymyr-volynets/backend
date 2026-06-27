<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Configuration\Db\Model;

use Object\Data;

class ConfigurationSections extends Data
{
    public $module_code = 'SM';
    public $title = 'S/M Configuration Db Sections';
    public $column_key = 'sm_confdbsection_code';
    public $column_prefix = 'sm_confdbsection_';
    public $orderby;
    public $columns = [
        'sm_confdbsection_code' => ['name' => 'Type Code', 'domain' => 'code'],
        'sm_confdbsection_name' => ['name' => 'Name', 'type' => 'text'],
        'sm_confdbsection_order' => ['name' => 'Order', 'domain' => 'order'],
    ];
    public $data = [
        '*' => ['sm_confdbsection_name' => '*', 'sm_confdbsection_order' => 1000],
        'production' => ['sm_confdbsection_name' => 'production', 'sm_confdbsection_order' => 2000],
        'staging' => ['sm_confdbsection_name' => 'staging', 'sm_confdbsection_order' => 3000],
        'testing' => ['sm_confdbsection_name' => 'testing', 'sm_confdbsection_order' => 4000],
        'development' => ['sm_confdbsection_name' => 'development', 'sm_confdbsection_order' => 5000],
    ];
}
