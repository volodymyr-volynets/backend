<?php

namespace Numbers\Backend\Db\Common\Model;
class Models extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Models';
	public $schema;
	public $name = 'sm_models';
	public $pk = ['sm_model_id'];
	public $orderby = [
		'sm_model_name' => SORT_ASC
	];
	public $limit;
	public $column_prefix = 'sm_model_';
	public $columns = [
		'sm_model_id' => ['name' => 'Model #', 'domain' => 'model_id_sequence'],
		'sm_model_code' => ['name' => 'Model', 'domain' => 'code'],
		'sm_model_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_model_module_code' => ['name' => 'Module Code', 'domain' => 'module_code'],
		'sm_model_tenant' => ['name' => 'Tenant', 'type' => 'boolean'],
		'sm_model_period' => ['name' => 'Period', 'type' => 'boolean'],
		// widgets
		'sm_model_widget_attributes' => ['name' => 'Widget Attributes', 'type' => 'boolean'],
		'sm_model_widget_audit' => ['name' => 'Widget Audit', 'type' => 'boolean'],
		'sm_model_widget_addressees' => ['name' => 'Widget Addresses', 'type' => 'boolean'],
		// data asset
		'sm_model_da_classification' => ['name' => 'Data Asset Classification', 'type' => 'text'],
		'sm_model_da_protection' => ['name' => 'Data Asset Protection', 'type' => 'smallint', 'default' => 0],
		'sm_model_da_scope' => ['name' => 'Data Asset Scope', 'type' => 'text'],
		// other
		'sm_model_optimistic_lock' => ['name' => 'Optimistic Lock', 'type' => 'boolean'],
		'sm_model_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_models_pk' => ['type' => 'pk', 'columns' => ['sm_model_id']],
		'sm_model_code_un' => ['type' => 'unique', 'columns' => ['sm_model_code']],
	];
	public $history = false;
	public $audit = false;
	public $optimistic_lock = false;
	public $options_map = [
		'sm_model_name' => 'name',
		'sm_model_inactive' => 'inactive'
	];
	public $options_active = [
		'sm_model_inactive' => 0
	];
	public $engine = [
		'MySQLi' => 'InnoDB'
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
	 * Option fields
	 *
	 * @param array $options
	 */
	public function optionsFields($options = []) {
		if (empty($options['where']['sm_model_code'])) {
			return [];
		}
		$model = new $options['where']['sm_model_code']();
		$result = $model->columns;
		if (!empty($options['where']['include_blank_columns'])) {
			for ($i = 1; $i <= 10; $i++) {
				$result['__blank_' . $i] = ['name' => 'Blank ' . $i];
			}
			for ($i = 1; $i <= 10; $i++) {
				$result['__multiple_' . $i] = ['name' => 'Multiple ' . $i];
			}
		}
		return $result;
	}
}