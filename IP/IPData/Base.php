<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\IP\IPData;

use Helper\HTTPRequest;

class Base extends \Numbers\Backend\IP\Common\Base
{
    /**
     * Get
     *
     * @param string $ip
     * @return array
     */
    public function get(string $ip): array
    {
        $result = [
            'success' => true,
            'error' => [],
            'data' => []
        ];
        $decoded = HTTPRequest::createStatic()
            ->url(str_replace('{IP}', $ip, \Application::get('ip.ipdata.url')))
            ->acceptable(HTTPRequest::Status200OK)
            ->retry(2, 1)
            ->get()
            ->jsonDecode(true)
            ->result();
        if (!$decoded['success']) {
            return $decoded;
        }
        // extract only needed fields
        foreach ([
            'postal' => 'postal',
            'country_code' => 'country_code',
            'province' => 'region',
            'city' => 'city',
            'latitude' => 'latitude',
            'longitude' => 'longitude',
        ] as $k => $v) {
            $result['data'][$k] = $decoded['data'][$v];
        }
        $result['data']['ip_provider'] = $decoded['data']['asn']['name'];
        $result['data']['ip_description'] = $result['data']['city'] . ', ' . $result['data']['province'] . ', ' . $result['data']['country_code'] . ', ' . $result['data']['postal'];
        return $result;
    }
}
