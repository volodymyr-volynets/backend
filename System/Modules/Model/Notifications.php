<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model;

use Object\Table;

class Notifications extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Notifications';
    public $name = 'sm_notifications';
    public $pk = ['sm_notification_code'];
    public $tenant = false;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_notification_';
    public $columns = [
        'sm_notification_code' => ['name' => 'Code', 'domain' => 'feature_code'],
        'sm_notification_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_notification_subject' => ['name' => 'Subject', 'type' => 'text'],
        'sm_notification_body' => ['name' => 'Body', 'type' => 'text', 'null' => true],
        'sm_notification_important' => ['name' => 'Important', 'type' => 'boolean'],
        'sm_notification_email_model_code' => ['name' => 'Email Model', 'domain' => 'code', 'null' => true],
        'sm_notification_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_notifications_pk' => ['type' => 'pk', 'columns' => ['sm_notification_code']],
        'sm_notification_code_fk' => [
            'type' => 'fk',
            'columns' => ['sm_notification_code'],
            'foreign_model' => '\Numbers\Backend\System\Modules\Model\Module\Features',
            'foreign_columns' => ['sm_feature_code']
        ]
    ];
    public $history = false;
    public $audit = false;
    public $optimistic_lock = false;
    public $options_map = [];
    public $options_active = [];
    public $engine = [
        'MySQLi' => 'InnoDB'
    ];

    public $cache = false;
    public $cache_tags = [];
    public $cache_memory = false;

    public $data_asset = [
        'classification' => 'public',
        'protection' => 1,
        'scope' => 'global'
    ];
}
