<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Events\Class2;

use Numbers\Backend\System\Events\Model\RequestsAR;
use Numbers\Backend\System\Events\Class2\RequestEventStatuses;
use Numbers\Backend\System\Events\Model\Subscribers;
use Numbers\Backend\System\Events\Model\Subscriptions;
use Numbers\Backend\System\Events\Model\Reponses;
use Object\Event\EventSubscriberBase;

class EventProcessor
{
    /**
     * Cached subscribers
     *
     * @var array|null
     */
    public static ?array $cached_subscribers = null;

    /**
     * Cached subscriptions
     *
     * @var array|null
     */
    public static ?array $cached_subscriptions = null;

    /**
     * Process one event
     *
     * @param int $tenant_id
     * @param string $request_id
     * @param string $code
     * @param mixed $data
     * @param array $options
     * @return array
     */
    public static function processOneEvent(string $request_id): array
    {
        $requests_ar = new RequestsAR();
        // if we are in transaction - means somethong went wrong we silently exit
        if ($requests_ar->getDbObject()->inTransaction()) {
            throw new \Exception('Event Processor: event is in transaction.');
        }
        /** @var RequestsAR $request */
        $request = $requests_ar->loadById($request_id);
        // if request not found or not in new status we return success
        if (!$request || $request->sm_evtrequest_status_id != RequestEventStatuses::New->value) {
            return ['success' => true, 'error' => []];
        }
        // load subscribers and subscriptions
        if (!isset(self::$cached_subscribers)) {
            self::$cached_subscribers = Subscribers::getStatic([
                'pk' => ['sm_evtsubscriber_code'],
            ]) ?? [];
        }
        if (!isset(self::$cached_subscriptions)) {
            self::$cached_subscriptions = Subscriptions::getStatic([
                'pk' => ['sm_evtsubscription_sm_event_code', 'sm_evtsubscription_sm_evtqueue_code', 'sm_evtsubscription_sm_evtsubscriber_code']
            ]) ?? [];
        }
        // deliver event to all subscribers if present
        $subscribers = self::$cached_subscriptions[$request->sm_evtrequest_sm_event_code][$request->sm_evtrequest_sm_evtqueue_code] ?? [];
        if (empty($subscribers)) {
            return ['success' => true, 'error' => []];
        }
        // set in progress
        $result = $requests_ar->fill([
            'sm_evtrequest_tenant_id' => \Tenant::id(),
            'sm_evtrequest_id' => $request_id,
            'sm_evtrequest_status_id' => RequestEventStatuses::InProgress->value
        ])->merge();
        if (!$result['success']) {
            return $result;
        }
        // execute models
        $success_counter = 0;
        $error_counter = 0;
        $responses = [];
        foreach ($subscribers as $k => $v) {
            $class = self::$cached_subscribers[$k]['sm_evtsubscriber_model'];
            /** @var EventSubscriberBase $model */
            $model = \Factory::model($class, true);
            $options_json = $request->sm_evtrequest_options;
            if (is_json($options_json)) {
                $options_json = json_decode($options_json, true);
            }
            $data_json = $request->sm_evtrequest_data;
            if (is_json($data_json)) {
                $data_json = json_decode($data_json, true);
            }
            $result = $model->execute($request_id, $request->sm_evtrequest_sm_event_code, $data_json, $options_json);
            $status_id = RequestEventStatuses::Completed->value;
            if ($result['success']) {
                $success_counter++;
            } else {
                $status_id = RequestEventStatuses::Errored->value;
                $error_counter++;
            }
            $responses[] = [
                'sm_evtresponse_tenant_id' => \Tenant::id(),
                'sm_evtresponse_id' => $requests_ar->getDbObject()->uuidTenanted(),
                'sm_evtresponse_sm_evtrequest_id' => $request_id,
                'sm_evtresponse_um_user_id' => $result['user_id'] ?? \User::id(),
                'sm_evtresponse_sm_event_code' => $request->sm_evtrequest_sm_event_code,
                'sm_evtresponse_sm_event_name' => $request->sm_evtrequest_sm_event_name,
                'sm_evtresponse_sm_evtqueue_code' => $request->sm_evtrequest_sm_evtqueue_code,
                'sm_evtresponse_sm_evtsubscriber_code' => self::$cached_subscribers[$k]['sm_evtsubscriber_code'],
                'sm_evtresponse_sm_evtsubscriber_name' => self::$cached_subscribers[$k]['sm_evtsubscriber_name'],
                'sm_evtresponse_status_id' => $status_id,
                'sm_evtresponse_result' => $result,
                'sm_evtresponse_retry' => 1,
                'sm_evtresponse_inactive' => 0,
            ];
        }
        // post results to responses table
        $result = Reponses::collectionStatic()->mergeMultiple($responses);
        if (!$result['success']) {
            return $result;
        }
        // post results to requests table
        $requests_ar = new RequestsAR();
        $status_id = RequestEventStatuses::Completed->value;
        if ($error_counter > 0) {
            $status_id = RequestEventStatuses::Errored->value;
        }
        $result = $requests_ar->fill([
            'sm_evtrequest_tenant_id' => \Tenant::id(),
            'sm_evtrequest_id' => $request_id,
            'sm_evtrequest_status_id' => $status_id
        ])->merge();
        if (!$result['success']) {
            return $result;
        }
        return ['success' => false, 'error' => []];
    }
}
