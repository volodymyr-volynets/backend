<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Override;

class Aliases
{
    public $data = [
        'resource_id' => [
            'no_data_alias_name' => 'Resource #',
            'no_data_alias_model' => '\Numbers\Backend\System\Modules\Model\Resources',
            'no_data_alias_column' => 'sm_resource_code'
        ],
        'rsrsubres_id' => [
            'no_data_alias_name' => 'Subresource #',
            'no_data_alias_model' => '\Numbers\Backend\System\Modules\Model\Resource\Subresources',
            'no_data_alias_column' => 'sm_rsrsubres_code'
        ],
        'action_id' => [
            'no_data_alias_name' => 'Action #',
            'no_data_alias_model' => '\Numbers\Backend\System\Modules\Model\Resource\Actions',
            'no_data_alias_column' => 'sm_action_code'
        ],
        'form_id' => [
            'no_data_alias_name' => 'Form #',
            'no_data_alias_model' => '\Numbers\Backend\System\Modules\Model\Forms',
            'no_data_alias_column' => 'sm_form_code'
        ],
        'sysflag_id' => [
            'no_data_alias_name' => 'System Flag #',
            'no_data_alias_model' => '\Numbers\Backend\System\Modules\Model\System\Flags',
            'no_data_alias_column' => 'sm_sysflag_code'
        ]
    ];
}
