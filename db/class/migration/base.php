<?php

abstract class numbers_backend_db_class_migration_base {

	/**
	 * Db link
	 *
	 * @var string
	 */
	public $db_link;

	/**
	 * Developer
	 *
	 * @var string
	 */
	public $developer;

	/**
	 * Db Object
	 *
	 * @var object
	 */
	public $db_object;

	/**
	 * DDL Object
	 *
	 * @var object
	 */
	public $ddl_object;

	/**
	 * Data
	 *
	 * @var array
	 */
	public $data = [];

	/**
	 * Mode
	 *		test
	 *		commit
	 *
	 * @var string
	 */
	public $mode;

	/**
	 * Action
	 *		up
	 *		down
	 *
	 * @var string
	 */
	public $action;

	/**
	 * Executed migration ids
	 *
	 * @var array
	 */
	private $executed_migration_ids = [];

	/**
	 * Prior executed migration ids
	 *
	 * @var array
	 */
	private $prior_executed_migration_ids = [];

	/**
	 * Executed migration statistics
	 *
	 * @var array
	 */
	private $executed_migration_stats = [];

	/**
	 * New objects
	 *
	 * @var array
	 */
	private $new_objects = [];

	/**
	 * Migrate up
	 */
	abstract public function up();

	/**
	 * Migrate down
	 */
	abstract public function down();

	/**
	 * Constructor
	 *
	 * @param array $options
	 */
	public function __construct($options = []) {}

	/**
	 * Reset migration object, needed for using the same objects for test and commit modes
	 *
	 * @param array $options
	 *		mode
	 *			test
	 *			commit
	 *		action
	 *			up
	 *			down
	 */
	public function reset($options = []) {
		$this->mode = $options['mode'] ?? 'test';
		$this->action = $options['action'] ?? 'up';
		$this->data = [];
		$this->executed_migration_ids = [];
		$this->prior_executed_migration_ids = $options['prior_executed_migration_ids'] ?? null;
		$this->new_objects = [];
		$this->executed_migration_stats = [
			'sm_migration_db_link' => $this->db_link,
			'sm_migration_type' => 'migration',
			'sm_migration_action' => $this->action,
			'sm_migration_name' => str_replace('numbers_backend_db_class_migration_template_', '', get_called_class()),
			'sm_migration_developer' => $this->developer ?? 'Unknown',
			'sm_migration_inserted' => format::now('timestamp'),
			'sm_migration_rolled_back' => 0,
			'sm_migration_legend' => [],
			'sm_migration_sql_counter' => 0,
			'sm_migration_sql_changes' => []
		];
		if ($this->mode == 'commit') {
			$this->db_object = new db($this->db_link);
			$this->ddl_object = factory::get(['db', $this->db_link, 'ddl_object']);
		}
	}

	/**
	 * Process
	 *
	 * @param string $operation
	 * @param int $migration_id
	 * @param string $name
	 * @param array $data
	 * @param array $options
	 */
	protected function process($operation, $migration_id, $name, $data, $options = []) {
		// up manual rollback
		if (isset($this->prior_executed_migration_ids)) {
			if (empty($migration_id)) return;
			if (empty($this->prior_executed_migration_ids[$migration_id])) return;
		}
		// put value into data
		$this->data[] = [
			'operation' => $operation,
			'migration_id' => $migration_id,
			'name' => $name,
			'data' => $data
		];
		// generate legend
		$this->executed_migration_stats['sm_migration_legend'][] = "         * {$operation}: ";
		$this->executed_migration_stats['sm_migration_legend'][] = "          *  {$name} -  {$data['type']} - #{$migration_id}";
		// if we are committing
		if ($this->mode == 'commit') {
			// non sql changes
			if ($operation != 'sql_query') {
				// extract permissions
				if ($data['type'] == 'schema_new') {
					$this->new_objects['schema'][$data['name']] = $data['name'];
				} else if ($data['type'] == 'schema_delete') {
					$this->new_objects['schema'][$data['name']] = null;
				} else if ($data['type'] == 'table_new' || $data['type'] == 'sequence_new' || $data['type'] == 'extension_new') {
					$name = ltrim($data['schema'] . '.' . $data['name'], '.');
					$type = str_replace('_new', '', $data['type']);
					$this->new_objects[$type][$name] = $name;
				} else if ($data['type'] == 'table_delete' || $data['type'] == 'sequence_delete' || $data['type'] == 'extension_delete') {
					$name = ltrim($data['schema'] . '.' . $data['name'], '.');
					$type = str_replace('_delete', '', $data['type']);
					$this->new_objects[$type][$name] = null;
				}
				// generate sql
				$diff = [$operation => [$name => $data]];
				$ddl_result = $this->ddl_object->generate_sql_from_diff_objects($this->db_link, $diff, ['mode' => 'commit']);
				if ($ddl_result['success'] && $ddl_result['count'] > 0) {
					$sql_queries = $ddl_result['data'];
				} else {
					$sql_queries = [];
				}
			} else {
				$sql_queries = $data['sql'];
			}
			/**
			 * If schema change consists of multiple SQL queries we might have issues when half queries fails and we do not know which to rollback,
			 * for now we would rollback entire migration_id
			 */
			if (!empty($sql_queries)) {
				// remember what ids we have executed
				if (!empty($migration_id)) {
					$this->executed_migration_ids[$migration_id] = $migration_id;
				}
				// execute queries
				foreach ($sql_queries as $v) {
					$this->executed_migration_stats['sm_migration_sql_counter']++;
					$this->executed_migration_stats['sm_migration_sql_changes'][] = $v;
					$query_result = $this->db_object->query($v, $options['key'] ?? null, $options);
					if (!$query_result['success']) {
						$this->db_object->rollback();
						Throw new Exception(implode("\n", $query_result['error']));
					}
				}
			}
		}
	}

