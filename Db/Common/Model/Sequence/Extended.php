<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common\Model\Sequence;

use Object\Table;

class Extended extends Table
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'S/M Sequence (Extended)';
    public $schema;
    public $name = 'sm_sequence_extended';
    public $pk = ['sm_sequence_name', 'sm_sequence_tenant_id', 'sm_sequence_module_id'];
    public $orderby;
    public $limit;
    public $column_prefix = 'sm_sequence_';
    public $columns = [
        'sm_sequence_name' => ['name' => 'Name', 'domain' => 'code'],
        'sm_sequence_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
        'sm_sequence_module_id' => ['name' => 'Module #', 'domain' => 'module_id'],
        'sm_sequence_description' => ['name' => 'Description', 'domain' => 'description', 'null' => true],
        // common attributes
        'sm_sequence_type' => ['name' => 'Type', 'domain' => 'type_code', 'options_model' => '\Numbers\Backend\Db\Common\Model\Sequence\Types'],
        'sm_sequence_prefix' => ['name' => 'Prefix', 'type' => 'varchar', 'length' => 15, 'null' => true],
        'sm_sequence_length' => ['name' => 'Length', 'type' => 'smallint', 'default' => 0],
        'sm_sequence_suffix' => ['name' => 'Suffix', 'type' => 'varchar', 'length' => 15, 'null' => true],
        // counter
        'sm_sequence_counter' => ['name' => 'Counter', 'type' => 'bigint']
    ];
    public $constraints = [
        'sm_sequence_extended_pk' => ['type' => 'pk', 'columns' => ['sm_sequence_name', 'sm_sequence_tenant_id', 'sm_sequence_module_id']],
    ];
    public $history = false;
    public $audit = false;
    public $options_map = [];
    public $options_active = [];
    public $engine = [
        'MySQLi' => 'MyISAM'
    ];

    public $cache = false;
    public $cache_tags = [];
    public $cache_memory = false;

    public $data_asset = [
        'classification' => 'public',
        'protection' => 0,
        'scope' => 'global'
    ];
}
