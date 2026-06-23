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
use Numbers\Backend\SMS\Common\Model\Profiles;

class Base extends \Numbers\Backend\SMS\Common\Base implements \Numbers\Backend\SMS\Common\Interface2\Base
{
    /**
     * Cached profiles
     *
     * @var array
     */
    protected static $cached_profiles = [];

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
            // override phone
            $debug = \Application::get('debug.debug');
            if (!empty($debug) || \Application::get('debug.override_phone')) {
                $options['to'] = \Application::get('debug.override_phone');
            }
            // profile
            if (!empty($options['profile'])) {
                if (isset(self::$cached_profiles[$options['profile']])) {
                    $profile = self::$cached_profiles[$options['profile']];
                } else {
                    $profile = self::$cached_profiles[$options['profile']] = Profiles::getSingleStatic([
                        'where' => [
                            'sm_smsprofile_tenant_id' => \Tenant::id(),
                            'sm_smsprofile_id' => (int) $options['profile'],
                        ]
                    ]);
                }
                $options['settings']['account_sid'] = $profile['sm_smsprofile_account_sid'];
                $options['settings']['auth_token'] = $profile['sm_smsprofile_auth_token'];
            }
            if (isset($options['sender_phone'])) {
                $options['settings']['from'] = $options['sender_phone'];
            }
            // prefix
            $prefix = '';
            if (!empty($options['whatsapp'])) {
                $prefix = 'whatsapp:';
            }
            $options['settings']['from'] = '+' . ltrim($options['settings']['from'], '+');
            $options['to'] = '+' . ltrim($options['to'], '+');
            // create client
            $twilio = new Client($options['settings']['account_sid'], $options['settings']['auth_token']);
            $params = [
                'from' => $prefix . $options['settings']['from'],
                'body' => $options['message'],
            ];
            if (isset($options['media'])) {
                $params['mediaUrl'] = $options['media'];
            }
            if (!empty($options['whatsapp'])) {
                $params['contentSid'] = $options['content_sid'];
                $params['contentVariables'] = json_encode($options['content_variables']);
            }
            $message = $twilio->messages->create(
                $prefix . $options['to'],
                $params
            );
            $result['data'] = $message->toArray();
            $result['success'] = true;
        } catch (\Exception $e) {
            $result['error'][] = $e->getMessage();
        }
        return $result;
    }
}
