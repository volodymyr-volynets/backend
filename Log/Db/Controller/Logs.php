<?php

namespace Numbers\Backend\Log\Db\Controller;
class Logs extends \Object\Controller\Permission {
	public function actionIndex() {
		$form = new \Numbers\Backend\Log\Db\Form\List2\Logs([
			'input' => \Request::input()
		]);
		echo $form->render();
	}
}