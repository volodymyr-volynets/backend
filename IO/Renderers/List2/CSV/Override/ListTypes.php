<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\IO\Renderers\List2\CSV\Override;

class ListTypes
{
    public $data = [
        'text/csv' => ['no_form_content_type_name' => 'CSV (Comma Delimited)', 'no_form_content_type_model' => '\Numbers\Backend\IO\Renderers\List2\CSV\Base', 'no_form_content_type_order' => -31000]
    ];
}
