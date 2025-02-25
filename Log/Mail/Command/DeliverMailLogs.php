<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Log\Mail\Command;

use Numbers\Backend\System\ShellCommand\Class2\ShellCommands;
use Numbers\Backend\Log\Db\Model\Logs;
use Numbers\Backend\Log\Common\Base;
use Numbers\Users\Users\Helper\Notification\Sender;
use Numbers\Backend\Log\Mail\Helper\Notifications;

class DeliverMailLogs extends ShellCommands
{
    public $code = 'SM::LOG_MAIL_DELIVER_LOGS';
    public $name = 'S/M Log Mail Deliver Logs (Command)';
    public $command = 'sm_log_mail_deliver';
    public $columns = [
        'tenant_id' => ['required' => true, 'name' => 'Tenant #', 'domain' => 'tenant_id'],
    ];

    public function execute(array $parameters, array $options = []): array
    {
        /** @var Logs $model */
        $model = \Factory::model('\Numbers\Backend\Log\Db\Model\LogsGeneratedYear' . date('Y'), false);
        $model->begin();
        $pivot_groupped = $model->queryBuilder()
            ->select()
            ->columns([
                'type' => 'sm_log_type',
                'message' => 'sm_log_message',
                'counter' => 'COUNT(*)'
            ])
            ->whereMultiple('AND', [
                'sm_log_tenant_id' => $parameters['tenant_id'],
                'sm_log_mail_sent' => 0,
            ])
            ->groupby(['type', 'message'])
            ->orderby(['counter' => SORT_DESC, 'type' => SORT_ASC, 'message' => SORT_ASC])
            ->array2(null, ['plain_array' => true]);
        // if we do not have anything in a pivot we exit
        $pivot_errors = $model->queryBuilder()
            ->select()
            ->columns([
                'type' => 'sm_log_type',
                'message' => 'sm_log_message',
                'other' => 'sm_log_other',
                'duration' => 'AVG(sm_log_duration)',
                'counter' => 'COUNT(*)',
            ])
            ->whereMultiple('AND', [
                'sm_log_tenant_id' => $parameters['tenant_id'],
                'sm_log_mail_sent' => 0,
                'sm_log_type' => Base::ERROR_TYPES,
            ])
            ->groupby(['type', 'message', 'other'])
            ->orderby(['counter' => SORT_DESC, 'type' => SORT_ASC, 'message' => SORT_ASC, 'other' => SORT_ASC])
            ->array2(null, ['plain_array' => true]);
        // update email column
        $model->queryBuilder()
            ->update()
            ->set([
                'sm_log_mail_sent' => 1,
            ])
            ->whereMultiple('AND', [
                'sm_log_tenant_id' => $parameters['tenant_id'],
                'sm_log_mail_sent' => 0,
            ])
            ->query();
        $model->commit();
        // send messages outsside ot transaction
        foreach (Sender::prepareListOfEmails(\Application::get('log.email.email')) as $v) {
            Notifications::sendLogDeliveryEmail($v['um_user_id'], $v['um_user_email'], $pivot_groupped, $pivot_errors);
        }
        return ['success' => true, 'error' => []];
    }
}
