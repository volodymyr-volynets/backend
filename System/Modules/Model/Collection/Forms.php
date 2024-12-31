<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model\Collection;

use Object\Collection;

class Forms extends Collection
{
    public $data = [
        'model' => '\Numbers\Backend\System\Modules\Model\Forms',
        'pk' => ['sm_form_code'],
        'details' => [
            '\Numbers\Backend\System\Modules\Model\Form\Fields' => [
                'pk' => ['sm_frmfield_form_code', 'sm_frmfield_code'],
                'type' => '1M',
                'map' => ['sm_form_code' => 'sm_frmfield_form_code'],
            ]
        ]
    ];
}
