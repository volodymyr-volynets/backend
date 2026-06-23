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
use Numbers\Backend\System\Modules\Model\Form\Updates;

class FormUpdates extends Controller
{
    public $title = 'Form Updates';

    public function actionIndex()
    {
        $open_rooms = \Request::input('open_rooms') ?? [];
        $result = [
            'success' => false,
            'error' => [],
            'data' => [],
            'details' => [],
            'need_refresh' => [],
            'data_hashed_pk' => [],
            'form_messages' => [],
        ];
        foreach ($open_rooms as $k => $v) {
            $query = Updates::queryBuilderStatic()->select();
            $query->columns([
                'operation_name' => 'sm_frmupdate_operation_name',
                'operation_counter' => 'COUNT(*)',
                'operation_details' => $query->db_object->sqlHelper('string_agg', ['expression' => 'sm_frmupdate_operation_details', 'delimiter' => ';;']),
                'subform_pk' => $query->db_object->sqlHelper('string_agg', ['expression' => 'sm_frmupdate_subform_pk', 'delimiter' => ';;']),
            ]);
            $query->where('AND', ['sm_frmupdate_tenant_id', '=', \Tenant::id()]);
            $exploded = explode('::', $v['form_room']);
            $query->where('AND', ['sm_frmupdate_form_code', '=', $exploded[0]]);
            if (isset($exploded[1])) {
                $query->where('AND', ['sm_frmupdate_form_pk', '=', $exploded[1]]);
            } else {
                $query->where('AND', ['sm_frmupdate_form_pk', 'IS', null]);
            }
            //$query->where('AND', ['sm_frmupdate_inserted_user_id', '<>', $user_id]);
            $query->where('AND', ['sm_frmupdate_inserted_timestamp', '>', $v['timestamp']]);
            $query->groupby(['operation_name']);
            $rows = $query->query(['operation_name'])['rows'] ?? [];
            // process per types
            $locs = [
                'View' => ['NF.Form.NumberOfViews', '{number} view(s)'],
                'Update' => ['NF.Form.NumberOfUpdates', '{number} update(s)'],
            ];
            foreach (['View', 'Update'] as $k0) {
                if (isset($rows[$k0])) {
                    $result['data'][$k][$k0] = loc($locs[$k0][0], $locs[$k0][1], [
                        'number' => $rows[$k0]['operation_counter'],
                        '__plural' => $rows[$k0]['operation_counter'],
                    ]);
                    if (!empty($rows[$k0]['operation_details'])) {
                        $details = explode(';;', $rows[$k0]['operation_details']);
                        $result['details'][$k] = array_merge($result['details'][$k] ?? [], $details);
                    }
                    if ($k0 == 'Update') {
                        $result['need_refresh'][$k] = true;
                    } else {
                        $result['need_refresh'][$k] = false;
                    }
                    if (!empty($rows[$k0]['subform_pk'])) {
                        $subform_pk = explode(';;', $rows[$k0]['subform_pk']);
                        $result['data_hashed_pk'][$k] = array_merge($result['data_hashed_pk'][$k] ?? [], $subform_pk);
                    }
                    // messages
                    if (!empty($result['details'][$k])) {
                        $js_func_name = "form_" . $k . "_form_actions_message_details_" . strtolower($k0);
                        $js_func_lines = [
                            'let form = $("#form_' . $k . '_form");',
                        ];
                        foreach ($result['details'][$k] as $v12) {
                            if (is_json($v12)) {
                                $v12 = loc(json_decode($v12, true));
                            }
                            $js_func_lines[] = 'Numbers.Form.error(form, "info", "' . $v12 . '");';
                        }
                        $js_func_line_str = implode("\n", $js_func_lines);
                        $js = <<<TTT
                            function {$js_func_name}() {
                                {$js_func_line_str}
                            }
TTT;
                        $result['form_messages'][$k] = \HTML::a([
                            'href' => 'javascript:void(0)',
                            'value' => \HTML::badge(['type' => 'info', 'value' => count($result['details'][$k])]),
                            'onclick' => "{$js_func_name}();"
                        ]) . \HTML::script(['value' => $js]);
                    }
                }
            }
            if (isset($result['data'][$k])) {
                $result['data'][$k] = array_values($result['data'][$k]);
            }
        }
        $result['success'] = true;
        \Layout::renderAs($result, 'application/json');
    }
}
