<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Model\Form;

use Object\Table;

class Updates extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Form Updates';
    public $name = 'sm_form_updates';
    public $pk = ['sm_frmupdate_tenant_id', 'sm_frmupdate_id'];
    public $tenant = true;
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_frmupdate_';
    public $columns = [
        'sm_frmupdate_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_frmupdate_id' => ['name' => 'Form Update #', 'domain' => 'big_id_sequence'],
        'sm_frmupdate_form_code' => ['name' => 'Form Code', 'domain' => 'code'],
        'sm_frmupdate_form_pk' => ['name' => 'Form Pk', 'domain' => 'code', 'null' => true],
        'sm_frmupdate_subform_pk' => ['name' => 'Subform Pk', 'domain' => 'code', 'null' => true],
        'sm_frmupdate_operation_name' => ['name' => 'Operation Name', 'domain' => 'name', 'null' => true],
        'sm_frmupdate_operation_details' => ['name' => 'Operation Details', 'type' => 'text', 'null' => true],
        'sm_frmupdate_message' => ['name' => 'Message', 'type' => 'json', 'null' => true],
        'sm_frmupdate_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
    ];
    public $constraints = [
        'sm_form_updates_pk' => ['type' => 'pk', 'columns' => ['sm_frmupdate_tenant_id', 'sm_frmupdate_id']],
    ];
    public $indexes = [];
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

    public $who = [
        'inserted' => true,
    ];

    public $data_asset = [
        'classification' => 'public',
        'protection' => 0,
        'scope' => 'global'
    ];
}
