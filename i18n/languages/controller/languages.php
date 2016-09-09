<?php

class numbers_backend_i18n_languages_controller_languages extends object_controller {
	public function action_index() {
		$list = new numbers_backend_i18n_languages_model_list_languages([
			'input' => request::input()
		]);
		echo $list->render();
	}
	public function action_edit() {
		$form = new numbers_backend_i18n_languages_model_form_languages([
			'input' => request::input()
		]);
		echo $form->render();
	}
}