<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Log\Common\Middleware;

use Object\Middleware;

class LogAfterErrors extends Middleware
{
    /**
     * Run
     *
     * @param \Request $request
     * @param mixed $response
     * @param array $options
     * @return bool|array
     */
    public function run(\Request $request, mixed $response, array $options = []): bool|array
    {
        return [
            'success' => true,
            'error' => [],
        ];
    }
}
