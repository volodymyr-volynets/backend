<?php

class numbers_backend_system_controller_model_actions extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.system.default_db_link';
	public $name = 'sm_controller_actions';
	public $pk = ['sm_cntractn_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_cntractn_';
	public $columns = [
		'sm_cntractn_id' => ['name' => 'Action #', 'domain' => 'action_id'],
		'sm_cntractn_code' => ['name' => 'Code', 'domain' => 'code'],
		'sm_cntractn_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_cntractn_parent_id' => ['name' => 'Parent #', 'domain' => 'action_id', 'default' => 0],
		'sm_cntractn_inactive' => ['name' => 'Inactive', 'type' => 'boolean'],
	];
	public $constraints = [
		'sm_controller_actions_pk' => ['type' => 'pk', 'columns' => ['sm_cntractn_id']],
		'sm_cntractn_code_un' => ['type' => 'unique', 'columns' => ['sm_cntractn_code']]
	];
	public $history = false;
	public $audit = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'mysqli' => 'InnoDB'
	];

	public $cache = true;
	public $cache_tags = [];
	public $cache_memory = false;

	/**
	 * A list of parent options
	 *
	 * @param array $options
	 * @return array
	 */
	public function get_all_parents_options($options) {
		$result = [];
		$data = helper_tree::convert_by_parent($this->get(), 'sm_cntractn_parent_id');
		helper_tree::convert_tree_to_options_multi($data, 0, [
			'name_field' => 'sm_cntractn_name',
			'i18n' => true
		], $result);
		return $result;
	}
}