<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Controller;

use Numbers\Backend\System\Modules\Model\Task\Progresses;
use Object\Content\Messages;
use Object\Controller;

class TaskProgress extends Controller
{ // \Object\Controller\Authorized

    public $title = 'Task Progress';

    public function actionIndex()
    {
        $id = (int) \Request::input('__form_progress');
        $result = ['success' => false, 'error' => [], 'message' => Messages::LOADING, 'data ' => []];
        if ($id) {
            $query = Progresses::queryBuilderStatic(['skip_acl' => true])->select();
            $query->columns([
                'percent' => 'sm_tskprogress_percent',
                'total' => 'sm_tskprogress_task_total',
                'completed' => 'sm_tskprogress_task_completed'
            ]);
            $query->where('AND', ['sm_tskprogress_tenant_id', '=', \Tenant::id()]);
            $query->where('AND', ['sm_tskprogress_id', '=', $id]);
            $result['data'] = $query->query()['rows'][0] ?? [];
            if (!empty($result['data'])) {
                $result['success'] = true;
                $result['message'] = str_replace(
                    [
                    '[percent]',
                    '[completed]',
                    '[total]'
                ],
                    [
                    $result['data']['percent'] . '%',
                    $result['data']['completed'],
                    $result['data']['total']
                ],
                    Messages::LOADING_COMPLETED
                );
            }
        }
        \Layout::renderAs($result, 'application/json');
    }
}
