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

use Object\Data;

class Types extends Data
{
    public $module_code = 'SM';
    public $title = 'S/M Form Types';
    public $column_key = 'sm_frmtype_id';
    public $column_prefix = 'sm_frmtype_';
    public $orderby;
    public $columns = [
        'sm_frmtype_id' => ['name' => 'Type #', 'domain' => 'type_id'],
        'sm_frmtype_name' => ['name' => 'Name', 'type' => 'text']
    ];
    public $data = [
        10 => ['sm_frmtype_name' => 'Form'],
        20 => ['sm_frmtype_name' => 'List'],
        30 => ['sm_frmtype_name' => 'Report'],
    ];

    /**
     * Determine type #
     *
     * @param string $type
     * @return int
     */
    public function determineTypeId(string $type): int
    {
        $data = $this->get([
            'where' => [
                'sm_frmtype_name' => $type
            ]
        ]);
        return key($data);
    }
}
