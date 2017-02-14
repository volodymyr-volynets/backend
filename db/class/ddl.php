<?php

class numbers_backend_db_class_ddl {

	/**
	 * All objects would be kept here
	 *
	 * @var array 
	 */
	public $objects = [];

	/**
	 * Number of objects
	 *
	 * @var int
	 */
	public $count = [];

	/**
	 * Adding object
	 *
	 * @param array $object
	 * @param string $db_link
	 */
	public function object_add($object, $db_link) {
		// object counters
		if (!isset($this->count[$db_link])) $this->count[$db_link] = [];
		$this->count[$db_link]['Total'] = $this->count[$db_link]['Total'] ?? 0;
		$type = ucwords(str_replace('_', ' ', $object['type'])) . '(s)';
		if (!isset($this->count[$db_link][$type])) $this->count[$db_link][$type] = 0;
		// if based on object
		if (in_array($object['type'], ['table', 'sequence', 'function', 'extension'])) {
			$this->objects[$db_link][$object['type']][$object['schema']][$object['name']] = $object;
		} else if ($object['type'] == 'schema') {
			$this->objects[$db_link][$object['type']][$object['name']] = $object;
		} else if ($object['type'] == 'constraint' || $object['type'] == 'index') {
			$this->objects[$db_link][$object['type']][$object['schema']][$object['table']][$object['name']] = $object;
		} else if (in_array($object['type'], ['column_new', 'column_change'])) {
			$this->objects[$db_link]['table'][$object['schema']][$object['table']]['data']['columns'][$object['name']] = $object['data'];
		} else if (in_array($object['type'], ['table_owner', 'sequence_owner'])) {
			$temp = str_replace('_owner', '', $object['type']);
			$this->objects[$db_link][$temp][$object['schema']][$object['name']]['data']['owner'] = $object['owner'];
		} else if ($object['type'] == 'schema_owner') {
			$temp = str_replace('_owner', '', $object['type']);
			$this->objects[$db_link][$temp][$object['schema']]['data']['owner'] = $object['owner'];
		}
		$this->count[$db_link]['Total']++;
		$this->count[$db_link][$type]++;
	}

	/**
	 * Remove object
	 *
	 * @param array $object
	 * @param string $db_link
	 */
	public function object_remove($object, $db_link) {
		// object counters
		if (!isset($this->count[$db_link])) $this->count[$db_link] = [];
		$this->count[$db_link]['Total'] = $this->count[$db_link]['Total'] ?? 0;
		if ($object['type'] == 'column_delete') {
			$type = ucwords('column new') . '(s)';
		} else {
			$type = ucwords(str_replace('_', ' ', $object['type'])) . '(s)';
		}
		// if based on object
		if (in_array($object['type'], ['table', 'sequence', 'function', 'extension'])) {
			unset($this->objects[$db_link][$object['type']][$object['schema']][$object['name']]);
		} else if ($object['type'] == 'schema') {
			unset($this->objects[$db_link][$object['type']][$object['name']]);
		} else if ($object['type'] == 'constraint' || $object['type'] == 'index') {
			unset($this->objects[$db_link][$object['type']][$object['schema']][$object['table']][$object['name']]);
		} else if (in_array($object['type'], ['column_delete'])) {
			unset($this->objects[$db_link]['table'][$object['schema']][$object['table']]['data']['columns'][$object['name']]);
		}
		$this->count[$db_link]['Total']--;
		$this->count[$db_link][$type]--;
	}

