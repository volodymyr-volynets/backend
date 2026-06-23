<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Mail\Common\Model\Profile;

use Object\Data;

class ProfileTypes extends Data
{
    public $module_code = 'SM';
    public $title = 'S/M Mail Profile Types';
    public $column_key = 'sm_mailproftype_code';
    public $column_prefix = 'sm_mailproftype_';
    public $orderby = [
        'sm_mailproftype_order' => SORT_ASC,
    ];
    public $columns = [
        'sm_mailproftype_code' => ['name' => 'Type', 'domain' => 'group_code'],
        'sm_mailproftype_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_mailproftype_model' => ['name' => 'Model', 'domain' => 'model'],
        'sm_mailproftype_order' => ['name' => 'Order', 'domain' => 'order'],
    ];
    public $data = [
        'SMTP_PHPMAILER' => ['sm_mailproftype_name' => 'PHP Mailer (SMTP)', 'sm_mailproftype_model' => '\Numbers\Backend\Mail\PHPMailer\Base', 'sm_mailproftype_order' => 1000],
    ];
}
