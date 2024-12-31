<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Session\Db\Controller;

use Numbers\Backend\Session\Db\Model\Sessions;
use Object\Controller;

class Check extends Controller
{
    public $title = 'Session Check';
    public $acl = [
        'public' => 1,
        'authorized' => 1
    ];

    public function actionIndex()
    {
        $input = \Request::input(null, true, true);
        $result = [
            'success' => false,
            'error' => [],
            'loggedin' => false,
            'expired' => false,
            'expires_in' => 0
        ];
        if (!empty($input['token']) && !empty($input[session_name()])) {
            $crypt = new \Crypt();
            $token_data = $crypt->tokenValidate($input['token'], ['skip_time_validation' => true]);
            if (!($token_data === false || $token_data['token'] !== 'general')) {
                // quering database
                $query = Sessions::queryBuilderStatic(['skip_acl' => true])->select();
                $query->columns([
                    'sm_session_expires',
                    'sm_session_user_id'
                ]);
                $query->where('AND', ['a.sm_session_id', '=', $input[session_name()]]);
                $query->where('AND', ['a.sm_session_expires', '>=', \Format::now('timestamp')]);
                $temp = $query->query();
                // put values into result
                $result['expired'] = empty($temp['rows']);
                $result['loggedin'] = !empty($temp['rows'][0]['sm_session_user_id']);
                // calculate when session is about to expire
                if (!empty($temp['rows'])) {
                    $now = \Format::now('unix');
                    $expires = strtotime($temp['rows'][0]['sm_session_expires']);
                    $result['expires_in'] = $expires - $now;
                }
                $result['success'] = true;
            }
        }
        // rendering
        \Layout::renderAs($result, 'application/json');
    }

    /**
     * Renew session
     */
    public function actionRenew()
    {
        $input = \Request::input(null, true, true);
        $result = [
            'success' => false,
            'error' => []
        ];
        if (!empty($input['token'])) {
            $crypt = new \Crypt();
            $token_data = $crypt->tokenValidate($input['token'], ['skip_time_validation' => true]);
            if (!($token_data === false || $token_data['token'] !== 'general')) {
                $result['success'] = true;
            }
        }
        \Layout::renderAs($result, 'application/json');
    }
}
