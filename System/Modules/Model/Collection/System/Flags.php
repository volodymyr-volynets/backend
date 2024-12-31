<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model\Collection\System;

use Object\Collection;

class Flags extends Collection
{
    public $data = [
        'model' => '\Numbers\Backend\System\Modules\Model\System\Flags',
        'pk' => ['sm_sysflag_id'],
        'details' => [
            '\Numbers\Backend\System\Modules\Model\System\Flag\Map' => [
                'pk' => ['sm_sysflgmap_sysflag_id', 'sm_sysflgmap_action_id'],
                'type' => '1M',
                'map' => ['sm_sysflag_id' => 'sm_sysflgmap_sysflag_id'],
            ],
            '\Numbers\Backend\System\Modules\Model\System\Flag\Features' => [
                'pk' => ['sm_sysflgftr_sysflag_id', 'sm_sysflgftr_feature_code'],
                'type' => '1M',
                'map' => ['sm_sysflag_id' => 'sm_sysflgftr_sysflag_id'],
            ]
        ]
    ];
}
