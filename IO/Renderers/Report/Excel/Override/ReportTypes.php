<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\IO\Renderers\Report\Excel\Override;

class ReportTypes
{
    public $data = [
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => ['no_report_content_type_name' => 'Microsoft Excel Workbook', 'no_report_content_type_model' => '\Numbers\Backend\IO\Renderers\Report\Excel\Base', 'no_report_content_type_order' => -30000]
    ];
}
