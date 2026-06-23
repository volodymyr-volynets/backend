<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\SMS\Common\Model;

use Object\Table;

class Profiles extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M SMS Profiles';
    public $name = 'sm_sms_profiles';
    public $pk = ['sm_smsprofile_tenant_id', 'sm_smsprofile_id'];
    public $tenant = true;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_smsprofile_';
    public $columns = [
        'sm_smsprofile_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_smsprofile_id' => ['name' => 'Profile #', 'domain' => 'profile_id_sequence'],
        'sm_smsprofile_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_smsprofile_sm_smsproftype_code' => ['name' => 'Type', 'domain' => 'group_code', 'options_model' => '\Numbers\Backend\SMS\Common\Model\Profile\ProfileTypes'],
        'sm_smsprofile_account_sid' => ['name' => 'Account SID', 'domain' => 'encrypted_password'],
        'sm_smsprofile_auth_token' => ['name' => 'Auth Token', 'domain' => 'encrypted_password'],
        'sm_smsprofile_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_sms_profiles_pk' => ['type' => 'pk', 'columns' => ['sm_smsprofile_tenant_id', 'sm_smsprofile_id']],
    ];
    public $indexes = [];
    public $history = false;
    public $audit = [
        'map' => [
            'sm_smsprofile_tenant_id' => 'wg_audit_tenant_id',
            'sm_smsprofile_id' => 'wg_audit_sm_smsprofile_id'
        ]
    ];
    public $optimistic_lock = true;
    public $options_map = [
        'sm_smsprofile_name' => 'name',
        'sm_smsprofile_inactive' => 'inactive'
    ];
    public $options_active = [
        'sm_smsprofile_inactive' => 0
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
