<?php

class numbers_backend_documents_basic_model_form_catalogs extends numbers_frontend_html_form_wrapper_base {
	public $form_link = 'catalogs';
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
			'dc_catalog_code' => ['order' => 100],
			'dc_catalog_name' => ['order' => 200],
			'dc_catalog_id_required' => ['order' => 300],
			'dc_catalog_valid_extensions' => ['order' => 400],
			'dc_catalog_thumbnail_settings' => ['order' => 500]
		]
	];
	public $elements = [
		'default' => [
			'dc_catalog_code' => [
				'dc_catalog_code' => ['order' => 1, 'label_name' => 'Catalog Code', 'domain' => 'type_code', 'percent' => 50, 'required' => true],
				'dc_catalog_inactive' => ['order' => 3, 'label_name' => 'Inactive', 'type' => 'boolean', 'percent' => 25, 'required' => false, 'method' => 'select', 'no_choose' => true, 'options_model' => 'object_data_model_inactive'],
				'dc_catalog_multiple' => ['order' => 2, 'label_name' => 'Multiple', 'type' => 'boolean', 'percent' => 25, 'required' => false, 'method' => 'select', 'no_choose' => true, 'options_model' => 'object_data_model_inactive']
			],
			'dc_catalog_name' => [
				'dc_catalog_name' => ['order' => 1, 'label_name' => 'Name', 'domain' => 'name', 'percent' => 50, 'required' => true],
				'dc_catalog_storage_id' => ['order' => 2, 'label_name' => 'Storage', 'domain' => 'type_id', 'percent' => 50, 'required' => true, 'method' => 'select', 'options_model' => 'numbers_backend_documents_basic_model_storages']
			],
			'dc_catalog_id_required' => [
				'dc_catalog_id_required' => ['order' => 1, 'label_name' => 'ID Required', 'type' => 'boolean', 'percent' => 33, 'required' => false, 'method' => 'select', 'no_choose' => true, 'options_model' => 'object_data_model_inactive'],
				'dc_catalog_date_required' => ['order' => 2, 'label_name' => 'Date Required', 'type' => 'boolean', 'percent' => 33, 'required' => false, 'method' => 'select', 'no_choose' => true, 'options_model' => 'object_data_model_inactive'],
				'dc_catalog_comment_required' => ['order' => 3, 'label_name' => 'Comment Required', 'type' => 'boolean', 'percent' => 33, 'required' => false, 'method' => 'select', 'no_choose' => true, 'options_model' => 'object_data_model_inactive']
			],
			'dc_catalog_valid_extensions' => [
				'dc_catalog_valid_extensions' => ['order' => 1, 'label_name' => 'Valid Extensions', 'description' => 'Comma separated list of extensions.', 'type' => 'text', 'null' => true, 'percent' => 100, 'required' => false]
			],
			'dc_catalog_thumbnail_settings' => [
				'dc_catalog_thumbnail_settings' => ['order' => 1, 'label_name' => 'Thumbnail Settings', 'description' => 'Example: width=120,height=90', 'type' => 'text', 'null' => true, 'percent' => 100, 'required' => false]
			],
			self::buttons => self::buttons_data_group
		]
	];
	public $collection = [
		'model' => 'numbers_backend_documents_basic_model_catalogs'
	];

	public function validate(& $form) {
		// multiple can not have required fields
		if (!empty($form->values['dc_catalog_multiple'])) {
			if (!empty($form->values['dc_catalog_id_required'])) {
				$form->error('danger', 'Can not be set when multiple is set!', 'dc_catalog_id_required');
			}
			if (!empty($form->values['dc_catalog_date_required'])) {
				$form->error('danger', 'Can not be set when multiple is set!', 'dc_catalog_date_required');
			}
			if (!empty($form->values['dc_catalog_comment_required'])) {
				$form->error('danger', 'Can not be set when multiple is set!', 'dc_catalog_comment_required');
			}
		}
		// thumbnails
		if (!empty($form->values['dc_catalog_thumbnail_settings'])) {
			if (!extension_loaded('imagick')) {
				$form->error('danger', 'You must load imagick extension to use thumbnails!', 'dc_catalog_thumbnail_settings');
			}
			// tokens
			$tokens = explode(',', $form->values['dc_catalog_thumbnail_settings']);
			foreach ($tokens as $v) {
				$v = explode('=', $v);
				if (!in_array($v[0], ['width', 'height'])) {
					$form->error('danger', i18n(null, 'Unknown variable [var]!', ['replace' => ['[var]' => $v[0]]]), 'dc_catalog_thumbnail_settings', ['skip_i18n' => true]);
				}
				if (!isset($v[1]) || !is_numeric($v[1])) {
					$form->error('danger', i18n(null, 'Unknown value [var]!', ['replace' => ['[var]' => $v[1]]]), 'dc_catalog_thumbnail_settings', ['skip_i18n' => true]);
				}
			}
			// preset valid extensions
			if (empty($form->values['dc_catalog_valid_extensions'])) {
				$form->values['dc_catalog_valid_extensions'] = 'png,jpg,jpeg,gif,tiff,tif';
			}
		}
	}
}