<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\IP\Simple;

use Numbers\Backend\IP\Simple\Model\IPv4;

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
            'success' => false,
            'error' => [],
            'data' => []
        ];
        // convert IP to long
        $ip_long = ip2long($ip);
        // fetch it from model
        if (!empty($ip_long)) {
            $data = IPv4::getStatic([
                'where' => [
                    'sm_ipver4_start;<=' => $ip_long,
                    'sm_ipver4_end;>=' => $ip_long
                ],
                'pk' => null,
            ]);
            if (!empty($data[0])) {
                foreach (['country_code', 'province', 'city', 'latitude', 'longitude'] as $v) {
                    if (!empty($data[0]['sm_ipver4_' . $v])) {
                        $result['data'][$v] = $data[0]['sm_ipver4_' . $v];
                    }
                }
                $result['success'] = true;
            }
        } else {
            $result['error'][] = 'Could not decode IP address!';
        }
        return $result;
    }
}
