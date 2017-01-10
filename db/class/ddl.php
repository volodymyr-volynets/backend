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
	 * @throws Exception
	 */
	public function object_add($object, $db_link) {
		if (!isset($this->count[$db_link])) $this->count[$db_link] = [];
		$this->count[$db_link]['Total'] = $this->count[$db_link]['Total'] ?? 0;
		$type = ucwords($object['type']) . '(s)';
		if (!isset($this->count[$db_link][$type])) $this->count[$db_link][$type] = 0;
		if ($object['type'] == 'table') {
			$this->objects[$db_link][$object['type']][$object['schema']][$object['name']] = $object['data'];
		} else if ($object['type'] == 'schema') {
			$this->objects[$db_link][$object['type']][$object['name']] = $object['data'];
		} else if ($object['type'] == 'constraint' || $object['type'] == 'index') {
			$this->objects[$db_link][$object['type']][$object['schema']][$object['table']][$object['name']] = $object['data'];
		} else if ($object['type'] == 'sequence') {
			$this->objects[$db_link][$object['type']][$object['schema']][$object['name']] = $object['data'];
		} else if ($object['type'] == 'function') {
			$this->objects[$db_link][$object['type']][$object['schema']][$object['name']] = $object['data'];
		} else if ($object['type'] == 'extension') {
			$this->objects[$db_link][$object['type']][$object['schema']][$object['name']] = $object;
		}
		$this->count[$db_link]['Total']++;
		$this->count[$db_link][$type]++;
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
			$model = is_object($model_class) ? $model_class : factory::model($model_class, true);
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
							'full_sequence_name' => $model->full_table_name . '_' . $k . '_seq'
						]
					], $model->db_link);
				}
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
						$temp_object = factory::model($v['foreign_model'], true);
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
			// owner
			$db = factory::get(['db', $model->db_link]);
			$ddl_object = $db['ddl_object'];
			$owner = $db['object']->connect_options['username'];
			// process sequence name and schema
			$this->object_add([
				'type' => 'sequence',
				'schema' => '',
				'name' => $model->name,
				'data' => [
					'owner' => $owner,
					'full_sequence_name' => $model->name,
					'type' => $model->type,
					'prefix' => $model->prefix,
					'length' => $model->length,
					'suffix' => $model->suffix
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
			'data' => [],
			'count' => 0
		];
		// mode and backend and db_link
		$options['type'] = $options['type'] ?? null;
		if (!empty($options['db_link'])) {
			$ddl_object = factory::get(['db', $options['db_link'], 'ddl_object']);
			$options['backend'] = factory::get(['db', $options['db_link'], 'backend']);
		}
		// before execution
		$result['data']['before_execution'] = [];
		// delete first
		$result['data']['delete_triggers'] = [];
		$result['data']['delete_views'] = [];
		$result['data']['delete_constraints'] = [];
		$result['data']['delete_indexes'] = [];
		$result['data']['delete_functions'] = [];
		$result['data']['delete_columns'] = [];
		$result['data']['delete_tables'] = [];
		$result['data']['delete_sequences'] = []; // after tables
		$result['data']['delete_schemas'] = [];
		$result['data']['delete_extensions'] = []; // last
		// new/change second
		$result['data']['new_extensions'] = []; // first
		$result['data']['new_schemas'] = [];
		$result['data']['new_schema_owners'] = [];
		$result['data']['new_tables'] = []; // after schemas
		$result['data']['new_table_owners'] = [];
		$result['data']['new_columns'] = [];
		$result['data']['change_columns'] = [];
		$result['data']['new_sequences'] = []; // after tables
		$result['data']['new_sequence_owners'] = [];
		$result['data']['new_constraints'] = [];
		$result['data']['new_indexes'] = [];
		//$result['data']['new_views'] = []; // views goes after we add columns
		//$result['data']['change_views'] = [];
		//$result['data']['new_view_owners'] = [];
		$result['data']['new_functions'] = [];
		$result['data']['new_function_owner'] = [];
		//$result['data']['new_triggers'] = []; // after functions
		//$result['data']['change_triggers'] = [];

		// add extension
		if (!empty($obj_master['extension'])) {
			foreach ($obj_master['extension'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					// in schema mode we skip not related extensions
					if ($options['type'] == 'schema' && !in_array($options['backend'], $v2['backends'])) continue;
					// extension must be present
					if (empty($obj_slave['extension'][$k][$k2])) {
						$result['data']['new_extensions'][$k . '.' . $k2] = [
							'type' => 'extension',
							'data' => $v2
						];
						$result['count']++;
					}
				}
			}
		}

		// delete extensions
		if (!empty($obj_slave['extension'])) {
			foreach ($obj_slave['extension'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					if (empty($obj_master['extension'][$k][$k2])) {
						$result['data']['delete_extensions'][$k . '.' . $k2] = [
							'type' => 'extension_delete',
							'data' => $v2
						];
						$result['count']++;
					}
				}
			}
		}

		// new schemas
		if (!empty($obj_master['schema'])) {
			foreach ($obj_master['schema'] as $k => $v) {
				if (empty($obj_slave['schema'][$k])) {
					$result['data']['new_schemas'][$k] = [
						'type' => 'schema',
						'data' => $v
					];
					$result['count']++;
				} else if ($v['owner']!=$obj_slave['schema'][$k]['owner']) {
					$result['data']['new_schema_owners'][$k] = [
						'type' => 'schema_owner',
						'data' => $v
					];
					$result['count']++;
				}
			}
		}

		// delete schema
		if (!empty($obj_slave['schema'])) {
			foreach ($obj_slave['schema'] as $k => $v) {
				if (empty($obj_master['schema'][$k])) {
					$result['data']['delete_schemas'][$k] = [
						'type' => 'schema_delete',
						'data' => $v
					];
					$result['count']++;
				}
			}
		}

		// new tables
		if (!empty($obj_master['table'])) {
			foreach ($obj_master['table'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					if (empty($obj_slave['table'][$k][$k2])) {
						$result['data']['new_tables'][$v2['full_table_name']] = [
							'type' => 'table_new',
							'data' => $v2
						];
						$result['count']++;
					} else {
						if ($v2['owner'] != $obj_slave['table'][$k][$k2]['owner']) {
							$result['data']['new_table_owners'][$v2['full_table_name']] = [
								'type' => 'table_owner',
								'data' => $v2
							];
							$result['count']++;
						}
					}
				}
			}
		}

		// delete table
		if (isset($obj_slave['table'])) {
			foreach ($obj_slave['table'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					if (empty($obj_master['table'][$k][$k2])) {
						$result['data']['delete_tables'][$v2['full_table_name']] = [
							'type' => 'table_delete',
							'data' => $v2
						];
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
					if (!empty($result['data']['new_tables'][$v2['full_table_name']])) continue;
					// finding new column
					foreach ($v2['columns'] as $k3 => $v3) {
						// schema comparison
						if ($options['type'] == 'schema') {
							$master_compare = $ddl_object->column_sql_type($v3);
							$compare_columns = ['sql_type', 'null', 'default'];
							$slave_compare = $obj_slave['table'][$k][$k2]['columns'][$k3];
						} else { // migration
							// todo
							Throw new Exception('migration comparison!!!');
						}
						// find new columns
						if (empty($obj_slave['table'][$k][$k2]['columns'][$k3])) {
							$master_compare['sql_type'] = null;
							$result['data']['new_columns'][$k . '.' . $k2 . '.' . $k3] = [
								'type' => 'column_new',
								'name' => $k3,
								'table' => $v2['full_table_name'],
								'data' => $master_compare
							];
							$result['count']++;
						} else {
							// comparing
							$temp_error = false;
							foreach ($compare_columns as $v88) {
								if ($master_compare[$v88] !== $slave_compare[$v88]) {
									$temp_error = true;
									break;
								}
							}
							if ($temp_error) {
								$master_compare['sql_type'] = null;
								$slave_compare['sql_type'] = null;
								$result['data']['change_columns'][$k . '.' . $k2 . '.' . $k3] = [
									'type' => 'column_change',
									'name' => $k3,
									'table' => $v2['full_table_name'],
									'data' => $master_compare,
									'data_slave' => $slave_compare
								];
								$result['count']++;
							}
						}
					}
					// finding columns to be deleted
					foreach ($obj_slave['table'][$k][$k2]['columns'] as $k3 => $v3) {
						if (empty($obj_master['table'][$k][$k2]['columns'][$k3])) {
							$result['data']['delete_columns'][$k . '.' . $k2 . '.' . $k3] = [
								'type' => 'column_delete',
								'name' => $k3,
								'table' => $v2['full_table_name']
							];
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
						if (empty($obj_slave['constraint'][$k][$k2][$k3])) {
							$result['data']['new_constraints'][$k . '.' . $k2 . '.' . $k3] = [
								'type' => 'constraint_new',
								'name' => $k3,
								'table' => $v3['full_table_name'],
								'data' => $v3
							];
							$result['count']++;
						} else {
							// comparing structure
							$temp_error = false;
							if ($v3['type'] != $obj_slave['constraint'][$k][$k2][$k3]['type']) {
								$temp_error = true;
							}
							if ($v3['full_table_name'] != $obj_slave['constraint'][$k][$k2][$k3]['full_table_name']) {
								$temp_error = true;
							}
							if (!array_compare_level1($v3['columns'], $obj_slave['constraint'][$k][$k2][$k3]['columns'])) {
								$temp_error = true;
							}
							// additiona verifications for fk constraints
							if ($v3['type'] == 'fk') {
								if ($v3['foreign_table'] != $obj_slave['constraint'][$k][$k2][$k3]['foreign_table']) {
									$temp_error = true;
								}
								if (!array_compare_level1($v3['foreign_columns'], $obj_slave['constraint'][$k][$k2][$k3]['foreign_columns'])) {
									$temp_error = true;
								}
								// compare options
								if ($v3['options']['match'] !== $obj_slave['constraint'][$k][$k2][$k3]['options']['match']) $temp_error = true;
								if ($v3['options']['update'] !== $obj_slave['constraint'][$k][$k2][$k3]['options']['update']) $temp_error = true;
								if ($v3['options']['delete'] !== $obj_slave['constraint'][$k][$k2][$k3]['options']['delete']) $temp_error = true;
							}
							// if we have an error we rebuild
							if ($temp_error) {
								// debug of fk issues
								/*
								print_r2($k3);
								print_r2($obj_slave['constraint'][$k][$k2][$k3]);
								print_r2($v3);
								exit;
								*/
								$result['data']['delete_constraints'][$k . '.' . $k2 . '.' . $k3] = [
									'type' => 'constraint_delete',
									'name' => $k3,
									'table' => $v3['full_table_name'],
									'data' => $v3
								];
								$result['data']['new_constraints'][$k . '.' . $k2 . '.' . $k3] = [
									'type' => 'constraint_new',
									'name' => $k3,
									'table' => $v3['full_table_name'],
									'data' => $v3
								];
								$result['count']+= 1;
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
						if (empty($obj_master['constraint'][$k][$k2][$k3])) {
							$result['data']['delete_constraints'][$k . '.' . $k2 . '.' . $k3] = [
								'type' => 'constraint_delete',
								'name' => $k3,
								'table' => $v3['full_table_name'],
								'data' => $v3
							];
							$result['count']++;
						}
					}
				}
			}
		}

		// new indexes
		if (!empty($obj_master['index'])) {
			foreach ($obj_master['index'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					foreach ($v2 as $k3 => $v3) {
						if (empty($obj_slave['index'][$k][$k2][$k3])) {
							$result['data']['new_indexes'][$k . '.' . $k2 . '.' . $k3] = [
								'type' => 'index_new',
								'name' => $k3,
								'table' => $v3['full_table_name'],
								'data' => $v3
							];
							$result['count']++;
						} else {
							// comparing structure
							$temp_error = false;
							if ($v3['type'] != $obj_slave['index'][$k][$k2][$k3]['type']) {
								$temp_error = true;
							}
							if (!array_compare_level1($v3['columns'], $obj_slave['index'][$k][$k2][$k3]['columns'])) {
								$temp_error = true;
							}
							// todo: comparison for foreign key & check
							if ($temp_error) {
								$result['data']['delete_indexes'][$k . '.' . $k2 . '.' . $k3] = [
									'type' => 'index_delete',
									'name' => $k3,
									'table' => $v3['full_table_name'],
									'data' => $obj_slave['index'][$k][$k2][$k3]
								];
								$result['data']['new_indexes'][$k . '.' . $k2 . '.' . $k3] = [
									'type' => 'index_new',
									'name' => $k3,
									'table' => $v3['full_table_name'],
									'data' => $v3
								];
								$result['count']+= 1;
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
							$result['data']['delete_indexes'][$k . '.' . $k2 . '.' . $k3] = [
								'type' => 'index_delete',
								'name' => $k3,
								'table' => $v3['full_table_name'],
								'data' => $v3
							];
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
					if (empty($obj_slave['sequence'][$k][$k2])) {
						$result['data']['new_sequences'][$k . '.' . $k2] = [
							'type' => 'sequences_new',
							'name' => $v2['full_sequence_name'],
							'owner' => $v2['owner'],
							'data' => $v2
						];
						$result['count']++;
					} else {
						// checking owner information
						if ($v2['owner'] != $obj_slave['sequence'][$k][$k2]['owner']) {
							$result['data']['new_sequence_owners'][$k . '.' . $k2] = [
								'type' => 'sequence_owner',
								'name' => $v2['full_sequence_name'],
								'owner'=>$v2['owner'],
								'data' => $v2
							];
						}
					}
				}
			}
		}

		// delete sequences
		if (!empty($obj_slave['sequence'])) {
			foreach ($obj_slave['sequence'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					if (empty($obj_master['sequence'][$k][$k2])) {
						$result['data']['delete_sequences'][$k . '.' . $k2] = [
							'type' => 'sequence_delete',
							'name' => $v2['full_sequence_name'],
							'data' => $v2
						];
						$result['count']++;
					}
				}
			}
		}

		// functions
		if (!empty($obj_master['function'])) {
			foreach ($obj_master['function'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					if (empty($obj_slave['function'][$k][$k2])) {
						$result['data']['new_functions'][$k . '.' . $k2] = [
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
								$result['data']['delete_functions'][$k . '.' . $k2] = array('type' => 'function_delete', 'name' => $v2['full_function_name'], 'data' => $v2);
								$result['data']['new_functions'][$k . '.' . $k2] = array('type' => 'function_new', 'name' => $v2['full_function_name'], 'owner' => $v2['owner'], 'data' => $v2);
								$result['count']++;
							}
						} else if ($options['backend'] == 'pgsql') {
							if (numbers_backend_db_class_ddl::sanitize_function($v2['sql_full']) != numbers_backend_db_class_ddl::sanitize_function($obj_slave['function'][$k][$k2]['sql_full'])) {
								$result['data']['delete_functions'][$k . '.' . $k2] = array('type' => 'function_delete', 'name' => $v2['full_function_name'], 'data' => $v2);
								$result['data']['new_functions'][$k . '.' . $k2] = array('type' => 'function_new', 'name' => $v2['full_function_name'], 'owner' => $v2['owner'], 'data' => $v2);
								$result['count']++;
							} else if ($v2['owner'] != $obj_slave['function'][$k][$k2]['owner']) { // checking owner
								$result['data']['new_function_owners'][$k . '.' . $k2] = array('type'=>'function_owner', 'name' => $v2['full_function_name'], 'owner' => $v2['owner'], 'data' => $v2);
								$result['count']++;
							}
						}
					}
				}
			}
		}

		// delete function
		if (!empty($obj_slave['function'])) {
			foreach ($obj_slave['function'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					if (empty($obj_master['function'][$k][$k2])) {
						$result['data']['delete_functions'][$k . '.' . $k2] = [
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
		foreach ($result['data']['delete_tables'] as $k => $v) {
			// unsetting constraints
			foreach ($result['data']['delete_constraints'] as $k2 => $v2) {
				if ($v2['data']['full_table_name'] == $k) {
					unset($result['data']['delete_constraints'][$k2]);
				}
			}
			// unsetting indexes
			foreach ($result['data']['delete_indexes'] as $k2 => $v2) {
				if ($v2['data']['full_table_name'] == $k) {
					unset($result['data']['delete_indexes'][$k2]);
				}
			}
		}

		// final step clean up empty keys
		foreach ($result['data'] as $k => $v) {
			// clean up empty nodes
			if (empty($v)) {
				unset($result['data'][$k]);
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
	 * Column SQL type
	 *
	 * @param array $column
	 * @return array
	 */
	public function column_sql_type($column) {
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
}