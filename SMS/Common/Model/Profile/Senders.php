<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\SMS\Common\Model\Profile;

use Object\Table;

class Senders extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M SMS Profile Senders';
    public $name = 'sm_sms_profile_senders';
    public $pk = ['sm_smsprosndr_tenant_id', 'sm_smsprosndr_sm_smsprofile_id', 'sm_smsprosndr_id'];
    public $tenant = true;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_smsprosndr_';
    public $columns = [
        'sm_smsprosndr_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_smsprosndr_sm_smsprofile_id' => ['name' => 'Profile #', 'domain' => 'profile_id'],
        'sm_smsprosndr_id' => ['name' => 'Detail #', 'domain' => 'detail_id'],
        'sm_smsprosndr_name' => ['name' => 'Name', 'domain' => 'name'],
        'sm_smsprosndr_phone' => ['name' => 'Phone', 'domain' => 'phone'],
        'sm_smsprosndr_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_sms_profile_senders_pk' => ['type' => 'pk', 'columns' => ['sm_smsprosndr_tenant_id', 'sm_smsprosndr_sm_smsprofile_id', 'sm_smsprosndr_id']],
        'sm_smsprosndr_sm_smsprofile_id_fk' => [
            'type' => 'fk',
            'columns' => ['sm_smsprosndr_tenant_id', 'sm_smsprosndr_sm_smsprofile_id'],
            'foreign_model' => '\Numbers\Backend\SMS\Common\Model\Profiles',
            'foreign_columns' => ['sm_smsprofile_tenant_id', 'sm_smsprofile_id'],
        ]
    ];
    public $indexes = [];
    public $history = false;
    public $audit = [];
    public $optimistic_lock = false;
    public $options_map = [
        'sm_smsprosndr_name' => 'name',
        'sm_smsprosndr_phone' => 'name',
        'sm_smsprosndr_inactive' => 'inactive'
    ];
    public $options_active = [
        'sm_smsprosndr_inactive' => 0
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
