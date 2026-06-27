<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Log\Db\Command;

use Numbers\Backend\System\ShellCommand\Class2\ShellCommands;
use Numbers\Backend\Log\Db\Model\Logs;
use Numbers\Backend\Log\Common\Base;
use Numbers\Users\Users\Helper\Notification\Sender;
use Numbers\Backend\Log\Mail\Helper\Notifications;
use Numbers\Backend\Configuration\Db\Helper\ConfigurationValues;

class TruncateLogs extends ShellCommands
{
    public $code = 'SM::LOG_DB_TRUNCATE_LOGS';
    public $name = 'S/M Log Db Truncate Logs (Command)';
    public $command = 'sm_log_db_truncate_logs';
    public $columns = [
        'tenant_id' => ['required' => true, 'name' => 'Tenant #', 'domain' => 'tenant_id'],
    ];

    public function execute(array $parameters, array $options = []): array
    {
        $now = \Format::now('datatime');
        $last_truncated = ConfigurationValues::get(\Tenant::id(), '*', 'config.db.log.email.last_truncated');
        if (!$last_truncated) {
            $last_truncated = '2026-01-01 00:00:00';
        } else {
            $last_truncated = json_decode($last_truncated);
        }
        ConfigurationValues::set(\Tenant::id(), '*', 'config.db.log.email.last_truncated', $now, 0);
        // compute 30 days in the past
        $delete_before = date('Y-m-d 00:00:00', strtotime($now) - (30 * 24 * 60 * 60));
        /** @var Logs $model */
        $model = \Factory::model('\Numbers\Backend\Log\Db\Model\LogsGeneratedYear' . date('Y', strtotime($delete_before)), false);
        return $model->queryBuilder()
            ->delete()
            ->whereMultiple('AND', [
                'sm_log_tenant_id;IN' => [0, $parameters['tenant_id']], // some events does not have tenant
                'sm_log_inserted_timestamp;<=' => $delete_before,
            ])
            ->query();
    }
}
