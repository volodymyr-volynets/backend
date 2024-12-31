<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\IO\CSV\Override;

class Exports
{
    public $data = [
        'csv' => [
            'name' => 'CSV (Comma Delimited)',
            'model' => '\Numbers\Backend\IO\CSV\Exports',
            'delimiter' => ',',
            'enclosure' => '"',
            'extension' => 'csv',
            'content_type' => 'application/octet-stream'
        ],
        'txt' => [
            'name' => 'Text (Tab Delimited)',
            'model' => '\Numbers\Backend\IO\CSV\Exports',
            'delimiter' => "\t",
            'enclosure' => '"',
            'extension' => 'txt',
            'content_type' => 'application/octet-stream'
        ]
    ];
}
