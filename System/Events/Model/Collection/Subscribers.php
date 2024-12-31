<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Events\Model\Collection;

use Object\Collection;

class Subscribers extends Collection
{
    public $data = [
        'name' => 'SM System Event Subscribers',
        'model' => '\Numbers\Backend\System\Events\Model\Subscribers',
        'details' => [
            '\Numbers\Backend\System\Events\Model\Subscriptions' => [
                'name' => 'SM System Event Subscriptions',
                'pk' => ['sm_evtsubscription_sm_evtsubscriber_code', 'sm_evtsubscription_sm_event_code', 'sm_evtsubscription_sm_evtqueue_code'],
                'type' => '1M',
                'map' => ['sm_evtsubscriber_code' => 'sm_evtsubscription_sm_evtsubscriber_code'],
            ],
        ]
    ];
}
