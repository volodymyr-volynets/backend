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
        // need to grab first occurrence of the trace
        $errors = $errors->map(function ($v) {
            if (!empty($v[5]) && is_array($v[5])) {
                $v[5] = json_decode((new \Json2($v[5][0]))->toJSON(), false);
                $v[5] = implode('<br/>', $v[5] ?? []);
            }
            return $v;
        });
        return Sender::notifySingleUser('SM::EMAIL_LOG_DELIVER_MAIL_LOGS', $um_user_id, $um_user_email, [
            'form' => [
                'input' => [
                    'um_user_id' => $um_user_id,
                    'um_user_email' => $um_user_email,
                    'pivot' => $pivot->toHTML(['Type', 'Message', 'Counter'], ['trim_columns' => 100]),
                    'errors' => $errors->toHTML([
                        'Type', 'Message', 'Other', 'Duration', 'Counter'
                    ], [
                        'trim_columns' => 100,
                        'new_line_columns' => ['trace' => ['name' => 'Trace', 'position' => 5]],
                    ]),
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
