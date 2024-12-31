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

use Object\Content\Messages;
use Object\Controller;

class DevPortal extends Controller
{
    public $title = 'Development Portal';
    public $icon = 'fas fa-cogs';

    public function actionIndex()
    {
        if (!\Application::get('debug.toolbar')) {
            throw new \Exception('You must enabled toolbar to view Dev. Portal.');
        }
        // get data
        $model = new \Numbers\Backend\System\Modules\Class2\DevPortal();
        // render links
        if (!empty($model->data['Links'])) {
            $ms = '';
            foreach ($model->data['Links'] as $k => $v) {
                foreach ($v as $k2 => $v2) {
                    $icon = '';
                    if (!empty($v2['icon'])) {
                        $icon = \HTML::icon(['type' => $v2['icon']]) . ' ';
                    }
                    $ms .= \HTML::a(['href' => $v2['url'], 'value' => $icon . i18n(null, $v2['name'])]) . '&nbsp;';
                }
                echo \HTML::segment(['header' => $k, 'value' => $ms]);
            }
        }
    }

    public function actionPHP()
    {
        if (!\Application::get('debug.toolbar')) {
            throw new \Exception('You must enabled toolbar to view Dev. Portal.');
        }
        // if we make an ajax call
        $php_code = \Request::input('php_code', false, false);
        if (!empty($php_code)) {
            $result = [
                'success' => true,
                'error' => [],
                'data' => ''
            ];
            ob_start();
            try {
                eval($php_code);
            } catch (\Exception $e) {
                $result['error'][] = $e->getMessage();
                $result['success'] = false;
            }
            $result['data'] = ob_get_clean();
            \Layout::renderAs($result, 'application/json');
        }
        echo \HTML::ace(['mode' => 'php', 'id' => 'id_ace_editor']);
        echo \HTML::button(['type' => 'primary', 'value' => 'Run', 'onclick' => 'ace_execute_php_code();']);
        echo \HTML::div(['id' => 'id_div_result', 'value' => '']);
        echo <<<TTT
			<script type="text/javascript">
			function ace_execute_php_code() {
				var ace_editor = ace.edit("id_ace_editor");
				var request = $.ajax({
					url: '/Numbers/Backend/System/Modules/Controller/DevPortal/_PHP',
					method: "POST",
					data: {
						php_code: ace_editor.getValue(),
						__skip_session: true,
						__ajax: true
					},
					dataType: "json"
				});
				request.done(function(data) {
					$("#id_div_result").html(print_r(data));
				});
			}
			</script>
TTT;
    }

    public function actionPostgreSQL()
    {
        if (!\Application::get('debug.toolbar')) {
            throw new Exception('You must enabled toolbar to view Dev. Portal.');
        }
        // if we make an ajax call
        $sql_code = \Request::input('sql_code', false, false);
        if (!empty($sql_code)) {
            $db_object = new \Db();
            $result = $db_object->queries($sql_code);
            $temp = [];
            foreach ($result['data'] as $k => $v) {
                if (empty($v)) {
                    $temp[] = \HTML::h3(['value' => 'Query ' . $k . ':']) . \HTML::message(['type' => WARNING, 'options' => Messages::NO_ROWS_FOUND]);
                } else {
                    $header = array_keys(current($v));
                    $header = array_combine($header, $header);
                    $temp[] = \HTML::h3(['value' => 'Query ' . $k . ':']) . \HTML::table(['options' => $v, 'header' => $header]);
                }
            }
            $result['data'] = implode(\HTML::hr(), $temp);
            \Layout::renderAs($result, 'application/json');
        }
        echo \HTML::ace(['mode' => 'pgsql', 'id' => 'id_ace_editor']);
        echo \HTML::button(['type' => 'primary', 'value' => 'Run', 'onclick' => 'ace_execute_pgsql();']);
        echo \HTML::div(['id' => 'id_div_result', 'value' => '']);
        echo <<<TTT
			<script type="text/javascript">
			function ace_execute_pgsql() {
				var ace_editor = ace.edit("id_ace_editor");
				var request = $.ajax({
					url: '/Numbers/Backend/System/Modules/Controller/DevPortal/_PostgreSQL',
					method: "POST",
					data: {
						sql_code: ace_editor.getValue(),
						__skip_session: true,
						__ajax: true
					},
					dataType: "json"
				});
				request.done(function(data) {
					if (data.success) {
						$("#id_div_result").html(data.data);
					} else {
						$("#id_div_result").html(print_r(data.error));
					}
				});
			}
			</script>
TTT;
    }
}
