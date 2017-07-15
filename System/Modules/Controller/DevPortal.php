<?php

namespace Numbers\Backend\System\Modules\Controller;
class DevPortal extends \Object\Controller {

	public $title = 'Development Portal';
	public $icon = 'cogs';

	public function actionIndex() {
		if (!\Application::get('debug.toolbar')) {
			Throw new Exception('You must enabled toolbar to view Dev. Portal.');
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
					$ms.= \HTML::a(['href' => $v2['url'], 'value' => $icon . i18n(null, $v2['name'])]) . '&nbsp;';
				}
				echo \HTML::segment(['header' => $k, 'value' => $ms]);
			}
		}
	}
}