	/**
	 * Process model
	 *
	 * @param string $model_class
	 * @param array $options
	 *		db_link
	 *		db_schema_owner
	 * @return array
	 */
	public function process_table_model($model_class, $options = []) {
		$result = [
			'success' => false,
			'error' => []
		];
		do {
			// model
			$model = is_object($model_class) ? $model_class : factory::model($model_class, true, $options);
			// skip tables with different db_link
			if ($model->db_link != ($options['db_link'] ?? 'default')) {
				$result['success'] = true;
				break;
			}
			// table must have columns
			if (empty($model->columns)) {
				$result['error'][] = 'Table ' . $model->full_table_name . ' must have atleast one column!';
				break;
			}
			// columns
			$processed_columns = object_data_common::process_domains_and_types($model->columns);
			$attributes = array_merge(object_data_common::$attributes['global'], object_data_common::$attributes['schema']);
			$columns = [];
			foreach ($processed_columns as $k => $v) {
				$temp = [];
				foreach ($attributes as $v2) {
					if (array_key_exists($v2, $v)) {
						$temp[$v2] = $v[$v2];
					}
				}
				$columns[$k] = $temp;
				// add sequence
				if (!empty($temp['sequence'])) {
					$this->object_add([
						'type' => 'sequence',
						'schema' => $model->schema,
						'name' => $model->name . '_' . $k . '_seq',
						'data' => [
							'owner' => $options['db_schema_owner'] ?? null,
							'full_sequence_name' => $model->full_table_name . '_' . $k . '_seq',
							'full_table_name' => $model->full_table_name, // a must
							// todo: handle tenant and ledger
							'type' => 'global_simple',
							'prefix' => null,
							'suffix' => null,
							'length' => 0
						]
					], $model->db_link);
				}
			}
			// add schema
			if (!empty($model->schema)) {
				$this->object_add([
					'type' => 'schema',
					'name' => $model->schema,
					'data' => [
						'owner' => $options['db_schema_owner'] ?? null,
						'name' => $model->schema
					]
				], $model->db_link);
			}
			// add table
			$this->object_add([
				'type' => 'table',
				'schema' => $model->schema,
				'name' => $model->name,
				'data' => [
					'columns' => $columns,
					'owner' => $options['db_schema_owner'] ?? null,
					'engine' => $model->engine,
					'full_table_name' => $model->full_table_name
				]
			], $model->db_link);
			// history
			/* todo, refactor
			if ($model->history) {
				if (empty($model->who['inserted']) || empty($model->who['updated'])) {
					$result['error'][] = 'History table ' . $model->name . ' must have inserted and updated who timestamps!';
				}
				// fix columns with serial types
				$columns_history = $columns;
				foreach ($model->pk as $v) {
					foreach (['serial' => 'integer', 'bigserial' => 'bigint', 'smallserial' => 'smallint'] as $k2 => $v2) {
						if ($columns_history[$v]['type'] == $k2) $columns_history[$v]['type'] = $v2;
					}
				}
				// add new history table
				$this->object_add([
					'type' => 'table',
					'schema' => '',
					'name' => $model->name . '__history',
					'data' => [
						'columns' => $columns_history,
						'owner' => $owner,
						'engine' => $model->engine,
						'full_table_name' => $model->history_name
					]], $model->db_link);
			}
			*/
			// processing constraints
			if (!empty($model->constraints)) {
				foreach ($model->constraints as $k => $v) {
					$v['full_table_name'] = $model->full_table_name;
					// additional processing for fk type constraints
					if ($v['type'] == 'fk') {
						$temp_object = factory::model($v['foreign_model'], true, $options);
						$v['foreign_table'] = $temp_object->full_table_name;
						// default options
						if (empty($v['options'])) $v['options'] = [];
						if (empty($v['options']['match'])) $v['options']['match'] = 'simple';
						if (empty($v['options']['update'])) $v['options']['update'] = 'cascade';
						if (empty($v['options']['delete'])) $v['options']['delete'] = 'restrict';
					}
					// add constraint
					$this->object_add([
						'type' => 'constraint',
						'schema' => $model->schema,
						'table' => $model->name,
						'name' => $k,
						'data' => $v
					], $model->db_link);
				}
			}
			// processing indexes
			if (!empty($model->indexes)) {
				foreach ($model->indexes as $k => $v) {
					$v['full_table_name'] = $model->full_table_name;
					// add index
					$this->object_add([
						'type' => 'index',
						'schema' => $model->schema,
						'table' => $model->name,
						'name' => $k,
						'data' => $v
					], $model->db_link);
				}
			}
			// if we got here - we are ok
			$result['success'] = true;
		} while(0);
		return $result;
	}

	/**
	 * Process sequence
	 *
	 * @param string $model_class
	 * @param array $options
	 *		db_link
	 * @return array
	 */
	public function process_sequence_model($model_class, $options = []) {
		$result = [
			'success' => false,
			'error' => []
		];
		do {
			// model
			$model = factory::model($model_class, true);
			// skip tables with different db_link
			if ($model->db_link != ($options['db_link'] ?? 'default')) {
				$result['success'] = true;
				break;
			}
			// process sequence name and schema
			$this->object_add([
				'type' => 'sequence',
				'schema' => $model->schema,
				'name' => $model->name,
				'data' => [
					'owner' => $options['db_schema_owner'] ?? null,
					'full_sequence_name' => $model->full_sequence_name,
					'full_table_name' => null, // a must
					'type' => $model->type,
					'prefix' => $model->prefix,
					'suffix' => $model->suffix,
					'length' => $model->length
				]
			], $model->db_link);
			// if we got here - we are ok
			$result['success'] = true;
		} while(0);
		return $result;
	}

