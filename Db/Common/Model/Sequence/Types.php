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

use Object\Data;

class Types extends Data
{
    public $module_code = 'SM';
    public $title = 'S/M Sequence Types';
    public $column_key = 'sm_seqtype_code';
    public $column_prefix = 'sm_seqtype_';
    public $columns = [
        'sm_seqtype_code' => ['name' => 'Sequence Type', 'domain' => 'type_code'],
        'sm_seqtype_name' => ['name' => 'Name', 'type' => 'text']
    ];
    public $data = [
        'global_simple' => ['sm_seqtype_name' => 'Global (Simple)'],
        'global_advanced' => ['sm_seqtype_name' => 'Global (Advanced)'],
        'tenant_simple' => ['sm_seqtype_name' => 'Tenant (Simple)'],
        'tenant_advanced' => ['sm_seqtype_name' => 'Tenant (Advanced)'],
        'module_simple' => ['sm_seqtype_name' => 'Ledger (Simple)'],
        'module_advanced' => ['sm_seqtype_name' => 'Ledger (Advanced)'],
    ];
}
