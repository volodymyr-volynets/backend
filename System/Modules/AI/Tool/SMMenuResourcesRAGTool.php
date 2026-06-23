<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\AI\Tool;

use Numbers\AI\SDK\Classes\Tool\BaseTool;
use Numbers\AI\SDK\Classes\Tool\AgenticRAGTool;

class SMMenuResourcesRAGTool extends BaseTool
{
    public string $name = 'sm_menu_resources_rag_tool';
    public function description(): string
    {
        return <<<TEXT
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
TEXT;
    }
    public function run(array $input): array
    {
        $result = AgenticRAGTool::singleRAG('SM::MENU_RESOURCES', $input['query'], $input['maximum_records'] ?? null);
        return $result['content_structured'];
    }
    public function schema(): array
    {
        return [
            'query' => ['required' => true, 'name' => 'Query', 'domain' => 'prompt'],
            'maximum_records' => ['name' => 'Maximum Records', 'domain' => 'counter', 'null' => true],
        ];
    }
}
