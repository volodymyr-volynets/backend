<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\AI\Data;

use Object\Import;

class RAG extends Import
{
    public $data = [
        'types' => [
            'options' => [
                'pk' => ['ai_ragtype_tenant_id', 'ai_ragtype_code'],
                'model' => '\Numbers\AI\SDK\Model\RAG\Types',
                'method' => 'save_insert_new',
                'submodule_exists' => ['Numbers.AI.SDK']
            ],
            'data' => [
                [
                    'ai_ragtype_tenant_id' => null,
                    'ai_ragtype_code' => 'SM::MENU_RESOURCES',
                    'ai_ragtype_name' => 'S/M Menu Resources',
                    'ai_ragtype_description' => 'Menu resources is used in this RAG.',
                    'ai_ragtype_model' => '\Numbers\AI\SDK\Model\Embeddings',
                    'ai_ragtype_id_field_code' => 'ai_embedding_code',
                    'ai_ragtype_key_field_code' => 'ai_embedding_ai_ragtype_code',
                    'ai_ragtype_content_field_code' => 'ai_embedding_content',
                    'ai_ragtype_embeddings_field_code' => 'ai_embedding_embeddings',
                    'ai_ragtype_is_rag' => 1,
                    'ai_ragtype_fetch_counter' => 15,
                    'ai_ragtype_fetch_definition' => 1,
                    'ai_ragtype_inactive' => 0,
                ],
            ]
        ],
        'definitions' => [
            'options' => [
                'pk' => ['ai_ragdefin_tenant_id', 'ai_ragdefin_code'],
                'model' => '\Numbers\AI\SDK\Model\RAG\Definitions',
                'method' => 'save_insert_new',
                'submodule_exists' => ['Numbers.AI.SDK']
            ],
            'data' => [
                [
                    'ai_ragdefin_tenant_id' => null,
                    'ai_ragdefin_code' => 'SM::MENU_RESOURCES_TYPE',
                    'ai_ragdefin_name' => 'Type',
                    'ai_ragdefin_default' => 'Menu',
                    'ai_ragdefin_ai_ragtype_code' => 'SM::MENU_RESOURCES',
                    'ai_ragdefin_description' => 'Menu type.',
                    'ai_ragdefin_order' => 1000,
                    'ai_ragdefin_inactive' => 0,
                ],
                [
                    'ai_ragdefin_tenant_id' => null,
                    'ai_ragdefin_code' => 'SM::MENU_RESOURCES_NAME',
                    'ai_ragdefin_name' => 'Name',
                    'ai_ragdefin_default' => null,
                    'ai_ragdefin_ai_ragtype_code' => 'SM::MENU_RESOURCES',
                    'ai_ragdefin_description' => 'Name of the menu.',
                    'ai_ragdefin_order' => 1010,
                    'ai_ragdefin_inactive' => 0,
                ],
                [
                    'ai_ragdefin_tenant_id' => null,
                    'ai_ragdefin_code' => 'SM::MENU_RESOURCES_ID',
                    'ai_ragdefin_name' => 'ID',
                    'ai_ragdefin_default' => null,
                    'ai_ragdefin_ai_ragtype_code' => 'SM::MENU_RESOURCES',
                    'ai_ragdefin_description' => 'ID of the menu.',
                    'ai_ragdefin_order' => 1015,
                    'ai_ragdefin_inactive' => 0,
                ],
                [
                    'ai_ragdefin_tenant_id' => null,
                    'ai_ragdefin_code' => 'SM::MENU_RESOURCES_MODULE',
                    'ai_ragdefin_name' => 'Module',
                    'ai_ragdefin_default' => null,
                    'ai_ragdefin_ai_ragtype_code' => 'SM::MENU_RESOURCES',
                    'ai_ragdefin_description' => 'Module of the menu.',
                    'ai_ragdefin_order' => 1020,
                    'ai_ragdefin_inactive' => 0,
                ],
                [
                    'ai_ragdefin_tenant_id' => null,
                    'ai_ragdefin_code' => 'SM::MENU_RESOURCES_GROUPS',
                    'ai_ragdefin_name' => 'Groups',
                    'ai_ragdefin_default' => null,
                    'ai_ragdefin_ai_ragtype_code' => 'SM::MENU_RESOURCES',
                    'ai_ragdefin_description' => 'Groups of the menu.',
                    'ai_ragdefin_order' => 1030,
                    'ai_ragdefin_inactive' => 0,
                ],
                [
                    'ai_ragdefin_tenant_id' => null,
                    'ai_ragdefin_code' => 'SM::MENU_RESOURCES_URL',
                    'ai_ragdefin_name' => 'URL',
                    'ai_ragdefin_default' => null,
                    'ai_ragdefin_ai_ragtype_code' => 'SM::MENU_RESOURCES',
                    'ai_ragdefin_description' => 'URL of the menu.',
                    'ai_ragdefin_order' => 1040,
                    'ai_ragdefin_inactive' => 0,
                ],
                [
                    'ai_ragdefin_tenant_id' => null,
                    'ai_ragdefin_code' => 'SM::MENU_RESOURCES_ACL',
                    'ai_ragdefin_name' => 'ACL',
                    'ai_ragdefin_default' => null,
                    'ai_ragdefin_ai_ragtype_code' => 'SM::MENU_RESOURCES',
                    'ai_ragdefin_description' => 'ACL of the menu.',
                    'ai_ragdefin_order' => 1050,
                    'ai_ragdefin_inactive' => 0,
                ],
            ]
        ],
    ];
}
