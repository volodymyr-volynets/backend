<?php

namespace Numbers\Backend\System\Modules\DataSource\Resource;
class Map extends \Object\DataSource {
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

	public $primary_model = '\Numbers\Backend\System\Modules\Model\Resource\Map';
	public $parameters = [
		'sm_rsrcmp_resource_id' => ['name' => 'Resource #', 'domain' => 'resource_id', 'required' => true],
		'existing_values' => ['name' => 'Existing Values', 'type' => 'mixed'],
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
			'sm_method_name' => 'c.sm_method_name',
			'disabled' => 'a.sm_rsrcmp_disabled',
			'inactive' => 'a.sm_rsrcmp_inactive + b.sm_action_inactive'
		]);
		// joins
		$this->query->join('INNER', new \Numbers\Backend\System\Modules\Model\Resource\Actions(), 'b', 'ON', [
			['AND', ['a.sm_rsrcmp_action_id', '=', 'b.sm_action_id', true], false]
		]);
		$this->query->join('INNER', new \Numbers\Backend\System\Modules\Model\Resource\Methods(), 'c', 'ON', [
			['AND', ['a.sm_rsrcmp_method_code', '=', 'c.sm_method_code', true], false]
		]);
		// where
		$this->query->where('AND', ['a.sm_rsrcmp_resource_id', '=', $parameters['sm_rsrcmp_resource_id']]);
		$this->query->where('AND', function(& $query) use ($parameters) {
			$query->where('OR', ['a.sm_rsrcmp_inactive + b.sm_action_inactive', '=', 0, true], false);
			if (!empty($parameters['existing_values'])) {
				$query->where('OR', ["concat_ws('::', a.sm_rsrcmp_method_code, a.sm_rsrcmp_action_id)", '=', \Object\Table\Options::optionJsonExtractKey($parameters['existing_values'], ['method_code', 'action_id'], '::')]);
			}
		});
		// order by
		$this->query->orderby(['sm_action_parent_action_id' => SORT_ASC, 'sm_rsrcmp_action_id' => SORT_ASC]);
	}

	/**
	 * @see $this->options()
	 */
	public function optionsJson($options = []) {
		$data = $this->get($options);
		$result = [];
		foreach ($data as $k => $v) {
			foreach ($v as $k2 => $v2) {
				foreach ($v2 as $k3 => $v3) {
					if ($k3 == -1) {
						$key = \Object\Table\Options::optionJsonFormatKey(['action_id' => $k3, 'method_code' => $k2]);
						$result[$key] = [
							'name' => $v3['sm_action_name'],
							'icon_class' => \HTML::icon(['type' => $v3['sm_action_icon'], 'class_only' => true]),
							//'__selected_name' => i18n(null, $v3['sm_method_name']) . ': ' . i18n(null, $v3['sm_action_name']),
							'parent' => null,
							'disabled' => $v3['disabled'],
							'inactive' => $v3['inactive']
						];
					} else {
						$parent = \Object\Table\Options::optionJsonFormatKey(['method_code' => $k2]);
						// add method
						if (!isset($result[$parent])) {
							$result[$parent] = ['name' => $v3['sm_method_name'], 'parent' => null, 'disabled' => true];
						}
						// add item
						$key = \Object\Table\Options::optionJsonFormatKey(['action_id' => $k3, 'method_code' => $k2]);
						// if we have a parent
						if (!empty($v3['sm_action_parent_action_id'])) {
							$parent = \Object\Table\Options::optionJsonFormatKey(['action_id' => $v3['sm_action_parent_action_id'], 'method_code' => $k2]);
						}
						$result[$key] = [
							'name' => $v3['sm_action_name'],
							'icon_class' => \HTML::icon(['type' => $v3['sm_action_icon'], 'class_only' => true]),
							'__selected_name' => i18n(null, $v3['sm_method_name']) . ': ' . i18n(null, $v3['sm_action_name']),
							'parent' => $parent,
							'disabled' => $v3['disabled'],
							'inactive' => $v3['inactive']
						];
					}
				}
			}
		}
		if (!empty($result)) {
			$converted = \Helper\Tree::convertByParent($result, 'parent');
			$result = [];
			\Helper\Tree::convertTreeToOptionsMulti($converted, 0, ['name_field' => 'name'], $result);
		}
		return $result;
	}
}