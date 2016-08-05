<?php

class numbers_backend_documents_basic_controller_catalogs extends object_controller {
	public function action_index() {
		$list = new numbers_backend_documents_basic_model_list_catalogs([
			'input' => request::input()
		]);
		echo $list->render();
	}
	public function action_edit() {
		$form = new numbers_backend_documents_basic_model_form_catalogs([
			'input' => request::input()
		]);
		echo $form->render();
	}
}