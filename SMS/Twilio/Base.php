<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\SMS\Twilio;

use Twilio\Rest\Client;

class Base extends \Numbers\Backend\SMS\Common\Base implements \Numbers\Backend\SMS\Common\Interface2\Base
{
    /**
     * Send an SMS
     *
     * @param array $options
     * @return array
     */
    public function send(array $options): array
    {
        $result = [
            'success' => false,
            'error' => [],
            'data' => []
        ];
        try {
            // overridde phone
            $debug = \Application::get('debug.debug');
            if (!empty($debug) || \Application::get('debug.override_phone')) {
                $options['to'] = \Application::get('debug.override_phone');
            }
            $twilio = new Client($options['settings']['account_sid'], $options['settings']['auth_token']);
            $message = $twilio->messages->create(
                $options['to'],
                [
                    "from" => $options['settings']['from'],
                    "body" => $options['message'],
                ]
            );
            $result['data'] = $message->toArray();
            $result['success'] = true;
        } catch (\Exception $e) {
            $result['error'][] = $e->getMessage();
        }
        return $result;
    }
}
