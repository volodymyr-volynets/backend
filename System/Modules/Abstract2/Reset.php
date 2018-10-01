<?php

namespace Numbers\Backend\System\Modules\Abstract2;
abstract class Reset {

	/**
	 * Db link
	 *
	 * @var string
	 */
	protected $db_link;

	/**
	 * Module code
	 *
	 * @var string
	 */
	protected $module_code;

	/**
	 * Module #
	 *
	 * @var int
	 */
	protected $module_id;

	/**
	 * Activation model
	 *
	 * @var string
	 */
	protected $activation_model;

	/**
	 * Constructor
	 *
	 * @param string $db_link
	 * @param string $module_code
	 * @param int $module_id
	 * @param array $options
	 */
	public function __construct(string $db_link, int $module_id, array $options = []) {
		$this->module_code = $options['module_code'] ?? null;
		$this->activation_model = $options['activation_model'] ?? null;
		$this->module_id = $module_id;
		$this->db_link = $db_link;
	}

	/**
	 * Execute (abstract)
	 */
	abstract public function execute();

	/**
	 * Process
	 *
	 * @return array
	 *		boolean success
	 *		array error
	 */
	public function process() : array {
		$result = [
			'success' => false,
			'error' => []
		];
		try {
			$db_object = new \Db($this->db_link);
			$db_object->begin();
			$this->execute();
			// reactivate
			if (!empty($this->activation_model)) {
				$class = $this->activation_model;
				$model = new $class();
				if (method_exists($model, 'activate')) {
					$temp = $model->activate();
				} else {
					$temp = $model->process();
				}
				if (!$temp['success']) {
					print_r2($temp);
					Throw new \Exception('Could not reactivate module!');
				}
			}
			$db_object->commit();
		} catch (\Exception $e) {
			$result['error'][] = $e->getMessage();
			$db_object->rollback();
			return $result;
		}
		$result['success'] = true;
		return $result;
	}

	/**
	 * Clear table
	 *
	 * @param \Object\Table $model
	 * @param array $where
	 */
	protected function clearTable(\Object\Table $model, array $where = []) {
		if (!empty($model->module_column)) {
			$where[$model->module_column] = $this->module_id;
		}
		// we need to cleanup widgets
		if (!empty($model->all_widgets)) {
			foreach ($model->all_widgets as $k => $v) {
				$widget_model = \Factory::model($v);
				$this->clearTable($widget_model);
			}
		}
		// delete from table
		$query = $model->queryBuilder()->delete();
		if (!empty($where)) {
			$query->whereMultiple('AND', $where);
		}
		$result = $query->query();
		if (!$result['success']) {
			Throw new \Exception(implode(', ', $result['error']));
		}
	}

	/**
	 * Clear sequences
	 *
	 * @param array $where
	 */
	protected function clearSequences(array $where = []) {
		$module_code = '%.' . strtolower($this->module_code) . '\_%';
		$sequences_query = \Numbers\Backend\Db\Common\Model\Sequences::queryBuilderStatic()->select();
		$sequences_query->where('AND', ['a.sm_sequence_name', 'LIKE', $module_code]);
		$sequences_result = $sequences_query->query(['sm_sequence_name']);
		// if we have sequences
		if (!empty($sequences_result['rows'])) {
			foreach ($sequences_result['rows'] as $k => $v) {
				switch ($v['sm_sequence_type']) {
					case 'tenant_simple':
					case 'tenant_advanced':
					case 'module_simple':
					case 'module_advanced':
						$extended_query = \Numbers\Backend\Db\Common\Model\Sequence\Extended::queryBuilderStatic()->update();
						$extended_query->set([
							'sm_sequence_counter' => 0
						]);
						$extended_query->where('AND', ['a.sm_sequence_tenant_id', '=', \Tenant::id()]);
						// if we have sequence per modules
						if ($v['sm_sequence_type'] == 'module_simple' || $v['sm_sequence_type'] == 'module_advanced') {
							$extended_query->where('AND', ['a.sm_sequence_module_id', '=', $this->module_id]);
						}
						$extended_query->where('AND', ['a.sm_sequence_name', '=', $k]);
						$extended_result = $extended_query->query();
						if (!$extended_result['success']) {
							Throw new \Exception(implode(', ', $extended_result['error']));
						}
						break;
					case 'global_simple': // for these we need to execute setval command
					case 'global_advanced':
						$global_sequence_query = new \Object\Query\Builder($this->db_link);
						$global_sequence_query->columns([
							'counter' => "setval('{$k}', 0)"
						]);
						$global_sequence_result = $global_sequence_query->query();
						if (!$global_sequence_result['success']) {
							Throw new \Exception(implode(', ', $global_sequence_result['error']));
						}
						break;
					default:
						Throw new \Exception('Unknown sequence type!');
				}
			}
		}
	}
}