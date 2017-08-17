<?php

namespace Numbers\Backend\Session\Db\Controller\Report;
class SessionLog extends \Object\Controller\Permission {
	public function actionIndex() {
		$form = new \Numbers\Backend\Session\Db\Form\Report\SessionLog([
			'input' => \Request::input()
		]);
		echo $form->render();
	}
}