	/**
	 * Process function model
	 *
	 * @param string $model_class
	 * @param array $options
	 *		db_link
	 * @return array
	 */
	public function process_function_model($model_class, $options = []) {
		$result = [
			'success' => false,
			'error' => []
		];
		do {
			// model
			$model = factory::model($model_class, true);
			// skip tables with different db_link
			if ($model->db_link != ($options['db_link'] ?? 'default')) {
				$result['success'] = true;
				break;
			}
			// owner & backend
			$db = factory::get(['db', $model->db_link]);
			$ddl_object = $db['ddl_object'];
			$ddl_backend = $db['backend'];
			$owner = $db['object']->connect_options['username'];
			// if we do not have sql or its natively supported we exit
			if (empty($model->function_sql[$ddl_backend])) {
				$result['success'] = true;
				break;
			}
			// we need to unset function definition
			$sql_full_temp = $model->function_sql[$ddl_backend];
			unset($sql_full_temp['definition']);
			$this->object_add([
				'type' => 'function',
				'schema' => '',
				'name' => $model->name,
				'data' => [
					'owner' => $owner,
					'full_function_name' => $model->name,
					'sql_full' => implode('', $sql_full_temp),
					'sql_parts' => $model->function_sql[$ddl_backend]
				]
			], $model->db_link);

			// if we got here - we are ok
			$result['success'] = true;
		} while(0);
		return $result;
	}

	/**
	 * Process extension
	 *
	 * @param string $model_class
	 * @param array $options
	 *		db_link
	 * @return array
	 */
	public function process_extension_model($model_class, $options = []) {
		$result = [
			'success' => false,
			'error' => []
		];
		do {
			// model
			$model = factory::model($model_class, true);
			// skip tables with different db_link
			if ($model->db_link != ($options['db_link'] ?? 'default')) {
				$result['success'] = true;
				break;
			}
			// process extension name and schema
			$this->object_add([
				'type' => 'extension',
				'schema' => $model->schema,
				'name' => $model->name,
				'backends' => $model->backends
			], $model->db_link);
			// if we got here - we are ok
			$result['success'] = true;
		} while(0);
		return $result;
	}

