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

class Tools extends Import
{
    public $data = [
        'groups' => [
            'options' => [
                'pk' => ['ai_group_tenant_id', 'ai_group_code'],
                'model' => '\Numbers\AI\SDK\Model\Groups',
                'method' => 'save',
                'submodule_exists' => ['Numbers.AI.SDK']
            ],
            'data' => [
                [
                    'ai_group_tenant_id' => null,
                    'ai_group_code' => 'SM::DEFAULT_GROUP',
                    'ai_group_name' => 'S/M Default Groups',
                    'ai_group_module_code' => 'SM',
                    'ai_group_inactive' => 0,
                ],
            ]
        ],
        'tools' => [
            'options' => [
                'pk' => ['ai_tool_tenant_id', 'ai_tool_code'],
                'model' => '\Numbers\AI\SDK\Model\Collection\Tools',
                'method' => 'save',
                'submodule_exists' => ['Numbers.AI.SDK']
            ],
            'data' => [
                [
                    'ai_tool_tenant_id' => null,
                    'ai_tool_code' => 'SM::MENU_RESOURCES_RAG_TOOL',
                    'ai_tool_name' => 'S/M Menu Resources RAG Tool',
                    'ai_tool_description' => <<<TEXT
Tool: sm_menu_resources_rag_tool

Description:
Searches internal knowledge base for menu resources.

When to use:
- Questions about menu resources
- Requests requiring up-to-date information
- When unsure and external knowledge may be insufficient

How to use:
1. Rewrite the user query into a concise search query
2. Include key entities, and context
3. Call the tool with the improved query

Returns:
- List of top-k results (1-5), each containing:
  - content (text chunk)
  - source (document code or identifier)
  - score (relevance 0-1)

Instructions:
- Use retrieved content as the primary source of truth
- Combine multiple chunks if needed
- Prefer higher relevance scores
- Cite sources in the answer
- Do NOT fabricate information not present in results

Failure handling:
- If no relevant results: say you don't know or ask for clarification
- If ambiguous: ask a follow-up before retrying
TEXT,
                    'ai_tool_tool_model' => '\Numbers\Backend\System\Modules\AI\Tool\SMMenuResourcesRAGTool',
                    'ai_tool_tool_name' => 'sm_menu_resources_rag_tool',
                    'ai_tool_is_rag' => 1,
                    'ai_tool_inactive' => 0,
                    '\Numbers\AI\SDK\Model\Tool\Groups' => [
                        [
                            'ai_tolgrp_ai_group_code' => 'SM::DEFAULT_GROUP',
                            'ai_tolgrp_inactive' => 0,
                        ]
                    ]
                ],
            ]
        ],
    ];
}
