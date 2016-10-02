<?php

class numbers_backend_documents_basic_model_form_upload extends numbers_frontend_html_form_wrapper_base {
	public $form_link = 'file_upload';
	public $options = [
		'no_ajax_form_reload' => true
	];
	public $containers = [
		'default' => ['default_row_type' => 'grid', 'order' => 1, 'custom_renderer' => 'numbers_backend_documents_basic_model_form_upload::render_upload_container'],
		'buttons' => ['default_row_type' => 'grid', 'order' => 2]
	];
	public $rows = [];
	public $elements = [
		'buttons' => [
			self::BUTTONS => [
				self::BUTTON_SUBMIT => self::BUTTON_SUBMIT_DATA
			]
		]
	];
	public $collection = [];

	public function overrides() {
		// todo: handle overrides here
	}

	public function validate(& $form) {
		// values
		$input = $form->options['input'] ?? [];
		$catalogs_model = new numbers_backend_documents_basic_model_catalogs();
		$catalogs = $catalogs_model->get();

		$file_uploaded = false;
		if (!empty($input['upload']) && is_array($input['upload'])) {
			foreach ($input['upload'] as $k => $v) {
				$name = 'upload[' . $k . ']';
				// see if we have a catalog
				$current_catalog = [];
				if (!empty($v['catalog_code']) && !empty($catalogs[$v['catalog_code']])) {
					$current_catalog = $catalogs[$v['catalog_code']];
				} else {
					continue;
				}
				// validate required fields
				if ($current_catalog['dc_catalog_id_required']) {
					if (empty($v['id_required'])) {
						$form->error('danger', i18n(null, object_content_messages::$required_field), $name . '[id_required]');
					}
				}
				if ($current_catalog['dc_catalog_date_required']) {
					if (empty($v['date_required'])) {
						// todo: validate date
						$form->error('danger', i18n(null, object_content_messages::$required_field), $name . '[date_required]');
					}
				}
				if ($current_catalog['dc_catalog_comment_required']) {
					if (empty($v['comment_required'])) {
						$form->error('danger', i18n(null, object_content_messages::$required_field), $name . '[comment_required]');
					}
				}
				
			}
		}
		// we must have atleast one file
		if (!$file_uploaded) {
			$form->error('danger', i18n(null, 'You must upload atleast one file!'));
		}
	}

