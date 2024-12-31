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

use Numbers\Backend\System\Modules\Model\Module\Features;

class Options extends Features
{
    public $pk = ['sm_feature_module_code', 'sm_feature_code'];
    public $alias_model = true;
}