	/**
	 * Compare two schemas
	 *
	 * @param array $obj_master
	 * @param array $obj_slave
	 * @param array $options
	 *		type
	 *			schema
	 *			migration
	 *		db_link - mandatory for type = schema
	 * @return array
	 */
	public function compare_schemas($obj_master, $obj_slave, $options = []) {
		$result = [
			'success' => false,
			'error' => [],
			'hint' => [],
			'up' => [],
			'down' => [],
			'count' => 0
		];
		// mode and backend and db_link
		$options['type'] = $options['type'] ?? null;
		if (!empty($options['db_link'])) {
			$ddl_object = factory::get(['db', $options['db_link'], 'ddl_object']);
			$options['backend'] = factory::get(['db', $options['db_link'], 'backend']);
		}
		// before execution
		$result['up']['before_execution'] = [];
		// delete first
		$result['up']['delete_triggers'] = [];
		$result['up']['delete_views'] = [];
		$result['up']['delete_constraints'] = [];
		$result['up']['delete_indexes'] = [];
		$result['up']['delete_functions'] = [];
		$result['up']['delete_sequences'] = []; // before tables
		$result['up']['delete_columns'] = [];
		$result['up']['delete_tables'] = [];
		$result['up']['delete_schemas'] = [];
		$result['up']['delete_extensions'] = []; // last
		// new/change second
		$result['up']['new_extensions'] = []; // first
		$result['up']['new_schemas'] = [];
		$result['up']['new_schema_owners'] = [];
		$result['up']['new_tables'] = []; // after schemas
		$result['up']['new_table_owners'] = [];
		$result['up']['new_table_engines'] = []; // todo
		$result['up']['new_columns'] = [];
		$result['up']['change_columns'] = [];
		$result['up']['new_sequences'] = []; // after tables
		$result['up']['new_sequence_owners'] = [];
		$result['up']['new_constraints'] = [];
		$result['up']['new_indexes'] = [];
		//$result['up']['new_views'] = []; // views goes after we add columns
		//$result['up']['change_views'] = [];
		//$result['up']['new_view_owners'] = [];
		$result['up']['new_functions'] = [];
		$result['up']['new_function_owners'] = [];
		//$result['up']['new_triggers'] = []; // after functions
		//$result['up']['change_triggers'] = [];
		// preset reverse array
		$result['down'] = $result['up'];

		// add extension
		if (!empty($obj_master['extension'])) {
			foreach ($obj_master['extension'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					// in schema mode we skip not related extensions
					if ($options['type'] == 'schema' && !in_array($options['backend'], $v2['backends'])) continue;
					$v2['migration_id'] = $result['count'] + 1;
					// extension must be present
					if (empty($obj_slave['extension'][$k][$k2])) {
						// up
						$v2['type'] = 'extension_new';
						$result['up']['new_extensions'][$k . '.' . $k2] = $v2;
						// down
						$v2['type'] = 'extension_delete';
						$result['down']['delete_extensions'][$k . '.' . $k2] = $v2;
						// count
						$result['count']++;
					}
				}
			}
		}

		// delete extensions
		if (!empty($obj_slave['extension'])) {
			foreach ($obj_slave['extension'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					$v2['migration_id'] = $result['count'] + 1;
					if (empty($obj_master['extension'][$k][$k2])) {
						// up
						$v2['type'] = 'extension_delete';
						$result['up']['delete_extensions'][$k . '.' . $k2] = $v2;
						// down
						$v2['type'] = 'extension_new';
						$result['down']['new_extensions'][$k . '.' . $k2] = $v2;
						// count
						$result['count']++;
					}
				}
			}
		}

		// new schemas
		if (!empty($obj_master['schema'])) {
			foreach ($obj_master['schema'] as $k => $v) {
				$v['migration_id'] = $result['count'] + 1;
				// new schema
				if (empty($obj_slave['schema'][$k])) {
					// up
					$v['type'] = 'schema_new';
					$result['up']['new_schemas'][$k] = $v;
					// down
					$v['type'] = 'schema_delete';
					$result['down']['delete_schemas'][$k] = $v;
					// count
					$result['count']++;
				} else if ($v['data']['owner'] != $obj_slave['schema'][$k]['data']['owner']) { // owner
					// up
					$result['up']['new_schema_owners'][$k] = [
						'type' => 'schema_owner',
						'schema' => $k,
						'name' => null,
						'owner' => $v['data']['owner'],
						'migration_id' => $result['count'] + 1
					];
					// down
					$result['down']['new_schema_owners'][$k] = [
						'type' => 'schema_owner',
						'schema' => $k,
						'name' => null,
						'owner' => $obj_slave['schema'][$k]['data']['owner'],
						'migration_id' => $result['count'] + 1
					];
					// count
					$result['count']++;
				}
			}
		}

		// delete schema
		if (!empty($obj_slave['schema'])) {
			foreach ($obj_slave['schema'] as $k => $v) {
				$v['migration_id'] = $result['count'] + 1;
				if (empty($obj_master['schema'][$k])) {
					// up
					$v['type'] = 'schema_delete';
					$result['up']['delete_schemas'][$k] = $v;
					// down
					$v['type'] = 'schema_new';
					$result['down']['new_schemas'][$k] = $v;
					// count
					$result['count']++;
				}
			}
		}

		// new tables
		if (!empty($obj_master['table'])) {
			foreach ($obj_master['table'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					$v2['migration_id'] = $result['count'] + 1;
					// new table
					if (empty($obj_slave['table'][$k][$k2])) {
						// up
						$v2['type'] = 'table_new';
						$result['up']['new_tables'][$v2['data']['full_table_name']] = $v2;
						// down
						$v2['type'] = 'table_delete';
						$result['down']['delete_tables'][$v2['data']['full_table_name']] = $v2;
						// count
						$result['count']++;
					} else if ($v2['data']['owner'] != $obj_slave['table'][$k][$k2]['data']['owner']) { // owner
						// up
						$result['up']['new_table_owners'][$v2['data']['full_table_name']] = [
							'type' => 'table_owner',
							'schema' => $k,
							'name' => $k2,
							'owner' => $v2['data']['owner'],
							'migration_id' => $result['count'] + 1
						];
						// down
						$result['down']['new_table_owners'][$v2['data']['full_table_name']] = [
							'type' => 'table_owner',
							'schema' => $k,
							'name' => $k2,
							'owner' => $obj_slave['table'][$k][$k2]['data']['owner'],
							'migration_id' => $result['count'] + 1
						];
						// count
						$result['count']++;
					}
				}
			}
		}

		// delete table
		if (isset($obj_slave['table'])) {
			foreach ($obj_slave['table'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					$v2['migration_id'] = $result['count'] + 1;
					if (empty($obj_master['table'][$k][$k2])) {
						// up
						$v2['type'] = 'table_delete';
						$result['up']['delete_tables'][$v2['data']['full_table_name']] = $v2;
						// down
						$v2['type'] = 'table_new';
						$result['down']['new_tables'][$v2['data']['full_table_name']] = $v2;
						// count
						$result['count']++;
					}
				}
			}
		}

		// new columns
		if (!empty($obj_master['table'])) {
			foreach ($obj_master['table'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					// if we have new table we do not need to check for new columns
					if (!empty($result['up']['new_tables'][$v2['data']['full_table_name']])) continue;
					// finding new column
					foreach ($v2['data']['columns'] as $k3 => $v3) {
						$name = ltrim($k . '.' . $k2 . '.' . $k3, '.');
						// schema comparison
						if ($options['type'] == 'schema') {
							$master_compare = $ddl_object->column_sql_type($v3);
							$compare_columns = ['sql_type', 'null', 'default'];
							$slave_compare = $obj_slave['table'][$k][$k2]['data']['columns'][$k3];
						} else { // migration comparison
							$master_compare = $this->column_sql_type_base($v3);
							$compare_columns = ['type', 'null', 'default', 'length', 'precision', 'scale', 'sequence'];
							$slave_compare = $this->column_sql_type_base($obj_slave['table'][$k][$k2]['data']['columns'][$k3]);
						}
						// new columns
						if (empty($obj_slave['table'][$k][$k2]['data']['columns'][$k3])) {
							$master_compare['sql_type'] = null;
							// up
							$result['up']['new_columns'][$name] = [
								'type' => 'column_new',
								'schema' => $k,
								'table' => $k2,
								'name' => $k3,
								'data' => $master_compare,
								'data_old' => [],
								'full_table_name' => $v2['data']['full_table_name'],
								'migration_id' => $result['count'] + 1
							];
							// down
							$result['down']['delete_columns'][$name] = [
								'type' => 'column_delete',
								'schema' => $k,
								'table' => $k2,
								'name' => $k3,
								'data' => [],
								'data_old' => $master_compare,
								'full_table_name' => $v2['data']['full_table_name'],
								'migration_id' => $result['count'] + 1
							];
							// count
							$result['count']++;
						} else { // column changes
							// comparing
							$temp_error = false;
							foreach ($compare_columns as $v88) {
								if ($master_compare[$v88] !== $slave_compare[$v88]) {
									$temp_error = true;
									break;
								}
							}
							// add changes
							if ($temp_error) {
								$master_compare['sql_type'] = null;
								$slave_compare['sql_type'] = null;
								// up
								$result['up']['change_columns'][$name] = [
									'type' => 'column_change',
									'schema' => $k,
									'table' => $k2,
									'name' => $k3,
									'data' => $master_compare,
									'data_old' => $slave_compare,
									'full_table_name' => $v2['data']['full_table_name'],
									'migration_id' => $result['count'] + 1
								];
								// down
								$result['down']['change_columns'][$name] = [
									'type' => 'column_change',
									'schema' => $k,
									'table' => $k2,
									'name' => $k3,
									'data' => $slave_compare,
									'data_old' => $master_compare,
									'full_table_name' => $v2['data']['full_table_name'],
									'migration_id' => $result['count'] + 1
								];
								// count
								$result['count']++;
							}
						}
					}
					// finding columns to be deleted
					foreach ($obj_slave['table'][$k][$k2]['data']['columns'] as $k3 => $v3) {
						if (empty($obj_master['table'][$k][$k2]['data']['columns'][$k3])) {
							$name = ltrim($k . '.' . $k2 . '.' . $k3, '.');
							$slave_compare = $this->column_sql_type_base($v3);
							$slave_compare['sql_type'] = null;
							// up
							$result['up']['delete_columns'][$name] = [
								'type' => 'column_delete',
								'schema' => $k,
								'table' => $k2,
								'name' => $k3,
								'data' => [],
								'data_old' => $slave_compare,
								'full_table_name' => $v2['data']['full_table_name'],
								'migration_id' => $result['count'] + 1
							];
							// down
							$result['down']['new_columns'][$name] = [
								'type' => 'column_new',
								'schema' => $k,
								'table' => $k2,
								'name' => $k3,
								'data' => $slave_compare,
								'data_old' => [],
								'full_table_name' => $v2['data']['full_table_name'],
								'migration_id' => $result['count'] + 1
							];
							// count
							$result['count']++;
						}
					}
				}
			}
		}

		// pk constraints can have different names
		if (!empty($obj_slave['constraint'])) {
			foreach ($obj_slave['constraint'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					foreach ($v2 as $k3 => $v3) {
						if ($v3['type'] == 'pk') {
							$found = null;
							if (!empty($obj_master['constraint'][$k][$k2])) {
								foreach ($obj_master['constraint'][$k][$k2] as $k4 => $v4) {
									if ($v4['type'] == 'pk') {
										$found = $k4;
										break;
									}
								}
							}
							if (!empty($found)) {
								$temp = $obj_slave['constraint'][$k][$k2][$k3];
								unset($obj_slave['constraint'][$k][$k2][$k3]);
								$obj_slave['constraint'][$k][$k2][$found] = $temp;
							}
						}
					}
				}
			}
		}

		// new constraints
		if (!empty($obj_master['constraint'])) {
			foreach ($obj_master['constraint'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					foreach ($v2 as $k3 => $v3) {
						$name = ltrim($k . '.' . $k2 . '.' . $k3, '.');
						if (empty($obj_slave['constraint'][$k][$k2][$k3])) {
							$v3['migration_id'] = $result['count'] + 1;
							// up
							$v3['type'] = 'constraint_new';
							$result['up']['new_constraints'][$name] = $v3;
							// down
							$v3['type'] = 'constraint_delete';
							$result['down']['delete_constraints'][$name] = $v3;
							// count
							$result['count']++;
						} else {
							// comparing structure
							$temp_error = false;
							if ($v3['data']['type'] != $obj_slave['constraint'][$k][$k2][$k3]['data']['type']) {
								$temp_error = true;
							}
							if ($v3['data']['full_table_name'] != $obj_slave['constraint'][$k][$k2][$k3]['data']['full_table_name']) {
								$temp_error = true;
							}
							if (!array_compare_level1($v3['data']['columns'], $obj_slave['constraint'][$k][$k2][$k3]['data']['columns'])) {
								$temp_error = true;
							}
							// additiona verifications for fk constraints
							if ($v3['data']['type'] == 'fk') {
								if ($v3['data']['foreign_table'] != $obj_slave['constraint'][$k][$k2][$k3]['data']['foreign_table']) {
									$temp_error = true;
								}
								if (!array_compare_level1($v3['data']['foreign_columns'], $obj_slave['constraint'][$k][$k2][$k3]['data']['foreign_columns'])) {
									$temp_error = true;
								}
								// compare options
								if ($v3['data']['options']['match'] !== $obj_slave['constraint'][$k][$k2][$k3]['data']['options']['match']) $temp_error = true;
								if ($v3['data']['options']['update'] !== $obj_slave['constraint'][$k][$k2][$k3]['data']['options']['update']) $temp_error = true;
								if ($v3['data']['options']['delete'] !== $obj_slave['constraint'][$k][$k2][$k3]['data']['options']['delete']) $temp_error = true;
							}
							// if we have an error we rebuild
							if ($temp_error) {
								$v3['migration_id'] = $result['count'] + 1;
								$v3_slave = $obj_slave['constraint'][$k][$k2][$k3];
								$v3_slave['migration_id'] = $result['count'] + 1;
								// up
								$v3_slave['type'] = 'constraint_delete';
								$result['up']['delete_constraints'][$name] = $v3_slave;
								$v3['type'] = 'constraint_new';
								$result['up']['new_constraints'][$name] = $v3;
								// down
								$v3['type'] = 'constraint_delete';
								$result['down']['delete_constraints'][$name] = $v3;
								$v3_slave['type'] = 'constraint_new';
								$result['down']['new_constraints'][$name] = $v3_slave;
								// count
								$result['count']++;
							}
						}
					}
				}
			}
		}

		// delete constraints
		if (isset($obj_slave['constraint'])) {
			foreach ($obj_slave['constraint'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					foreach ($v2 as $k3 => $v3) {
						$name = ltrim($k . '.' . $k2 . '.' . $k3, '.');
						$v3['migration_id'] = $result['count'] + 1;
						if (empty($obj_master['constraint'][$k][$k2][$k3])) {
							// up
							$v3['type'] = 'constraint_delete';
							$result['up']['delete_constraints'][$name] = $v3;
							// down
							$v3['type'] = 'constraint_new';
							$result['down']['new_constraints'][$name] = $v3;
							// count
							$result['count']++;
						}
					}
				}
			}
		}

		// new/changed indexes
		if (!empty($obj_master['index'])) {
			foreach ($obj_master['index'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					foreach ($v2 as $k3 => $v3) {
						$name = ltrim($k . '.' . $k2 . '.' . $k3, '.');
						if (empty($obj_slave['index'][$k][$k2][$k3])) {
							$v3['migration_id'] = $result['count'] + 1;
							// up
							$v3['type'] = 'index_new';
							$result['up']['new_indexes'][$name] = $v3;
							// down
							$v3['type'] = 'index_delete';
							$result['down']['delete_indexes'][$name] = $v3;
							// count
							$result['count']++;
						} else {
							// comparing structure
							$temp_error = false;
							if ($v3['data']['type'] != $obj_slave['index'][$k][$k2][$k3]['data']['type']) {
								$temp_error = true;
							}
							if (!array_compare_level1($v3['data']['columns'], $obj_slave['index'][$k][$k2][$k3]['data']['columns'])) {
								$temp_error = true;
							}
							// if discrepancy
							if ($temp_error) {
								$v3['migration_id'] = $result['count'] + 1;
								$v3_slave = $obj_slave['index'][$k][$k2][$k3];
								$v3_slave['migration_id'] = $result['count'] + 1;
								// up
								$v3_slave['type'] = 'index_delete';
								$result['up']['delete_indexes'][$name] = $v3_slave;
								$v3['type'] = 'index_new';
								$result['up']['new_indexes'][$name] = $v3;
								// down
								$v3['type'] = 'index_delete';
								$result['down']['delete_indexes'][$name] = $v3;
								$v3_slave['type'] = 'index_new';
								$result['down']['new_indexes'][$name] = $v3_slave;
								// count
								$result['count']++;
							}
						}
					}
				}
			}
		}

		// delete indexes
		if (isset($obj_slave['index'])) {
			foreach ($obj_slave['index'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					foreach ($v2 as $k3 => $v3) {
						if (empty($obj_master['index'][$k][$k2][$k3])) {
							$name = ltrim($k . '.' . $k2 . '.' . $k3, '.');
							$v3['migration_id'] = $result['count'] + 1;
							// up
							$v3['type'] = 'index_delete';
							$result['up']['delete_indexes'][$name] = $v3;
							// down
							$v3['type'] = 'index_new';
							$result['down']['new_indexes'][$name] = $v3;
							// count
							$result['count']++;
						}
					}
				}
			}
		}

		// new sequences
		if (!empty($obj_master['sequence'])) {
			foreach ($obj_master['sequence'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					$name = ltrim($k . '.' . $k2, '.');
					$v2['migration_id'] = $result['count'] + 1;
					// new sequence
					if (empty($obj_slave['sequence'][$k][$k2])) {
						// up
						$v2['type'] = 'sequence_new';
						$result['up']['new_sequences'][$name] = $v2;
						// down
						$v2['type'] = 'sequence_delete';
						$result['down']['delete_sequences'][$name] = $v2;
						// count
						$result['count']++;
					} else if ($v2['data']['owner'] != $obj_slave['sequence'][$k][$k2]['data']['owner']) { // owner
						// up
						$result['up']['new_sequence_owners'][$name] = [
							'type' => 'sequence_owner',
							'schema' => $k,
							'name' => $k2,
							'owner' => $v2['data']['owner'],
							'migration_id' => $result['count'] + 1
						];
						// down
						$result['down']['new_sequence_owners'][$name] = [
							'type' => 'sequence_owner',
							'schema' => $k,
							'name' => $k2,
							'owner' => $obj_slave['sequence'][$k][$k2]['data']['owner'],
							'migration_id' => $result['count'] + 1
						];
						// count
						$result['count']++;
					}
				}
			}
		}

		// delete sequences
		if (!empty($obj_slave['sequence'])) {
			foreach ($obj_slave['sequence'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					$name = ltrim($k . '.' . $k2, '.');
					$v2['migration_id'] = $result['count'] + 1;
					if (empty($obj_master['sequence'][$k][$k2])) {
						// up
						$v2['type'] = 'sequence_delete';
						$result['up']['delete_sequences'][$name] = $v2;
						// down
						$v2['type'] = 'sequence_new';
						$result['down']['new_sequences'][$name] = $v2;
						// count
						$result['count']++;
					}
				}
			}
		}

		// functions
		// todo
		if (!empty($obj_master['function'])) {
			foreach ($obj_master['function'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					if (empty($obj_slave['function'][$k][$k2])) {
						$result['up']['new_functions'][$k . '.' . $k2] = [
							'type' => 'function_new',
							'name' => $v2['full_function_name'],
							'owner' => $v2['owner'],
							'data' => $v2
						];
						$result['count']++;
					} else {
						// todo add here !!!
						// if function has changed
						if ($options['backend'] == 'mysqli') {
							if ($v2['sql_parts']['body'] != $obj_slave['function'][$k][$k2]['sql_parts']['body']) {
								$result['up']['delete_functions'][$k . '.' . $k2] = array('type' => 'function_delete', 'name' => $v2['full_function_name'], 'data' => $v2);
								$result['up']['new_functions'][$k . '.' . $k2] = array('type' => 'function_new', 'name' => $v2['full_function_name'], 'owner' => $v2['owner'], 'data' => $v2);
								$result['count']++;
							}
						} else if ($options['backend'] == 'pgsql') {
							if (numbers_backend_db_class_ddl::sanitize_function($v2['sql_full']) != numbers_backend_db_class_ddl::sanitize_function($obj_slave['function'][$k][$k2]['sql_full'])) {
								$result['up']['delete_functions'][$k . '.' . $k2] = array('type' => 'function_delete', 'name' => $v2['full_function_name'], 'data' => $v2);
								$result['up']['new_functions'][$k . '.' . $k2] = array('type' => 'function_new', 'name' => $v2['full_function_name'], 'owner' => $v2['owner'], 'data' => $v2);
								$result['count']++;
							} else if ($v2['owner'] != $obj_slave['function'][$k][$k2]['owner']) { // checking owner
								$result['up']['new_function_owners'][$k . '.' . $k2] = array('type'=>'function_owner', 'name' => $v2['full_function_name'], 'owner' => $v2['owner'], 'data' => $v2);
								$result['count']++;
							}
						}
					}
				}
			}
		}

		// delete function
		// todo
		if (!empty($obj_slave['function'])) {
			foreach ($obj_slave['function'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					if (empty($obj_master['function'][$k][$k2])) {
						$result['up']['delete_functions'][$k . '.' . $k2] = [
							'type' => 'function_delete',
							'name' => $v2['full_function_name'],
							'data' => $v2
						];
						$result['count']++;
					}
				}
			}
		}

		// if we delete tables there's no need to delete constrants and/or indexes
		foreach (['up', 'down'] as $k0) {
			foreach ($result[$k0]['delete_tables'] as $k => $v) {
				// unsetting constraints
				foreach ($result[$k0]['delete_constraints'] as $k2 => $v2) {
					if ($v2['data']['full_table_name'] == $k) {
						unset($result[$k0]['delete_constraints'][$k2]);
					}
				}
				// unsetting indexes
				foreach ($result[$k0]['delete_indexes'] as $k2 => $v2) {
					if ($v2['data']['full_table_name'] == $k) {
						unset($result[$k0]['delete_indexes'][$k2]);
					}
				}
			}
		}

		// final step clean up empty keys
		foreach (['up', 'down'] as $k0) {
			foreach ($result[$k0] as $k => $v) {
				// clean up empty nodes
				if (empty($v)) {
					unset($result[$k0][$k]);
				}
			}
		}

		$result['success'] = true;
		return $result;
	}

