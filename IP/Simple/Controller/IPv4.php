<?php

namespace Numbers\Backend\IP\Simple\Controller;
class IPv4 extends \Object\Controller\Permission {
	public function actionIndex() {
		$form = new \Numbers\Backend\IP\Simple\Form\List2\IPv4([
			'input' => \Request::input()
		]);
		echo $form->render();
	}
	public function actionEdit() {
		$form = new \Numbers\Backend\IP\Simple\Form\IPv4([
			'input' => \Request::input()
		]);
		echo $form->render();
	}
	public function actionImport() {
		$form = new \Object\Form\Wrapper\Import([
			'model' => '\Numbers\Backend\IP\Simple\Form\IPv4',
			'reset_table' => true,
			'input' => \Request::input(),
		]);
		echo $form->render();
	}
}