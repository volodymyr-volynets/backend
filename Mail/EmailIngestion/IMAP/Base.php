<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Mail\EmailIngestion\IMAP;

use Helper\Date;

class Base
{
    /**
     * Resource
     *
     * @var resource
     */
    private $resource;

    /**
     * Constructor
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
    }

    /**
     * Connect
     *
     * @param array $options
     * @return array
     */
    public function connect(array $options): array
    {
        $result = [
            'success' => false,
            'error' => []
        ];
        $this->resource = imap_open($options['server'], $options['username'], $options['password'], 0, 3);
        if ($this->resource === false) {
            $result['error'] = imap_errors();
        } else {
            $result['success'] = true;
        }
        return $result;
    }

    /**
     * Close
     *
     * @return array
     */
    public function close(): array
    {
        if (imap_close($this->resource)) {
            return ['success' => true, 'error' => []];
        } else {
            return ['success' => false, 'error' => imap_errors()];
        }
    }

    /**
     * Get headers
     *
     * @param string $start_date
     * @param string $end_date
     * @return array
     */
    public function getHeaders(string $start_date, string $end_date, array $options = []): array
    {
        $result = [
            'success' => false,
            'error' => [],
            'data' => []
        ];
        $unread = '';
        if (!empty($options['unread_only'])) {
            $unread = ' UNSEEN ';
        }
        $recent_messages = @imap_search($this->resource, $unread . 'BEFORE "' . date("j F Y", strtotime(Date::addInterval($start_date, '+1 day', 'Y-m-d'))) . '" SINCE "' . date("j F Y", strtotime($end_date)) . '"', SE_UID);
        if ($recent_messages !== false) {
            $result['data'] = imap_fetch_overview($this->resource, implode(',', array_slice($recent_messages, 0, 5000)), FT_UID);
            $result['success'] = true;
        }
        return $result;
    }

    /**
     * Get message
     *
     * @param mixed $msgno
     * @return array
     */
    public function getMessage($msgno, $part = '1.2'): array
    {
        $result = [];
        $header = imap_headerinfo($this->resource, $msgno);
        $result['subject'] = imap_utf8($header->subject);
        $result['fromaddress'] = $header->fromaddress;
        $result['toaddress'] = $header->toaddress ?? null;
        $result['date'] = $header->date;
        $result['udate'] = $header->udate;
        $result['body'] = imap_fetchbody($this->resource, $msgno, 2);
        if (empty($result['body'])) {
            $result['body'] = imap_fetchbody($this->resource, $msgno, 1.2);
            if (empty($result['body'])) {
                $result['body'] = imap_fetchbody($this->resource, $msgno, 1.1);
                if (empty($result['body'])) {
                    $result['body'] = imap_fetchbody($this->resource, $msgno, 1);
                }
            }
        }
        $result['body1'] = @imap_fetchbody($this->resource, $msgno, 1);
        if (isset($result['body1'])) {
            $result['body1'] = imap_qprint($result['body1']);
        }
        // see if content is base64 encoded
        $decoded = imap_base64($result['body']);
        if ($decoded !== false) {
            $result['body'] = $decoded;
            return $result;
        }
        $result['body'] = imap_qprint($result['body']);
        return $result;
    }
}
