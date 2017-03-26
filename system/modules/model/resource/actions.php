<?php

class numbers_backend_system_modules_model_resource_actions extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Resource Actions';
	public $name = 'sm_resource_actions';
	public $pk = ['sm_action_id'];
	public $tenant = false;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_action_';
	public $columns = [
		'sm_action_id' => ['name' => 'Action #', 'domain' => 'action_id'],
		'sm_action_code' => ['name' => 'Code', 'domain' => 'code'],
		'sm_action_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_action_icon' => ['name' => 'Icon', 'domain' => 'icon'],
		'sm_action_parent_action_id' => ['name' => 'Parent #', 'domain' => 'action_id', 'null' => true],
		'sm_action_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
	];
	public $constraints = [
		'sm_resource_actions_pk' => ['type' => 'pk', 'columns' => ['sm_action_id']],
		'sm_action_code_un' => ['type' => 'unique', 'columns' => ['sm_action_code']],
		'sm_action_parent_action_id_fk' => [
			'type' => 'fk',
			'columns' => ['sm_action_parent_action_id'],
			'foreign_model' => 'numbers_backend_system_modules_model_resource_actions',
			'foreign_columns' => ['sm_action_id']
		]
	];
	public $history = false;
	public $audit = false;
	public $optimistic_lock = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'mysqli' => 'InnoDB'
	];

	public $cache = true;
	public $cache_tags = [];
	public $cache_memory = false;

	public $data_asset = [
		'classification' => 'public',
		'protection' => 0,
		'scope' => 'global'
	];

	/**
	 * A list of parent options
	 *
	 * @param array $options
	 * @return array
	 */
	public function get_all_parents_options($options) {
		$result = [];
		$data = helper_tree::convert_by_parent($this->get(), 'sm_action_parent_id');
		helper_tree::convert_tree_to_options_multi($data, 0, [
			'name_field' => 'sm_action_name',
			'i18n' => true
		], $result);
		return $result;
	}
}