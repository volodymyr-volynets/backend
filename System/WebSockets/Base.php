<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\WebSockets;

use ElephantIO\Client;

class Base
{
    /**
     * @var string
     */
    private $web_socket_link;

    /**
     * @var array
     */
    private $options = [];

    /**
     * @var Client
     */
    private $client;

    /**
     * Constructor
     *
     * @param string $db_link
     * @param array $options
     */
    public function __construct(string $web_socket_link, array $options = [])
    {
        $this->web_socket_link = $web_socket_link;
        $this->options = $options;
    }

    /**
     * Connect to socket.io
     *
     * @param array $options
     * @return array
     */
    public function connect(array $options): array
    {
        $result = [
            'success' => false,
            'error' => [],
        ];
        $url = ($options['scheme'] ?? 'http') . '://' . $options['host'] . ':' . $options['port'] . $options['path'];
        $options2 = [
            'client' => (int) ($options['client_version'] ?? Client::CLIENT_4X),
            'context' => [
                'http' => [],
                'ssl' => [],
            ],
            //'transport' => 'websocket',
        ];
        $this->client = Client::create($url, $options2);
        try {
            $this->client->connect();
            $this->client->of($options['namespace'] ?? '/');
            $result['success'] = true;
        } catch (\Exception $e) {
            $result['error'][] = $e->getMessage();
        }
        return $result;
    }

    /**
     * Closes a connection
     *
     * @return array
     */
    public function close(): array
    {
        if ($this->client) {
            //$this->client->disconnect();
        }
        return [
            'success' => true,
            'error' => [],
        ];
    }

    /**
     * Send
     *
     * @param string $message
     * @param array $data
     * @param bool|null $ask
     * @return array
     */
    public function send(string $message, array $data = [], ?bool $ack = null): array
    {
        $result = [
            'success' => false,
            'error' => [],
        ];
        try {
            $this->client->emit($message, $data, $ack);
            $result['success'] = true;
        } catch (\Exception $e) {
            $result['error'][] = $e->getMessage();
        }
        return $result;
    }
}
