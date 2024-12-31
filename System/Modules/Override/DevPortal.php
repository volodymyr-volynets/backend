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

class DevPortal
{
    public $data = [
        'Links' => [
            'Global' => [
                'Run PHP Code' => [
                    'url' => '/Numbers/Backend/System/Modules/Controller/DevPortal/_PHP',
                    'name' => 'Run PHP Code',
                    'icon' => 'fab fa-free-code-camp'
                ],
                'Run SQL Code' => [
                    'url' => '/Numbers/Backend/System/Modules/Controller/DevPortal/_PostgreSQL',
                    'name' => 'Run SQL Code',
                    'icon' => 'fab fa-codepen'
                ]
            ]
        ]
    ];
}
