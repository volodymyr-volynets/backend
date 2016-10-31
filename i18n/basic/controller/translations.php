<?php

class numbers_backend_i18n_basic_controller_translations extends object_controller {
	public function action_index() {
		$list = new numbers_backend_i18n_basic_model_list_translations([
			'input' => request::input()
		]);
		echo $list->render();
	}
	public function action_edit() {
		$form = new numbers_backend_i18n_basic_model_form_translations([
			'input' => request::input()
		]);
		echo $form->render();
	}
}