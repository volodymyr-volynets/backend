<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\IO\Renderers\List2\Excel\Override;

class ListTypes
{
    public $data = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['no_form_content_type_name' => 'Microsoft Excel Workbook', 'no_form_content_type_model' => '\Numbers\Backend\IO\Renderers\List2\Excel\Base', 'no_form_content_type_order' => -30000]
    ];
}
