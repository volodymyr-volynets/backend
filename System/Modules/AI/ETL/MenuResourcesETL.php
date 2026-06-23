<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\AI\ETL;

use Numbers\AI\SDK\Classes\Agent\PreConfigured;
use Numbers\AI\SDK\Model\Embeddings;
use Numbers\AI\SDK\Model\Settings;
use Numbers\Backend\Db\Common\Model\Models;
use Numbers\Backend\System\Modules\Model\Resources;
use Numbers\Tenants\Widgets\Batches\Helper\Save;
use Numbers\Tenants\Widgets\Batches\Model\Records;

class MenuResourcesETL
{
    public static function process()
    {
        $result = [
            'success' => false,
            'error' => [],
            'count' => 0,
            'legend' => [],
        ];
        // load default embedding agent
        $ai_settings = Settings::getSingleStatic([
            'where' => [
                'ai_setting_tenant_id' => \Tenant::id(),
            ]
        ]);
        if (empty($ai_settings['ai_setting_embedding_ai_agent_code'])) {
            throw new \Exception('ETL: please set A/I Embedding Agent Code in A/I Settings');
        }
        // run ETL
        return \Db::etl(function ($options) {
            $query = Resources::queryBuilderStatic(['alias' => 'a'])
                ->select()
                ->columns([
                    'a.*',
                    'd.*'
                ])
                ->join('LEFT', new Records(), 'b', 'ON', [
                    ['AND', ['b.tm_batchrecord_tm_batchtype_code', '=', 'AI_EMBEDDINGS', false], false],
                    ['AND', ['b.tm_batchrecord_field_code', '=', 'sm_resource_id', false], false],
                    ['AND', ['b.tm_batchrecord_field_value_id', '=', 'a.sm_resource_id', true], false]
                ])
                ->join('LEFT', new Records(), 'c', 'ON', [
                    ['AND', ['c.tm_batchrecord_tm_batchtype_code', '=', 'AI_EMBEDDINGS', false], false],
                    ['AND', ['c.tm_batchrecord_field_code', '=', 'ai_embedding_code', false], false],
                    ['AND', ['c.tm_batchrecord_tm_batchentry_code', '=', 'b.tm_batchrecord_tm_batchentry_code', true], false]
                ])
                ->join('LEFT', new Embeddings(), 'd', 'ON', [
                    ['AND', ['d.ai_embedding_tenant_id', '=', \Tenant::id(), false], false],
                    ['AND', ['d.ai_embedding_code', '=', 'c.tm_batchrecord_field_value_code', true], false]
                ])
                ->whereMultiple('AND', [
                    'a.sm_resource_type' => 200,
                ])
                ->limit(10_000)
                ->orderby(['a.sm_resource_id' => SORT_ASC]);
            return $query->query(['sm_resource_id'])['rows'];
        }, function ($row, $options) {
            $content = MenuResources2Embedding::transform($row, $options)['content'];
            $transform = [
                // resource
                'sm_resource_id' => $row['sm_resource_id'],
                'sm_resource_name' => $row['sm_resource_name'],
                // embeddings
                'ai_embedding_code' => $row['ai_embedding_code'],
                'ai_embedding_content' => $content,
                'ai_embedding_hash_sha1' => sha1($content),
            ];
            // compare sha1s and return null
            if (!empty($row['ai_embedding_hash_sha1']) && $row['ai_embedding_hash_sha1'] == $transform['ai_embedding_hash_sha1']) {
                return false;
            }
            return $transform;
        }, function ($row, $options) {
            // call AI Embeddings API
            $agent = new PreConfigured($options['ai_agent_code']);
            $response1 = $agent->embeddings($row['ai_embedding_content'], [
                'ai_embedding_code' => $row['ai_embedding_code'] ?? null,
                'ai_embedding_ai_ragtype_code' => $options['ai_embedding_ai_ragtype_code'] ?? 'OTHER',
            ]);
            // if its existing we do not create batch
            if (!empty($row['ai_embedding_code'])) {
                return false;
            }
            $ai_embedding_code = $response1['embeddings']['ai_embedding_code'];
            // create batches
            $raw_batch_records = [];
            $raw_batch_records[$response1['embeddings']['ai_embedding_code'] . '::CODE'] = [
                'tm_batchrecord_sm_model_id' => Models::loadIDByCodeStatic('\Numbers\AI\SDK\Model\Embeddings', null, null, ['first' => true]),
                'tm_batchrecord_sm_model_code' => '\Numbers\AI\SDK\Model\Embeddings',
                'tm_batchrecord_no_data_model_role_code' => 'embedding',
                'tm_batchrecord_field_code' => 'ai_embedding_code',
                'tm_batchrecord_field_name' => 'A/I Embedding Code',
                'tm_batchrecord_field_value_id' => null,
                'tm_batchrecord_field_value_code' => $ai_embedding_code,
                'tm_batchrecord_field_value_name' => 'Embedding Content and Vectors',
                'tm_batchrecord_inactive' => 0,
            ];
            $raw_batch_records[$response1['embeddings']['ai_embedding_code'] . '::RESOURCE'] = [
                'tm_batchrecord_sm_model_id' => Models::loadIDByCodeStatic('\Numbers\Backend\System\Modules\Model\Resources', null, null, ['first' => true]),
                'tm_batchrecord_sm_model_code' => '\Numbers\Backend\System\Modules\Model\Resources',
                'tm_batchrecord_no_data_model_role_code' => 'primary',
                'tm_batchrecord_field_code' => 'sm_resource_id',
                'tm_batchrecord_field_name' => 'S/M Resource #',
                'tm_batchrecord_field_value_id' => $row['sm_resource_id'],
                'tm_batchrecord_field_value_code' => null,
                'tm_batchrecord_field_value_name' => $row['sm_resource_name'],
                'tm_batchrecord_inactive' => 0,
            ];
            $batch_helper_save = Save::create(null, 'AI_EMBEDDINGS', $raw_batch_records);
            if (!$batch_helper_save['success']) {
                return false;
            }
        }, [
            'ai_agent_code' => $ai_settings['ai_setting_embedding_ai_agent_code'],
            'ai_embedding_ai_ragtype_code' => 'SM::MENU_RESOURCES',
        ]);
    }
}
