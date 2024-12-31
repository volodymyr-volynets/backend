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

class Notifications extends Import
{
    public $data = [
        'features' => [
            'options' => [
                'pk' => ['sm_feature_code'],
                'model' => '\Numbers\Backend\System\Modules\Model\Collection\Module\Features',
                'method' => 'save'
            ],
            'data' => [
                [
                    'sm_feature_module_code' => 'SM',
                    'sm_feature_code' => 'SM::EMAIL_LOG_DELIVER_MAIL_LOGS',
                    'sm_feature_type' => 21,
                    'sm_feature_name' => 'U/M Email Log Deliver Logs',
                    'sm_feature_icon' => 'far fa-envelope',
                    'sm_feature_activated_by_default' => 1,
                    'sm_feature_activation_model' => null,
                    'sm_feature_inactive' => 0
                ],
            ]
        ],
        'notifications' => [
            'options' => [
                'pk' => ['sm_notification_code'],
                'model' => '\Numbers\Backend\System\Modules\Model\Notifications',
                'method' => 'save'
            ],
            'data' => [
                [
                    'sm_notification_code' => 'SM::EMAIL_LOG_DELIVER_MAIL_LOGS',
                    'sm_notification_name' => 'U/M Email Log Deliver Logs',
                    'sm_notification_subject' => 'New Logs [count_notifications], [count_errors]',
                    'sm_notification_body' => '',
                    'sm_notification_important' => 1,
                    'sm_notification_email_model_code' => '\Numbers\Backend\Log\Mail\Email\LogMailDeliverLogs',
                    'sm_notification_inactive' => 0
                ],
            ]
        ],
    ];
}
