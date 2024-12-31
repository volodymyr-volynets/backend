<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\IO\CSV;

use Object\Content\ImportFormats;

class Exports
{
    /**
     * Export
     *
     * @param string $format
     * @param array $data
     * @return array
     */
    public static function export(string $format, array $data): array
    {
        $result = [
            'success' => false,
            'error' => [],
            'data' => null
        ];
        $formats = ImportFormats::getStatic();
        $delimiter = $formats[$format]['delimiter'];
        $enclosure = $formats[$format]['enclosure'];
        // open file for writing
        $final = [];
        $outstream = fopen("php://temp", 'r+');
        foreach ($data as $sheet_name => $sheet_data) {
            fputcsv($outstream, ['(((Sheet)))', $sheet_name], $delimiter, $enclosure);
            foreach ($sheet_data as $k => $v) {
                fputcsv($outstream, $v, $delimiter, $enclosure);
            }
        }
        rewind($outstream);
        while (!feof($outstream)) {
            $final[] = fgets($outstream);
        }
        fclose($outstream);
        // result
        $result['success'] = true;
        $result['data'] = implode('', $final);
        return $result;
    }
}
