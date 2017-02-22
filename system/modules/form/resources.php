<?php

class numbers_backend_system_modules_form_resources extends object_form_wrapper_base {
	public $form_link = 'resources';
	public $options = [
		'segment' => [
			'type' => 'primary',
			'header' => [
				'icon' => ['type' => 'pencil-square-o'],
				'title' => 'View / Edit:'
			]
		]
	];
	public $containers = [
		'default' => ['default_row_type' => 'grid', 'order' => 1]
	];
	public $rows = [
		'default' => [
			'em_enttitle_name' => ['order' => 100],
			'em_enttitle_order' => ['order' => 200]
		]
	];
	public $elements = [
		'default' => [
			'em_enttitle_name' => [
				'em_enttitle_name' => ['order' => 1, 'label_name' => 'Name', 'domain' => 'personal_title', 'percent' => 100, 'required' => true, 'navigation' => true]
			],
			'em_enttitle_order' => [
				'em_enttitle_order' => ['order' => 2, 'label_name' => 'Order', 'domain' => 'order', 'percent' => 50, 'required' => true],
				'em_enttitle_inactive' => ['order' => 3, 'label_name' => 'Inactive', 'type' => 'boolean', 'percent' => 50, 'required' => false, 'method' => 'select', 'no_choose' => true, 'options_model' => 'object_data_model_inactive']
			],
			self::buttons => self::buttons_data_group
		]
	];
	public $collection = [
		'model' => 'numbers_backend_system_modules_model_resources'
	];

	public function overrides() {
		// todo: handle overrides here
	}

	public function validate(& $form) {
		// validation
	}
}