	/**
	 * Render
	 *
	 * @param object $form
	 */
	public function render_upload_container(& $form) {
		$result = [
			'success' => false,
			'error' => [],
			'data' => [
				'html' => '',
				'js' => '',
				'css' => ''
			]
		];
		// values
		$input = $form->options['input'] ?? [];
		$catalogs_model = new numbers_backend_documents_basic_model_catalogs();
		$catalogs_options = $catalogs_model->options();
		$catalogs = $catalogs_model->get();
		// we would assemble everyting into $data variable
		$data = [
			'options' => []
		];
		// up to 3 uploads at a time
		for ($row_number = 1; $row_number <= 3; $row_number++) {
			$name = 'upload[' . $row_number . ']';
			$row_class = $row_number % 2 ? 'grid_row_even' : 'grid_row_odd';
			$current_catalog = [];
			if (!empty($input['upload'][$row_number]['catalog_code']) && !empty($catalogs[$input['upload'][$row_number]['catalog_code']])) {
				$current_catalog = $catalogs[$input['upload'][$row_number]['catalog_code']];
			}
			// first line
			$data['options'][$row_number]['row_number']['row_number'] = [
				'label' => '&nbsp;',
				'value' => $row_number . '.',
				'options' => [
					'percent' => 1
				],
				'class' => 'grid_counter_row',
				'row_class' => $row_class
			];
			$data['options'][$row_number]['catalog_code']['catalog_code'] = [
				'label' => html::label(['value' => i18n(null, 'Catalog') . ':', 'class' => 'control-label']),
				'value' => $form->render_element_value([
					'type' => 'field',
					'options' => [
						'id' => 'catalog_code_' . $row_number,
						'name' => $name . '[catalog_code]',
						'method' => 'html::select',
						'options' => $catalogs_options,
						'onchange' => 'this.form.submit();'
					]
				], $input['upload'][$row_number]['catalog_code'] ?? null),
				'options' => [
					'percent' => 25
				]
			];
			$data['options'][$row_number]['file']['file'] = [
				'label' => html::label(['value' => i18n(null, 'Upload') . ':', 'class' => 'control-label']),
				'value' => html::file(['class' => 'file_upload_file', 'skip_form_control' => true, 'multiple' => !empty($current_catalog['dc_catalog_multiple'])]),
				'options' => [
					'percent' => 25
				]
			];
			$data['options'][$row_number]['files']['files'] = [
				'label' => html::label(['value' => i18n(null, 'File(s)') . ':', 'class' => 'control-label']),
				'value' => '<div>123</div>',
				'options' => [
					//'percent' => 25
				]
			];
			// 2nd row if not multiple
			if (!empty($current_catalog) && empty($current_catalog['dc_catalog_multiple'])) {
				$temp_name = i18n(null, 'ID');
				if ($current_catalog['dc_catalog_id_required']) {
					$temp_name = html::mandatory(['type' => 'mandatory', 'value' => $temp_name]);
				}
				$temp_name.= ':';
				$data['options'][$row_number . '_2nd']['row_number']['row_number'] = [
					'label' => '&nbsp;',
					'value' => '&nbsp;',
					'options' => [
						'percent' => 1
					],
					'class' => 'grid_counter_row',
					'row_class' => $row_class
				];
				$data['options'][$row_number . '_2nd']['id_required']['id_required'] = [
					'label' => html::label(['value' => $temp_name, 'class' => 'control-label']),
					'error' => $form->get_field_errors(['options' => ['name' => $name . '[id_required]']]),
					'value' => $form->render_element_value([
						'type' => 'field',
						'options' => [
							'id' => 'id_required_' . $row_number,
							'name' => $name . '[id_required]',
							'method' => 'html::input',
						]
					], $input['upload'][$row_number]['id_required'] ?? null),
					'options' => [
						'percent' => 25
					],
					'row_class' => $row_class
				];
				$temp_name = i18n(null, 'Date');
				if ($current_catalog['dc_catalog_date_required']) {
					$temp_name = html::mandatory(['type' => 'mandatory', 'value' => $temp_name]);
				}
				$temp_name.= ':';
				$data['options'][$row_number . '_2nd']['date_required']['date_required'] = [
					'label' => html::label(['value' => $temp_name, 'class' => 'control-label']),
					'error' => $form->get_field_errors(['options' => ['name' => $name . '[date_required]']]),
					'value' => $form->render_element_value([
						'type' => 'field',
						'options' => [
							'id' => 'date_required_' . $row_number,
							'name' => $name . '[date_required]',
							'method' => 'html::calendar',
							'calendar_icon' => 'right'
						]
					], $input['upload'][$row_number]['date_required'] ?? null),
					'options' => [
						'percent' => 25
					],
					'row_class' => $row_class
				];
				$temp_name = i18n(null, 'Comment');
				if ($current_catalog['dc_catalog_comment_required']) {
					$temp_name = html::mandatory(['type' => 'mandatory', 'value' => $temp_name]);
				}
				$temp_name.= ':';
				$data['options'][$row_number . '_2nd']['comment_required']['comment_required'] = [
					'label' => html::label(['value' => $temp_name, 'class' => 'control-label']),
					'error' => $form->get_field_errors(['options' => ['name' => $name . '[comment_required]']]),
					'value' => $form->render_element_value([
						'type' => 'field',
						'options' => [
							'id' => 'comment_required_' . $row_number,
							'name' => $name . '[comment_required]',
							'method' => 'html::textarea',
							'rows' => 3
						]
					], $input['upload'][$row_number]['comment_required'] ?? null),
					'options' => [
						'percent' => 50
					],
					'row_class' => $row_class
				];
			}
		}
		// add js file and initialize uploads
		layout::add_js('/numbers/media_submodules/numbers_backend_documents_basic_base.js');
		layout::onload('numbers.backend_documents.init();');
		$result['data']['html'] = html::grid($data);
		$result['success'] = true;
		return $result;
	}

	public function save(& $form) {
		
	}
}