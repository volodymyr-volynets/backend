<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Events\API\V1\SM;

use Numbers\Backend\System\Events\Model\EventsAR;
use Numbers\Backend\System\Events\Model\RequestsAR;
use Numbers\Backend\System\Events\Class2\RequestEventStatuses;
use Object\Controller\API;
use Helper\Constant\HTTPConstants;

class Events extends API
{
    public $group = ['SM', 'System', 'Events'];
    public $name = 'S/M Events (API V1)';
    public $version = 'V1';
    public $base_url = '/API/V1/SM/Events';
    public $tenant = true;
    public $module = false;
    public $acl = [
        'public' => true,
        'authorized' => true,
        'permission' => false,
    ];

    public $loc = [];

    /**
     * Routes
     */
    public function routes()
    {
        \Route::api($this->name, $this->base_url, self::class, $this->route_options)
            ->acl('Public,Authorized');
    }

    /**
     * Request Event API
     */
    public $postRequestEvent_name = 'S/M Request Event';
    public $postRequestEvent_description = 'Use this API to request an event in the system.';
    public $postRequestEvent_columns = [
        'bearer_token' => ['required' => true, 'domain' => 'token', 'name' => 'Bearer Token', 'loc' => 'NF.Form.BearerToken', 'from_application' => 'flag.global.__bearer_token'],
        'code' => ['required' => true, 'name' => 'Code', 'domain' => 'group_code'],
        'data' => ['sometimes' => true, 'name' => 'Data', 'type' => 'array'],
        'options' => ['sometimes' => true, 'name' => 'Options', 'type' => 'array'],
    ];
    public $postRequestEvent_result_danger = \Validator::RESULT_DANGER;
    public $postRequestEvent_result_success = RESULT_SUCCESS;
    public function postRequestEvent(RequestsAR $request_ar, EventsAR $event_ar)
    {
        $result = $request_ar->fill([
            'sm_evtrequest_tenant_id' => \Tenant::id(),
            'sm_evtrequest_id' => $request_ar->getDbObject()->uuidTenanted(),
            'sm_evtrequest_um_user_id' => $this->values['data']['user_id'] ?? \User::id() ?? 0,
            'sm_evtrequest_sm_event_code' => $this->values['code'],
            'sm_evtrequest_sm_event_name' => $event_ar->loadIDByCode($this->values['code'], null, 'sm_event_name'),
            'sm_evtrequest_sm_evtqueue_code' => $this->values['data']['queue'] ?? 'SM::DEFAULT',
            'sm_evtrequest_sm_evttype_code' => $this->values['options']['type'] ?? 'SM::REQUEST_END',
            'sm_evtrequest_status_id' => RequestEventStatuses::New->value,
            'sm_evtrequest_data' => $this->values['data'] ?? [],
            'sm_evtrequest_options' => $this->values['options'] ?? [],
            'sm_evtrequest_inactive' => 0,
        ])->merge();
        if (!$result['success']) {
            return $this->finish(HTTPConstants::Status500InternalServerError, $result);
        } else {
            return $this->finish(HTTPConstants::Status200OK, $result);
        }
    }
}
