<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Controller;

use Object\Controller;
use Numbers\Backend\System\Modules\DataSource\Menu\Searches;
use Numbers\AI\SDK\Classes\Agent\PreConfigured;
use Numbers\AI\SDK\Classes\Tool\AgenticRAGTool;
use Numbers\AI\SDK\Model\Settings;

class MenuSearches extends Controller
{
    public $title = 'Menu Searches';

    public function actionIndex()
    {
        $result = [
            'success' => true,
            'error' => [],
            'html' => [],
        ];
        // user needs to be logged in
        if (!\User::authorized()) {
            $result['error'][] = 'You need to be logged in!';
            \Layout::renderAs($result, 'application/json');
        }
        // code and search_text needs to be provided
        $search_text = \Request::input('search_text');
        $sm_menusearch_code = \Request::input('sm_menusearch_code');
        if (empty($sm_menusearch_code)) {
            $result['error'][] = 'You need to specify code!';
            \Layout::renderAs($result, 'application/json');
        }
        // load code data
        $search_data = Searches::getSingleStatic([
            'where' => [
                'sm_menusearch_code' => (string) $sm_menusearch_code,
            ]
        ]);
        // we must have data in db
        if (empty($search_data)) {
            $result['error'][] = 'You need to specify code!';
            \Layout::renderAs($result, 'application/json');
        }
        // if we need to have controller permission
        if (!empty($search_data['sm_resource_code'])) {
            if (\Can::controllerActionPermitted($search_data['sm_resource_code'], 'Index', 'List_View', null)) {
                goto logic_label;
            }
            if (\Can::controllerActionPermitted($search_data['sm_resource_code'], 'Edit', 'Record_View', null)) {
                goto logic_label;
            }
            $result['error'][] = 'You do not have permission to the controller!';
            \Layout::renderAs($result, 'application/json');
        }
        logic_label:
                // logic here

                $search_model = \Factory::model($search_data['model']);
        $db_model = \Factory::model($search_data['sm_model_code']);
        $result = $search_model->render([
            'search_data' => $search_data,
            'search_text' => $search_text,
        ]);
        \Layout::renderAs($result, 'application/json');
    }

    public function actionModelDescription()
    {
        $result = [
            'success' => false,
            'error' => [],
            'columns' => [],
            'title' => '',
        ];
        // user needs to be logged in
        if (!\User::authorized()) {
            $result['error'][] = 'You need to be logged in!';
            \Layout::renderAs($result, 'application/json');
        }
        // code needs to be provided
        $sm_menusearch_code = \Request::input('sm_menusearch_code');
        if (empty($sm_menusearch_code)) {
            $result['error'][] = 'You need to specify code!';
            \Layout::renderAs($result, 'application/json');
        }
        // load code data
        $search_data = Searches::getSingleStatic([
            'where' => [
                'sm_menusearch_code' => (string) $sm_menusearch_code,
            ]
        ]);
        // we must have data in db
        if (empty($search_data)) {
            $result['error'][] = 'You need to specify code!';
            \Layout::renderAs($result, 'application/json');
        }
        // if we need to have controller permission
        if (!empty($search_data['sm_resource_code'])) {
            if (\Can::controllerActionPermitted($search_data['sm_resource_code'], 'Index', 'List_View', null)) {
                goto logic_label;
            }
            if (\Can::controllerActionPermitted($search_data['sm_resource_code'], 'Edit', 'Record_View', null)) {
                goto logic_label;
            }
            $result['error'][] = 'You do not have permission to the controller!';
            \Layout::renderAs($result, 'application/json');
        }
        logic_label:
                // logic here

                $db_model = \Factory::model($search_data['sm_model_code']);
        $search_model = \Factory::model($search_data['model']);
        $result['title'] = $db_model->title;
        $result['columns'] = array_values(array_column($search_model->search_columns, 'name'));
        $result['success'] = true;
        \Layout::renderAs($result, 'application/json');
    }

    public function actionMenuSearchesRAG()
    {
        $result = [
            'success' => true,
            'error' => [],
            'html' => '',
        ];
        // user needs to be logged in
        if (!\User::authorized()) {
            $result['error'][] = 'You need to be logged in!';
            \Layout::renderAs($result, 'application/json');
        }
        // query
        $query = \Request::input('query');
        $maximum_records = \Request::input('maximum_records') ?? 10;
        if (empty($query)) {
            $result['error'][] = 'You need to specify query / keywords!';
            \Layout::renderAs($result, 'application/json');
        }
        // A/I Settings
        $ai_settings = Settings::getSingleStatic([
            'where' => [
                'ai_setting_tenant_id' => \Tenant::id(),
            ]
        ]);
        if (empty($ai_settings['ai_setting_default_ai_agent_code'])) {
            $result['error'][] = 'You need to set default agent in A/I Settings!';
            \Layout::renderAs($result, 'application/json');
        }
        // query RAG tool
        $content_string = AgenticRAGTool::singleRAG('SM::MENU_RESOURCES', $query, $maximum_records)['content_string'];
        // generate prompt
        $prompt = 'Answer the question using the context below.' . "\n\n";
        $prompt .= 'Context:' . "\n";
        $prompt .= $content_string . "\n\n";
        $prompt .= 'Question:' . "\n";
        $prompt .= 'Give me a table of menu items with: Name, URL (as HTML link with value "Open"), Module (take second module value with /), Groups (Take first group name), ACL.';
        // call agent
        $agent = new PreConfigured($ai_settings['ai_setting_default_ai_agent_code']);
        $response1 = $agent->prompt($prompt);
        $result['html'] = implode('<br/><br/>', array_column($response1['content'], 'html'));
        $result['success'] = true;
        \Layout::renderAs($result, 'application/json');
    }
}
