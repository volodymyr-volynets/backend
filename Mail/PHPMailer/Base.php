<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Mail\PHPMailer;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Base extends \Numbers\Backend\Mail\Common\Base implements \Numbers\Backend\Mail\Common\Interface2\Base
{
    /**
     * Send an email
     *
     * @param array $options
     * @return array
     */
    public function send(array $options): array
    {
        $result = [
            'success' => false,
            'error' => [],
            'unique_id' => null
        ];
        $calendar_invite = !empty($options['calendar_invite']);
        $calendar_message = $options['calendar_message'] ?? '';
        // see if we need to validate
        if (empty($options['validated'])) {
            $temp = $this->validate($options);
            if (!$temp['success']) {
                return $temp;
            } elseif (!empty($temp['data']['requires_fetching'])) {
                // we error if we require fetching from database
                $result['error'][] = 'Fetching of email addresses is required!';
                return $result;
            } else {
                $options = $temp['data'];
            }
        }
        // to, cc, bcc
        $recepients = [];
        foreach (['to', 'cc', 'bcc'] as $r) {
            $recepients[$r] = [];
            foreach ($options[$r] as $v) {
                // todo: add recepient name here
                $recepients[$r][] = $v['email'];
            }
            $recepients[$r] = implode(',', $recepients[$r]);
        }
        // create PHPMailer object and prepare for sending
        require \Application::get('application.path_full') . '../libraries/vendor/autoload.php';
        $mail = new PHPMailer();
        $mail->CharSet = $options['charset'] ?? 'UTF-8';
        // overrides for non production environments
        $environment = \Application::get('environment');
        $debug = \Application::get('debug.debug');
        if (!empty($debug) || \Application::get('debug.override_email')) {
            // special headers
            foreach ($recepients as $k => $v) {
                if (!empty($v)) {
                    $mail->addCustomHeader('X-Original-' . ucfirst($k), $v);
                }
            }
            unset($recepients);
            $recepients['to'] = \Application::get('debug.override_email') ?? \Application::get('debug.email');
            // prepend environment to subject
            if (strpos($options['subject'], '[' . $environment . ']') === false) {
                $options['subject'] = '[' . $environment . '] ' . $options['subject'];
            }
        }
        // smtp
        $smtp = \Application::get('flag.global.mail.delivery.smtp');
        if (!empty($smtp)) {
            // if we are debuging
            if (\Application::get('debug.mail_smtp')) {
                $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            }
            $mail->isSMTP();
            $mail->Host = $smtp['host'];
            $mail->Port = $smtp['port'];
            if (!empty($smtp['auth'])) {
                $mail->SMTPAuth = true;
                $mail->Username = $smtp['username'];
                $mail->Password = $smtp['password'];
            }
            $mail->SMTPSecure = $smtp['secure'] ?? PHPMailer::ENCRYPTION_STARTTLS;
        }
        // fetch mail settings
        $options = array_merge_hard(\Application::get('flag.global.mail') ?? [], $options);
        // process from
        $mail->setFrom($options['from']['email'], $options['from']['name'] ?? '');
        $mail->addReplyTo($options['from']['email'], $options['from']['name'] ?? '');
        // organization
        if (!empty($options['from']['organization'])) {
            $mail->addCustomHeader('Organization', $options['from']['organization']);
        }
        $mail->Subject = $options['subject'];
        // to, cc, bcc
        foreach (explode(',', $recepients['to']) as $v) {
            $mail->addAddress($v);
        }
        if (!empty($recepients['bcc'])) {
            foreach (explode(',', $recepients['bcc']) as $v) {
                $mail->addBCC($v);
            }
        }
        if (!empty($recepients['cc'])) {
            foreach (explode(',', $recepients['cc']) as $v) {
                $mail->addCC($v);
            }
        }
        $mail->addCustomHeader('Errors-To', $options['from']['email']);
        // important
        if (!empty($options['important'])) {
            $mail->Priority = 1;
        }
        // body
        if ($calendar_invite) {
            $mail->Body = $calendar_message;
            $mail->ContentType = 'text/calendar; name=event.ics; method=REQUEST; charset=UTF-8';
            //$mail->addCustomHeader('MIME-version', '1.0');
            //$mail->addCustomHeader('Content-type', 'text/calendar; name=event.ics; method=REQUEST; charset=UTF-8');
            $mail->addCustomHeader('Content-Transfer-Encoding', '7bit');
            $mail->addCustomHeader('X-Mailer', 'Microsoft Office Outlook 12.0');
            $mail->addCustomHeader('Content-class: urn:content-classes:calendarmessage');
        } elseif (!empty($options['is_html'])) {
            $mail->WordWrap = 50;
            $mail->isHTML(true);
            $mail->AltBody = $options['message'][0]['data'];
            $mail->Body = $options['message'][1]['data'];
        } else {
            $mail->WordWrap = 50;
            $mail->Body = $options['message'][0]['data'];
        }
        // attachments
        if (!empty($options['attachments'])) {
            foreach ($options['attachments'] as $k => $v) {
                if (!empty($v['data'])) {
                    $mail->addStringAttachment($v['data'], $v['name'], $mail::ENCODING_BASE64, $v['type']);
                }
            }
        }
        // trying to deliver
        try {
            $mail->send();
            $result['success'] = true;
        } catch (\Exception $e) {
            $result['error'][] = 'Could not deliver mail! ' . $mail->ErrorInfo;
            error_log('PHPMailer error: ' . $mail->ErrorInfo, 0);
        }
        return $result;
    }
}
