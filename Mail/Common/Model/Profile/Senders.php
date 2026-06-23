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

use Object\Table;

class Senders extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Mail Profile Senders';
    public $name = 'sm_mail_profile_senders';
    public $pk = ['sm_mailprosndr_tenant_id', 'sm_mailprosndr_sm_mailprofile_id', 'sm_mailprosndr_id'];
    public $tenant = true;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_mailprosndr_';
    public $columns = [
        'sm_mailprosndr_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_mailprosndr_sm_mailprofile_id' => ['name' => 'Profile #', 'domain' => 'profile_id'],
        'sm_mailprosndr_id' => ['name' => 'Detail #', 'domain' => 'detail_id'],
        'sm_mailprosndr_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_mailprosndr_email' => ['name' => 'Email', 'domain' => 'email'],
        'sm_mailprosndr_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_mail_profile_senders_pk' => ['type' => 'pk', 'columns' => ['sm_mailprosndr_tenant_id', 'sm_mailprosndr_sm_mailprofile_id', 'sm_mailprosndr_id']],
        'sm_mailprosndr_sm_mailprofile_id_fk' => [
            'type' => 'fk',
            'columns' => ['sm_mailprosndr_tenant_id', 'sm_mailprosndr_sm_mailprofile_id'],
            'foreign_model' => '\Numbers\Backend\Mail\Common\Model\Profiles',
            'foreign_columns' => ['sm_mailprofile_tenant_id', 'sm_mailprofile_id'],
        ]
    ];
    public $indexes = [];
    public $history = false;
    public $audit = [];
    public $optimistic_lock = false;
    public $options_map = [
        'sm_mailprosndr_name' => 'name',
        'sm_mailprosndr_email' => 'name',
        'sm_mailprosndr_inactive' => 'inactive'
    ];
    public $options_active = [
        'sm_mailprosndr_inactive' => 0
    ];
    public $engine = [
        'MySQLi' => 'InnoDB'
    ];

    public $cache = false;
    public $cache_tags = [];
    public $cache_memory = false;

    public $data_asset = [
        'classification' => 'proprietary',
        'protection' => 1,
        'scope' => 'global'
    ];
}
