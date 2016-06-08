<?php

class numbers_backend_system_menu_controller_menu extends object_controller {

	public $title = 'Menu';
	public $acl = [
		'public' => 0,
		'authorized' => 1
	];

	public function action_index() {
		$input = request::input();
		// create object and load data
		$object = new numbers_backend_system_menu_model_datasource_menu();
		$data = $object->get(['where' => [
			'type' => [1, 2],
			'group1_code' => $input['group1_code'] ?? null,
			'group2_code' => $input['group2_code'] ?? null,
			'group3_code' => $input['group3_code'] ?? null,
		]]);
		// assemble data
		$name = '';
		$icon = '';
		if (!empty($input['group1_code'])) {
			$name = $data[$input['group1_code']]['name'];
			$icon = $data[$input['group1_code']]['icon'];
			$data = $data[$input['group1_code']]['options'];
			// if we have options
			if (!empty($input['group2_code'])) {
				$name = $data[$input['group2_code']]['name'];
				$icon = $data[$input['group2_code']]['icon'];
				$data = $data[$input['group2_code']]['options'];
				// if we have options
				if (!empty($input['group3_code'])) {
					$name = $data[$input['group3_code']]['name'];
					$icon = $data[$input['group3_code']]['icon'];
					$data = $data[$input['group3_code']]['options'];
				}
			}
		}
		echo html::segment(['type' => 'primary', 'value' => $this->render_options($data, $name, $icon)]);
	}

	/**
	 * Render
	 *
	 * @param array $data
	 */
	public function render_options($data, $name, $icon) {
		$value = [];
		foreach ($data as $k => $v) {
			if (!empty($v['options'])) {
				$value[] = $this->render_options($v['options'], $v['name'], $v['icon']);
			} else {
				$extension = '';
				if (!empty($v['name_extension'])) {
					$extension = '<br/>' . $v['name_extension'];
				}
				$value[] = html::a(['href' => $v['url'], 'value' => html::name($v['name'], $v['icon']) . $extension, 'style' => 'width: 300px; display: inline-block;']);
			}
		}
		// implode
		$value = implode('', $value);
		// generate fieldset
		return html::fieldset(['legend' => html::name($name, $icon), 'value' => $value]);
	}
}