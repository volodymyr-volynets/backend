<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Events;

use Numbers\Backend\System\Events\Model\EventsAR;
use Numbers\Backend\System\Events\Model\RequestsAR;
use Numbers\Backend\System\Events\Class2\RequestEventStatuses;
use Numbers\Backend\System\Events\Class2\EventProcessor;

class Base
{
    /**
     * Register an event
     *
     * @param string $code
     * @param mixed $data
     * @param array $options
     * @return array
     */
    public function registerAnEvent(string $code, mixed $data, array $options = []): array
    {
        $request_ar = new RequestsAR();
        $event_ar = new EventsAR();
        // we might have a transaction that can be rolled back
        if ($request_ar->getDbObject()->inTransaction()) {
            $result = \API::remoteRun('\Numbers\Backend\System\Events\API\V1\SM\Events', 'postRequestEvent', [
                'code' => $code,
                'data' => json_encode($data),
                'options' => json_encode($options),
            ]);
        } else {
            $result = $request_ar->fill([
                'sm_evtrequest_tenant_id' => \Tenant::id(),
                'sm_evtrequest_id' => $request_ar->getDbObject()->uuidTenanted(),
                'sm_evtrequest_um_user_id' => $data['user_id'] ?? \User::id() ?? 0,
                'sm_evtrequest_sm_event_code' => $code,
                'sm_evtrequest_sm_event_name' => $event_ar->loadIDByCode($code, null, 'sm_event_name'),
                'sm_evtrequest_sm_evttype_code' => $options['type'] ?? 'SM::REQUEST_END',
                'sm_evtrequest_sm_evtqueue_code' => $options['queue'] ?? 'SM::DEFAULT',
                'sm_evtrequest_status_id' => RequestEventStatuses::New->value,
                'sm_evtrequest_data' => $data,
                'sm_evtrequest_options' => $options,
                'sm_evtrequest_inactive' => 0,
            ])->merge();
        }
        // set event
        if ($result['success']) {
            \Event::setEvent([
                'type' => $options['type'] ?? 'SM::REQUEST_END',
                'tenant_id' => $result['new_pk']['sm_evtrequest_tenant_id'],
                'request_id' => $result['new_pk']['sm_evtrequest_id'],
                'code' => $code,
                'data' => $data,
                'options' => $options,
            ]);
        } else {
            \Event::setEvent([
                'type' => 'SM::ERRORS',
                'code' => $code,
                'data' => $data,
                'options' => $options,
            ]);
        }
        return $result;
    }

    /**
     * Process one event
     *
     * @param string $request_id
     * @return array
     */
    public static function processOneEvent(string $request_id): array
    {
        return EventProcessor::processOneEvent($request_id);
    }
}
