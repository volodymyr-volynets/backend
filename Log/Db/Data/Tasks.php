<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Log\Db\Data;

use Object\Import;

class Tasks extends Import
{
    public $data = [
        'tasks' => [
            'options' => [
                'pk' => ['ts_task_code'],
                'model' => '\Numbers\Users\TaskScheduler\Model\Collection\Tasks',
                'method' => 'save'
            ],
            'data' => [
                [
                    'ts_task_code' => 'SM::LOG_DB_TRUNCATE_LOGS',
                    'ts_task_name' => 'S/M Log Db Truncate Logs (Task)',
                    'ts_task_model' => '\Numbers\Backend\Log\Db\Task\TruncateLogs',
                    'ts_task_inactive' => 0,
                ],
            ],
        ],
    ];
}
