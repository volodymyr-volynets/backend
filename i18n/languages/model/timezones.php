<?php

class numbers_backend_i18n_languages_model_timezones extends object_data {
	public $column_key = 'lc_timezone_code';
	public $column_prefix = 'lc_timezone_';
	public $orderby = ['lc_timezone_name' => SORT_ASC];
	public $columns = [
		'lc_timezone_code' => ['name' => 'Timezone Code', 'domain' => 'group_code'],
		'lc_timezone_name' => ['name' => 'Name', 'type' => 'text']
	];
	public $data = [];

	/**
	 * Get
	 *
	 * @param array $options
	 */
	public function get($options = []) {
		$data = DateTimeZone::listIdentifiers();
		foreach ($data as $v) {
			$this->data[$v] = ['lc_timezone_name' => $v];
		}
		return parent::get($options);
	}
}