	/**
	 * Query
	 *
	 * @param int $migration_id
	 * @param string $sql
	 * @param mixed $key
	 * @param array $options
	 */
	protected function query($migration_id, $sql, $key = null, $options = []) {
		$options['key'] = $key;
		$data = [
			'type' => 'sql_query',
			'sql' => [$sql]
		];
		$this->process('sql_query', $migration_id, 'custom query', $data, $options);
	}

	/**
	 * Execute migration
	 *
	 * @param string $type
	 *		up
	 *		down
	 * @param array $options
	 * @return array
	 */
	public function execute($type = 'up', $options = []) {
		$result = [
			'success' => false,
			'error' => [],
			'permissions' => []
		];
		// wrap everyting into try/catch block because method throw exceptions
		try {
			$this->reset(['mode' => 'commit', 'action' => $type]);
			$this->db_object->begin();
			// execute up or down
			$this->{$type}();
			// log migration
			$migration_model = new numbers_backend_db_class_model_migrations();
			if ($migration_model->db_present()) {
				// insert new migration record
				$temp_result = numbers_backend_db_class_model_migrations::collection()->merge($this->executed_migration_stats);
				if (!$temp_result['success']) {
					Throw new Exception(implode("\n", $temp_result['error']));
				}
				// mark original up migration as rolled back
				if ($type == 'down') {
					$temp = $migration_model->get([
						'where' => [
							'sm_migration_db_link' => $this->db_link,
							'sm_migration_type' => 'migration',
							'sm_migration_action' => 'up',
							'sm_migration_name' => $this->executed_migration_stats['sm_migration_name'],
							'sm_migration_rolled_back' => 0
						],
						'columns' => [
							'sm_migration_id'
						]
					]);
					if (empty($temp)) {
						Throw new Exception('Could not find original up migration!');
					}
					$temp_result = numbers_backend_db_class_model_migrations::collection()->merge([
						'sm_migration_id' => key($temp),
						'sm_migration_rolled_back' => 1
					]);
					if (!$temp_result['success']) {
						Throw new Exception(implode("\n", $temp_result['error']));
					}
				}
			}
			$this->db_object->commit();
			$result['success'] = true;
			$result['permissions'] = $this->new_objects;
		} catch (Exception $e) {
			$result['error'][] = "Migration type: {$type} failed - " . $e->getMessage();
			// manual rollback for MySQL
			// todo
			/*
			if ($type = 'up' && !empty($this->executed_migration_ids)) {
				try {
					$old_ids = $this->executed_migration_ids;
					$old_stats = $this->executed_migration_stats;
					$this->reset(['mode' => 'commit', 'action' => 'down', 'prior_executed_migration_ids' => $old_ids]);
					$this->db_object->begin();
					// execute down
					$this->down();
					// log migration
					$migration_model = new numbers_backend_db_class_model_migrations();
					if ($migration_model->db_present()) {
						$old_stats['sm_migration_rolled_back'] = 1;
						$temp_result = numbers_backend_db_class_model_migrations::collection()->merge_multiple([$old_stats, $this->executed_migration_stats]);
						if (!$temp_result['success']) {
							Throw new Exception(implode("\n", $temp_result['error']));
						}
					}
					$this->db_object->commit();
					$result['error'][] = "Type: down rollback completed!";
				} catch(Exception $e2) {
					$this->db_object->rollback();
					$result['error'][] = "Type: down rollback failed - " . $e2->getMessage();
				}
			}
			*/
		}
		return $result;
	}
}