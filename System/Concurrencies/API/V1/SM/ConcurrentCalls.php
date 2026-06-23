<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Concurrencies\API\V1\SM;

use Object\Controller\API;
use Helper\Constant\HTTPConstants;
use Object\Reflection;

class ConcurrentCalls extends API
{
    public $group = ['SM', 'System', 'Concurrencies'];
    public $name = 'S/M System Concurrencies Concurrent Calls (API V1)';
    public $version = 'V1';
    public $base_url = '/API/V1/SM/ConcurrentCalls';
    public $model = null;
    public $pk = null;
    public $tenant = true;
    public $module = false;
    public $acl = [
        'public' => true,
        'authorized' => true,
        'permission' => false,
    ];

    /**
     * Routes
     */
    public function routes()
    {
        \Route::api($this->name, $this->base_url, self::class, $this->route_options)
            ->acl('Public,Authorized');
    }

    /**
     * Register API (Simple)
     */
    public $postConcurrentCall_name = 'Concurrent Call';
    public $postConcurrentCall_description = 'Call callable concurrently.';
    public $postConcurrentCall_columns = [
        'funcs' => ['required' => true, 'name' => 'Functions', 'loc' => 'NF.Form.Functions', 'type' => 'json'],
        'token' => ['required' => true, 'domain' => 'token', 'name' => 'Token', 'validate_as_tokens' => ['sm.concurrent', 'sm.general'], 'skip_time_validation' => true],
    ];
    public $postConcurrentCall_result_danger = \Validator::RESULT_DANGER;
    public $postConcurrentCall_result_success = RESULT_SUCCESS;
    public function postConcurrentCall()
    {
        try {
            if (is_json($this->values['funcs'])) {
                $this->values['funcs'] = json_decode($this->values['funcs'], true);
            }
            // before
            foreach ($this->values['funcs']['before'] ?? [] as $v) {
                Reflection::runClosure($v['func'], $v['args']);
            }
            // funcs
            foreach ($this->values['funcs']['funcs'] ?? [] as $v) {
                $result = Reflection::runClosure($v['func'], $v['args']);
            }
            // after
            foreach ($this->values['funcs']['after'] ?? [] as $v) {
                Reflection::runClosure($v['func'], $v['args']);
            }
        } catch (\Exception $e) {
            return $this->finish(HTTPConstants::Status500InternalServerError, [
                'success' => false,
                'error' => [$e->getMessage()],
            ]);
        }
        // success
        return $this->finish(HTTPConstants::Status200OK, [
            'success' => true,
            'error' => [],
            'data' => $result,
        ]);
    }
}
