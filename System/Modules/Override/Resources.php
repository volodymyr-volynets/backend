<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Override;

class Resources
{
    public $data = [
        'actions' => [
            'primary' => [
                'datasource' => '\Numbers\Backend\System\Modules\DataSource\Resource\Actions'
            ]
        ],
        'flags' => [
            'primary' => [
                'datasource' => '\Numbers\Backend\System\Modules\DataSource\Flags'
            ]
        ]
    ];
}
