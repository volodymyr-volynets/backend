<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Log\Db\Task;

use Numbers\Users\TaskScheduler\Abstract2\Task;

class TruncateLogs extends Task
{
    public $task_code = 'SM::LOG_DB_TRUNCATE_LOGS';

    public function execute(array $parameters, array $options = []): array
    {
        $command = new \Numbers\Backend\Log\Db\Command\TruncateLogs([
            'tenant_id' => \Tenant::id(),
        ], [
            'skip_input_processing' => true,
        ]);
        return $command->runCurrentCommand();
    }
}
