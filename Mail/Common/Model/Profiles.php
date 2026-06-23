<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Mail\Common\Model;

use Object\Table;

class Profiles extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Mail Profiles';
    public $name = 'sm_mail_profiles';
    public $pk = ['sm_mailprofile_tenant_id', 'sm_mailprofile_id'];
    public $tenant = true;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_mailprofile_';
    public $columns = [
        'sm_mailprofile_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_mailprofile_id' => ['name' => 'Profile #', 'domain' => 'profile_id_sequence'],
        'sm_mailprofile_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_mailprofile_sm_mailproftype_code' => ['name' => 'Type', 'domain' => 'group_code', 'options_model' => '\Numbers\Backend\Mail\Common\Model\Profile\ProfileTypes'],
        // domain
        'sm_mailprofile_host' => ['name' => 'Host', 'domain' => 'host2'],
        'sm_mailprofile_port' => ['name' => 'Port', 'domain' => 'port'],
        'sm_mailprofile_auth' => ['name' => 'Auth', 'type' => 'boolean'],
        'sm_mailprofile_secure' => ['name' => 'Secure', 'domain' => 'code', 'null' => true],
        'sm_mailprofile_username' => ['name' => 'Username', 'domain' => 'encrypted_password'],
        'sm_mailprofile_password' => ['name' => 'Password', 'domain' => 'encrypted_password'],
        // other
        'sm_mailprofile_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_mail_profiles_pk' => ['type' => 'pk', 'columns' => ['sm_mailprofile_tenant_id', 'sm_mailprofile_id']],
    ];
    public $indexes = [];
    public $history = false;
    public $audit = [
        'map' => [
            'sm_mailprofile_tenant_id' => 'wg_audit_tenant_id',
            'sm_mailprofile_id' => 'wg_audit_sm_mailprofile_id'
        ]
    ];
    public $optimistic_lock = true;
    public $options_map = [
        'sm_mailprofile_name' => 'name',
        'sm_mailprofile_inactive' => 'inactive'
    ];
    public $options_active = [
        'sm_mailprofile_inactive' => 0
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
