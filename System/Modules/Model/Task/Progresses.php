<?php

namespace Numbers\Backend\System\Modules\Model\Task;
class Progresses extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Task Progresses';
	public $name = 'sm_task_progresses';
	public $pk = ['sm_tskprogress_tenant_id', 'sm_tskprogress_id'];
	public $tenant = true;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_tskprogress_';
	public $columns = [
		'sm_tskprogress_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id'],
		'sm_tskprogress_id' => ['name' => 'Progress #', 'domain' => 'big_id_sequence'],
		'sm_tskprogress_name' => ['name' => 'Name', 'domain' => 'name'],
		'sm_tskprogress_percent' => ['name' => 'Percent', 'type' => 'numeric', 'precision' => 14, 'scale' => 2, 'default' => 0],
		'sm_tskprogress_task_total' => ['name' => 'Tasks Total', 'type' => 'integer', 'default' => 1],
		'sm_tskprogress_task_completed' => ['name' => 'Tasks Completed', 'type' => 'integer', 'default' => 0],
		'sm_tskprogress_finish' => ['name' => 'Finish', 'type' => 'boolean'],
		'sm_tskprogress_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_task_progresses_pk' => ['type' => 'pk', 'columns' => ['sm_tskprogress_tenant_id', 'sm_tskprogress_id']],
	];
	public $indexes = [];
	public $history = false;
	public $audit = false;
	public $optimistic_lock = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'MySQLi' => 'InnoDB'
	];

	public $cache = true;
	public $cache_tags = [];
	public $cache_memory = false;

	public $who = [
		'inserted' => true,
		'updated' => true
	];

	public $data_asset = [
		'classification' => 'proprietary',
		'protection' => 1,
		'scope' => 'global'
	];

	/**
	 * Start tracking
	 *
	 * @param int $id
	 * @param string $name
	 * @param int $total
	 * @param int $completed
	 * @return int
	 */
	public static function startTracking(?int $id, string $name, int $total, int $completed) : int {
		$data = [
			'sm_tskprogress_tenant_id' => \Tenant::id(),
			'sm_tskprogress_name' => $name,
			'sm_tskprogress_task_total' => $total,
			'sm_tskprogress_task_completed' => $completed,
			'sm_tskprogress_percent' => round($completed / $total * 100, 2),
			'sm_tskprogress_finish' => 0,
			'sm_tskprogress_inactive' => 0
		];
		if ($id) {
			$data['sm_tskprogress_id'] = $id;
		}
		$result = self::collectionStatic(['skip_acl' => true])->merge($data);
		if ($id) {
			return $id;
		} else {
			return $result['new_serials']['sm_tskprogress_id'];
		}
	}

	/**
	 * Make progress
	 *
	 * @param int $id
	 * @param int $total
	 * @param int $completed
	 * @return array
	 */
	public static function makeProgress(int $id, int $total, int $completed) : array {
		$query = \Numbers\Backend\System\Modules\Model\Task\Progresses::queryBuilderStatic();
		$query->update();
		$query->set([
			'sm_tskprogress_tenant_id' => \Tenant::id(),
			'sm_tskprogress_id' => $id,
			'sm_tskprogress_task_total' => $total,
			'sm_tskprogress_task_completed' => $completed,
			'sm_tskprogress_percent' => round($completed / $total * 100, 2),
			'sm_tskprogress_finish' => 0,
			'sm_tskprogress_inactive' => 0
		]);
		$query->where('AND', ['sm_tskprogress_tenant_id', '=', \Tenant::id()]);
		$query->where('AND', ['sm_tskprogress_id', '=', $id]);
		$query->dblink([]);
		return $query->query();
	}
}