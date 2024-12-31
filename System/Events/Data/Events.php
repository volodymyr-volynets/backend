<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Events\Data;

use Object\Import;

class Events extends Import
{
    public $data = [
        'tasks' => [
            'options' => [
                'pk' => ['sm_evtqueue_code'],
                'model' => '\Numbers\Backend\System\Events\Model\Queues',
                'method' => 'save'
            ],
            'data' => [
                [
                    'sm_evtqueue_code' => 'SM::DEFAULT',
                    'sm_evtqueue_name' => 'S/M Default (Queue)',
                    'sm_evtqueue_inactive' => 0,
                ],
            ],
        ],
    ];
}
