<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common;

use Object\Query\Builder;

class ETL
{
    /**
     * Extract transform load
     *
     * @param callable $extract
     * @param callable $transform
     * @param callable $load
     * @param array $options
     * @return array[]|array{data: array, error: array, success: bool}
     */
    public static function etl(callable $extract, callable $transform, callable $load, array $options = []): array
    {
        $result = [
            'success' => false,
            'error' => [],
            'count' => 0,
            'legend' => [],
        ];
        $rows = $extract($options);
        if ($rows instanceof Builder) {
            $rows = $rows->query()['rows'] ?? [];
        }
        $result['count'] = count($rows);
        foreach ($rows as $k => $v) {
            $new_row = $transform($v, $options);
            if ($new_row === false) {
                $result['legend'][] = 'Skipped transform for ' . $k;
                continue;
            }
            $new_load = $load($new_row, $options);
            if ($new_load === false) {
                $result['legend'][] = 'Skipped load for ' . $k;
            }
            $result['legend'][] = 'Success for ' . $k;
        }
        $result['success'] = true;
        return $result;
    }
}
