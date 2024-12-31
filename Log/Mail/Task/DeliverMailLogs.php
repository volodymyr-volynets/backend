<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Log\Mail\Task;

use Numbers\Users\TaskScheduler\Abstract2\Task;

class DeliverMailLogs extends Task
{
    public $task_code = 'SM::LOG_MAIL_DELIVER_LOGS';

    public function execute(array $parameters, array $options = []): array
    {
        $command = new \Numbers\Backend\Log\Mail\Command\DeliverMailLogs([
            'tenant_id' => \Tenant::id(),
        ]);
        return $command->runCurrentCommand();
    }
}
