<?php

namespace Numbers\Backend\System\Modules\DataSource\Subresource;
class Actions extends \Object\DataSource {
	public $db_link;
	public $db_link_flag;
	public $pk = ['id'];
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

	public $primary_model = '\Numbers\Backend\System\Modules\Model\Resource\Subresource\Map';
	public $parameters = [
		'rsrsubres_id' => ['name' => 'Subresource #', 'domain' => 'resource_id', 'required' => true],
	];

	public function query($parameters, $options = []) {
		// columns
		$this->query->columns([
			'id' => 'a.sm_rsrsubmap_action_id',
			'parent_id' => 'b.sm_action_parent_action_id',
			'name' => 'b.sm_action_name',
			'icon' => 'b.sm_action_icon',
			'inactive' => 'a.sm_rsrsubmap_inactive + b.sm_action_inactive'
		]);
		// join
		$this->query->join('INNER', new \Numbers\Backend\System\Modules\Model\Resource\Actions(), 'b', 'ON', [
			['AND', ['a.sm_rsrsubmap_action_id', '=', 'b.sm_action_id', true], false],
		]);
		// where
		$this->query->where('AND', ['a.sm_rsrsubmap_rsrsubres_id', '=', $parameters['rsrsubres_id']]);
	}

	/**
	 * @see $this->options();
	 */
	public function optionsGroupped(array $options = []) : array {
		if (!is_array($options['existing_values'])) {
			$options['existing_values'] = [$options['existing_values']];
		}
		$data = $this->get($options);
		$result = [];
		foreach ($data as $k => $v) {
			// hide inactive
			if ($v['inactive'] && !in_array($k, $options['existing_values'])) continue;
			// add item
			$result[$k] = [
				'name' => $v['name'],
				'icon_class' => \HTML::icon(['type' => $v['icon'] ?? 'fas fa-cubes', 'class_only' => true]),
				'parent' => $v['parent_id'],
				'inactive' => $v['inactive'],
			];
		}
		if (!empty($result)) {
			$converted = \Helper\Tree::convertByParent($result, 'parent');
			$result = [];
			\Helper\Tree::convertTreeToOptionsMulti($converted, 0, ['name_field' => 'name'], $result);
		}
		return $result;
	}
}