	/**
	 * Sanitize function
	 *
	 * @param string $sql
	 * @return string
	 */
	public static function sanitize_function($sql) {
		$sql = trim(str_replace(['$BODY$', '$function$'], '$$$', $sql), " \t\n\r");
		$temp = explode("\n", $sql);
		foreach ($temp as $k => $v) {
			$temp[$k] = trim($v, " \t\n\r");
		}
		$temp = implode("\n", $temp);
		$temp = str_replace('FUNCTION public.', 'FUNCTION ', $temp);
		return $temp;
	}

	/**
	 * Column SQL type (Base)
	 *
	 * @param array $column
	 * @return array
	 */
	public function column_sql_type_base($column) {
		$column['type'] = $column['type'] ?? null;
		$column['null'] = !empty($column['null']) ? true : false;
		$column['default'] = $column['default'] ?? null;
		$column['length'] = $column['length'] ?? 0;
		$column['precision'] = $column['precision'] ?? 0;
		$column['scale'] = $column['scale'] ?? 0;
		$column['sequence'] = !empty($column['sequence']) ? true : false;
		// sql_type would be used in schema generation and comparison
		$column['sql_type'] = null;
		return $column;
	}

	/**
	 * Generate SQL from diff objects, diff is generated by self::compare_schemas,
	 * this method must be called from inherited classes
	 *
	 * @param string $db_link
	 * @param array $diff
	 * @param array $options
	 * @return array
	 */
	public function generate_sql_from_diff_objects($db_link, $diff, $options = []) {
		$result = [
			'success' => false,
			'error' => [],
			'data' => [],
			'count' => 0
		];
		$options['mode'] = $options['mode'] ?? 'commit';
		// process column sql_type for new tables
		if (!empty($diff['new_tables'])) {
			foreach ($diff['new_tables'] as $k => $v) {
				foreach ($v['data']['columns'] as $k2 => $v2) {
					$diff['new_tables'][$k]['data']['columns'][$k2] = $this->column_sql_type($v2);
				}
			}
		}
		// new columns
		if (!empty($diff['new_columns'])) {
			foreach ($diff['new_columns'] as $k => $v) {
				$diff['new_columns'][$k]['data'] = $this->column_sql_type($v['data']);
			}
		}
		// column changes
		if (!empty($diff['change_columns'])) {
			foreach ($diff['change_columns'] as $k => $v) {
				$diff['change_columns'][$k]['data'] = $this->column_sql_type($v['data']);
				$diff['change_columns'][$k]['data_old'] = $this->column_sql_type($v['data_old']);
			}
		}
		// generating sql
		foreach ($diff as $k => $v) {
			foreach ($v as $k2 => $v2) {
				// we need to make fk constraints last to sort MySQL issues
				if ($k == 'new_constraints' && $v2['type'] == 'constraint_new' && $v2['data']['type'] == 'fk') {
					$diff[$k . '_fks'][$k2]['sql'] = $this->render_sql($v2['type'], $v2);
				} else {
					$diff[$k][$k2]['sql'] = $this->render_sql($v2['type'], $v2, ['mode' => $options['mode']]);
				}
			}
		}
		// if we are dropping we need to disable foregn key checks
		if ($options['mode'] == 'drop') {
			$backend = factory::get(['db', $db_link, 'backend']);
			if ($backend == 'mysqli') {
				$diff['before_execution']['foreign_key_checks']['sql'] = 'SET foreign_key_checks = 0;';
				// we also need to unset sequences
				unset($diff['delete_sequences']);
			}
		}
		// generate plain array
		foreach ($diff as $k => $v) {
			foreach ($v as $k2 => $v2) {
				if (empty($v2['sql'])) continue;
				if (!is_array($v2['sql'])) $v2['sql'] = [$v2['sql']];
				foreach ($v2['sql'] as $v3) {
					$result['data'][] = $v3;
					$result['count']++;
				}
			}
		}
		$result['success'] = true;
		return $result;
	}
}