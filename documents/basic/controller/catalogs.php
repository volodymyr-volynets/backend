<?php

class numbers_backend_documents_basic_controller_catalogs extends \Object\Controller {
	public function actionIndex() {
		$list = new numbers_backend_documents_basic_model_list_catalogs([
			'input' => \Request::input()
		]);
		echo $list->render();
	}
	public function actionEdit() {
		$form = new numbers_backend_documents_basic_model_form_catalogs([
			'input' => \Request::input()
		]);
		echo $form->render();
	}
}