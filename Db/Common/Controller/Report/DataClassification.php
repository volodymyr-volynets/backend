<?php

namespace Numbers\Backend\Db\Common\Controller\Report;
class DataClassification extends \Object\Controller\Permission {
	public function actionIndex() {
		$form = new \Numbers\Backend\Db\Common\Form\Report\DataClassification([
			'input' => \Request::input()
		]);
		echo $form->render();
	}
}