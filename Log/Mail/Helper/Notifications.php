<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Log\Mail\Helper;

use Numbers\Users\Users\Helper\Notification\Sender;

class Notifications
{
    /**
     * Send log delivery email
     *
     * @param int $um_user_id
     * @param string $message
     * @param string $occasion
     * @param string $success_url
     * @return array
     */
    public static function sendLogDeliveryEmail(int $um_user_id, string $um_user_email, \Array2 $pivot, \Array2 $errors): array
    {
        return Sender::notifySingleUser('SM::EMAIL_LOG_DELIVER_MAIL_LOGS', $um_user_id, $um_user_email, [
            'form' => [
                'input' => [
                    'um_user_id' => $um_user_id,
                    'um_user_email' => $um_user_email,
                    'pivot' => $pivot->toHTML(['Type', 'Message', 'Counter'], ['trim_columns' => 100]),
                    'errors' => $errors->toHTML(['Type', 'Message', 'Other', 'Duration', 'Counter'], ['trim_columns' => 100]),
                ],
            ],
            'replace' => [
                'subject' => [
                    '[count_notifications]' => $pivot->count(),
                    '[count_errors]' => $errors->count(),
                ],
            ],
        ]);
    }
}
