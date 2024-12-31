<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Log\Mail\Data;

use Object\Import;

class Commands extends Import
{
    public $data = [
        'tasks' => [
            'options' => [
                'pk' => ['sm_shellcommand_code'],
                'model' => '\Numbers\Backend\System\ShellCommand\Model\ShellCommands',
                'method' => 'save'
            ],
            'data' => [
                [
                    'sm_shellcommand_code' => 'SM::LOG_MAIL_DELIVER_LOGS',
                    'sm_shellcommand_name' => 'S/M Log Mail Deliver Logs (Command)',
                    'sm_shellcommand_description' => 'Use this command to send mail logs.',
                    'sm_shellcommand_model' => '\Numbers\Backend\Log\Mail\Command\DeliverMailLogs',
                    'sm_shellcommand_command' => 'sm_log_mail_deliver',
                    'sm_shellcommand_module_code' => 'SM',
                    'sm_shellcommand_inactive' => 0,
                ],
            ],
        ],
    ];
}
