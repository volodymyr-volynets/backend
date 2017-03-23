<?php

class numbers_backend_system_modules_datasource_resource_map extends object_datasource {
	public $db_link;
	public $db_link_flag;
	public $pk = ['sm_rsrcmp_resource_id', 'sm_rsrcmp_method_code', 'sm_rsrcmp_action_id'];
	public $columns;
	public $orderby;
	public $limit;
	public $single_row;
	public $single_value;
	public $options_map =[];
	public $column_prefix;

	public $cache = true;
	public $cache_tags = [];
	public $cache_memory = false;

	public $primary_model = 'numbers_backend_system_modules_model_resource_map';
	public $parameters = [
		'sm_rsrcmp_resource_id' => ['name' => 'Resource #', 'domain' => 'resource_id', 'required' => true],
	];

	public function query($parameters, $options = []) {
		// columns
		$this->query->columns([
			'sm_rsrcmp_resource_id' => 'a.sm_rsrcmp_resource_id',
			'sm_rsrcmp_action_id' => 'a.sm_rsrcmp_action_id',
			'sm_action_parent_action_id' => 'b.sm_action_parent_action_id',
			'sm_action_name' => 'b.sm_action_name',
			'sm_action_icon' => 'b.sm_action_icon',
			'sm_rsrcmp_method_code' => 'a.sm_rsrcmp_method_code',
			'sm_method_name' => 'c.sm_method_name'
		]);
		// joins
		$this->query->join('INNER', new numbers_backend_system_modules_model_resource_actions(), 'b', 'ON', [
			['AND', ['a.sm_rsrcmp_action_id', '=', 'b.sm_action_id', true], false]
		]);
		$this->query->join('INNER', new numbers_backend_system_modules_model_resource_methods(), 'c', 'ON', [
			['AND', ['a.sm_rsrcmp_method_code', '=', 'c.sm_method_code', true], false]
		]);
		// where
		$this->query->where('AND', ['a.sm_rsrcmp_resource_id', '=', $parameters['sm_rsrcmp_resource_id']]);
		// order by
		$this->query->orderby(['sm_action_parent_action_id' => SORT_ASC, 'sm_rsrcmp_action_id' => SORT_ASC]);
	}

	/**
	 * @see $this->options()
	 */
	public function options_json($options = []) {
		$data = $this->get($options);
		$result = [];
		foreach ($data as $k => $v) {
			foreach ($v as $k2 => $v2) {
				foreach ($v2 as $k3 => $v3) {
					$parent = object_table_options::options_json_format_key(['method_code' => $k2]);
					// add method
					if (!isset($result[$parent])) {
						$result[$parent] = ['name' => $v3['sm_method_name'], 'parent' => null, 'disabled' => true];
					}
					// add item
					$key = object_table_options::options_json_format_key(['action_id' => $k3, 'method_code' => $k2]);
					// if we have a parent
					if (!empty($v3['sm_action_parent_action_id'])) {
						$parent = object_table_options::options_json_format_key(['action_id' => $v3['sm_action_parent_action_id'], 'method_code' => $k2]);
					}
					$result[$key] = ['name' => $v3['sm_action_name'], 'icon_class' => Html::icon(['type' => $v3['sm_action_icon'], 'class_only' => true]), '__selected_name' => i18n(null, $v3['sm_method_name']) . ': ' . i18n(null, $v3['sm_action_name']), 'parent' => $parent];
				}
			}
		}
		if (!empty($result)) {
			$converted = helper_tree::convert_by_parent($result, 'parent');
			$result = [];
			helper_tree::convert_tree_to_options_multi($converted, 0, ['name_field' => 'name'], $result);
		}
		return $result;
	}
}