<?php

class numbers_backend_db_class_ddl {

	/**
	 * All objects would be kept here
	 *
	 * @var array 
	 */
	public $objects = [];

	/**
	 * All db links from models
	 *
	 * @var array
	 */
	public $db_links = [];

	/**
	 * Adding object
	 *
	 * @param string $type - code or db
	 * @param array $object
	 * @param string $db_link
	 * @param string $db_link_flag
	 * @throws Exception
	 */
	public function object_add($object, $db_link) {
		// assembling everything as array
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
		$this->db_links[$db_link] = $db_link;
	}

	/**
	 * Process model
	 *
	 * @param string $model_class
	 * @return array
	 * @throws Exception
	 */
	public function process_table_model($model_class) {
		$result = [
			'success' => false,
			'error' => []
		];
		do {
			// table model
			$model = new $model_class();
			$db = factory::get(['db', $model->db_link]);
			$ddl_object = $db['ddl_object'];
			$owner = $db['object']->connect_options['username'];
			$engine = $model->engine[$db['backend']] ?? null;

			// process table name and schema
			$schema_supported = $ddl_object->is_schema_supported($model->name);
			if ($schema_supported['success']) {
				if (!empty($schema_supported['schema']) && $schema_supported['schema'] != 'public') {
					$this->object_add(['type' => 'schema', 'schema' => $schema_supported['schema'], 'name' => $schema_supported['schema'], 'data' => ['name' => $schema_supported['schema'], 'owner' => $owner]], $model->db_link);
				}
			}

			// process columns
			if (empty($model->columns)) {
				$result['error'][] = 'Table ' . $model->name . ' must have atleast one column!';
				break;
			}

			// columns would be here
			$columns = [];
			foreach ($model->columns as $k => $v) {
				$v['column_name'] = $k;
				$column_temp = $ddl_object->is_column_type_supported($v, $model);
				$columns[$k] = $column_temp['column'];
				// handle sequence
				if (!empty($column_temp['column']['sequence'])) {
					$this->object_add([
						'type' => 'sequence',
						'schema' => $schema_supported['schema'],
						'name' => $column_temp['column']['sequence'],
						'data' => [
							'owner' => $owner,
							'full_sequence_name' => $column_temp['column']['sequence'],
							'type' => 'simple',
							'prefix' => null,
							'length' => 0,
							'suffix' => null
						]
					], $model->db_link);
				}
			}
			$this->object_add(['type' => 'table', 'schema' => $schema_supported['schema'], 'name' => $schema_supported['table'], 'data' => ['columns' => $columns, 'owner' => $owner, 'full_table_name' => $schema_supported['full_table_name'], 'engine' => $engine]], $model->db_link);

			// history
			if ($model->history) {
				$temp = $model->column_prefix . 'inserted';
				if (empty($model->columns[$temp]) || $model->columns[$temp]['type'] != 'timestamp') {
					$result['error'][] = 'Table ' . $model->name . ' history column inserted is missing or is not timestamp!';
				}
				$temp = $model->column_prefix . 'updated';
				if (empty($model->columns[$temp]) || $model->columns[$temp]['type'] != 'timestamp') {
					$result['error'][] = 'Table ' . $model->name . ' history column updated is missing or is not timestamp!';
				}
				if ($result['error']) {
					break;
				}
				// fix columns with serial types
				$columns_history = $columns;
				foreach ($model->pk as $v) {
					if ($columns_history[$v]['type'] == 'serial') {
						$columns_history[$v]['type'] = 'integer';
					} else if ($columns_history[$v]['type'] == 'bigserial') {
						$columns_history[$v]['type'] = 'bigint';
					} else if ($columns_history[$v]['type'] == 'smallserial') {
						$columns_history[$v]['type'] = 'smallint';
					}
				}
				// add new history table
				$this->object_add(['type' => 'table', 'schema' => $schema_supported['schema'], 'name' => $schema_supported['table'] . '__history', 'data' => ['columns' => $columns_history, 'owner' => $owner, 'full_table_name' => $model->history_name, 'engine' => $engine]], $model->db_link);
			}

			// processing constraints
			if (!empty($model->constraints)) {
				foreach ($model->constraints as $k => $v) {
					$v['full_table_name'] = $schema_supported['full_table_name'];
					// additional processing for fk type constraints
					if ($v['type'] == 'fk') {
						$temp_class_name = $v['foreign_model'];
						$temp_object = new $temp_class_name();
						$v['foreign_table'] = $temp_object->name;
					}
					$this->object_add(['type' => 'constraint', 'schema' => $schema_supported['schema'], 'table' => $schema_supported['table'], 'name' => $k, 'data' => $v], $model->db_link);
				}
			}

			// processing indexes
			if (!empty($model->indexes)) {
				foreach ($model->indexes as $k => $v) {
					$v['full_table_name'] = $schema_supported['full_table_name'];
					$this->object_add(['type' => 'index', 'schema' => $schema_supported['schema'], 'table' => $schema_supported['table'], 'name' => $k, 'data' => $v], $model->db_link);
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
	 * @return array
	 */
	public function process_sequence_model($model_class) {
		$result = [
			'success' => false,
			'error' => []
		];
		do {
			// table model
			$model = new $model_class();
			$db = factory::get(['db', $model->db_link]);
			$ddl_object = $db['ddl_object'];
			$owner = $db['object']->connect_options['username'];

			// process sequence name and schema
			$schema_supported = $ddl_object->is_schema_supported($model->name);
			$this->object_add([
				'type' => 'sequence',
				'schema' => $schema_supported['schema'],
				'name' => $schema_supported['table'],
				'data' => [
					'owner' => $owner,
					'full_sequence_name' => $schema_supported['full_table_name'],
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
	 * @return array
	 */
	public function process_function_model($model_class) {
		$result = [
			'success' => false,
			'error' => []
		];
		do {
			// table model
			$model = new $model_class();
			$db = factory::get(['db', $model->db_link]);
			$ddl_object = $db['ddl_object'];
			$ddl_backend = $db['backend'];
			$owner = $db['object']->connect_options['username'];
			// if we do not have sql or its natively supported we exit
			if (empty($model->function_sql[$ddl_backend])) {
				$result['success'] = true;
				break;
			}
			// process function name and schema
			$schema_supported = $ddl_object->is_schema_supported($model->function_name);
			// we need to unset function definition
			$sql_full_temp = $model->function_sql[$ddl_backend];
			unset($sql_full_temp['definition']);
			$this->object_add([
				'type' => 'function',
				'schema' => $schema_supported['schema'],
				'name' => $schema_supported['table'],
				'data' => [
					'owner' => $owner,
					'full_function_name' => $schema_supported['full_table_name'],
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
	 * @return array
	 */
	public function process_function_extension($model_class) {
		$result = [
			'success' => false,
			'error' => []
		];
		do {
			// table model
			$model = new $model_class();
			$db = factory::get(['db', $model->db_link]);
			$ddl_object = $db['ddl_object'];
			$ddl_backend = $db['backend'];
			// if we do not have sql or its natively supported we exit
			if ($model->extension_submodule != $ddl_backend) {
				$result['success'] = true;
				break;
			}
			// process extension name and schema
			$schema_supported = $ddl_object->is_schema_supported($model->extension_name);
			$this->object_add([
				'type' => 'extension',
				'schema' => $schema_supported['schema'],
				'name' => $schema_supported['table']
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
	 * @return array
	 */
	public function compare_schemas($obj_master, $obj_slave, $options = []) {
		$result = [
			'success' => false,
			'error' => [],
			'data' => [],
			'count' => 0
		];

		// delete first
		$result['data']['delete_triggers'] = [];
		$result['data']['delete_views'] = [];
		$result['data']['delete_constraints'] = [];
		$result['data']['delete_indexes'] = [];
		$result['data']['delete_functions'] = [];
		$result['data']['delete_columns'] = [];
		$result['data']['delete_tables'] = [];
		$result['data']['delete_domains'] = []; // after tables and columns
		$result['data']['delete_sequences'] = []; // after domains
		$result['data']['delete_schemas'] = [];
		$result['data']['delete_extensions'] = []; // last

		// new second
		$result['data']['new_extensions'] = []; // first
		$result['data']['new_schemas'] = [];
		$result['data']['new_schema_owners'] = [];
		$result['data']['new_domains'] = []; // after schema
		$result['data']['new_domain_owners'] = [];
		$result['data']['new_sequences'] = [];
		$result['data']['new_sequence_owners'] = [];
		$result['data']['new_tables'] = [];
		$result['data']['new_table_owners'] = [];
		$result['data']['new_columns'] = [];
		$result['data']['change_columns'] = [];
		$result['data']['new_constraints'] = [];
		$result['data']['new_indexes'] = [];
		$result['data']['new_views'] = []; // views goes after we add columns
		$result['data']['change_views'] = [];
		$result['data']['new_view_owners'] = [];
		$result['data']['new_functions'] = [];
		$result['data']['new_function_owner'] = [];
		$result['data']['new_triggers'] = []; // after functions
		$result['data']['change_triggers'] = [];

		// add extension
		if (!empty($obj_master['extension'])) {
			foreach ($obj_master['extension'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					if (empty($obj_slave['extension'][$k][$k2])) {
						$result['data']['new_extensions'][$k . '.' . $k2] = array('type' => 'extension', 'data' => $v2);
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
						$result['data']['delete_extensions'][$k . '.' . $k2] = array('type'=>'extension_delete', 'data' => $v2);
						$result['count']++;
					}
				}
			}
		}

		// new schemas
		if (!empty($obj_master['schema'])) {
			foreach ($obj_master['schema'] as $k => $v) {
				if (empty($obj_slave['schema'][$k])) {
					$result['data']['new_schemas'][$k] = array('type' => 'schema', 'data' => $v);
					$result['count']++;
				} else if ($v['owner']!=$obj_slave['schema'][$k]['owner']) {
					$result['data']['new_schema_owners'][$k] = array('type' => 'schema_owner', 'data' => $v);
					$result['count']++;
				}
			}
		}

		// delete schema
		if (!empty($obj_slave['schema'])) {
			foreach ($obj_slave['schema'] as $k => $v) {
				if (empty($obj_master['schema'][$k])) {
					$result['data']['delete_schemas'][$k] = array('type'=>'schema_delete', 'data' => $v);
					$result['count']++;
				}
			}
		}

		// new tables
		foreach ($obj_master['table'] as $k => $v) {
			foreach ($v as $k2 => $v2) {
				if (empty($obj_slave['table'][$k][$k2])) {
					$result['data']['new_tables'][$v2['full_table_name']] = array('type' => 'table_new', 'data' => $v2);
					$result['count']++;
				} else {
					if ($v2['owner'] != $obj_slave['table'][$k][$k2]['owner']) {
						$result['data']['new_table_owners'][$v2['full_table_name']] = array('type'=>'table_owner', 'data' => $v2);
						$result['count']++;
					}
				}
			}
		}

		// delete table
		if (isset($obj_slave['table'])) {
			foreach ($obj_slave['table'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					if (empty($obj_master['table'][$k][$k2])) {
						$result['data']['delete_tables'][$v2['full_table_name']] = array('type' => 'table_delete', 'data' => $v2);
						$result['count']++;
					}
				}
			}
		}

		// new columns
		foreach ($obj_master['table'] as $k => $v) {
			foreach ($v as $k2 => $v2) {
				// if we have new table we do not need to check for new columns
				if (!empty($result['data']['new_tables'][$v2['full_table_name']])) continue;
				// finding new column
				foreach ($v2['columns'] as $k3 => $v3) {
					if (empty($obj_slave['table'][$k][$k2]['columns'][$k3])) {
						$result['data']['new_columns'][$k . '.' . $k2 . '.' . $k3] = array('type' => 'column_new', 'name' => $k3, 'table' => $v2['full_table_name'], 'data' => $v3);
						$result['count']++;
					} else {
						// comparing data types
						$temp_error = false;
						if ($v3['type'] != $obj_slave['table'][$k][$k2]['columns'][$k3]['type']) {
							if (strpos($v3['type'], 'serial') !== false || strpos($obj_slave['table'][$k][$k2]['columns'][$k3]['type'], 'serial') !== false) {
								$result['error'][] = 'Serial data type changes must be handled manually, column ' . $k . '.' . $k2 . '.' . $k3;
								return $result;
							}
							$temp_error = true;
						}
						if (!isset($v3['null'])) {
							$v3['null'] = false;
						}
						if (!isset($obj_slave['table'][$k][$k2]['columns'][$k3]['null'])) {
							$obj_slave['table'][$k][$k2]['columns'][$k3]['null'] = false;
						}
						if ($v3['null'] != $obj_slave['table'][$k][$k2]['columns'][$k3]['null']) {
							$temp_error = true;
						}
						if (!isset($v3['default'])) {
							$v3['default'] = null;
						}
						if (!isset($obj_slave['table'][$k][$k2]['columns'][$k3]['default'])) {
							$obj_slave['table'][$k][$k2]['columns'][$k3]['default'] = null;
						}
						if ($v3['default'] != $obj_slave['table'][$k][$k2]['columns'][$k3]['default']) {
							$temp_error = true;
						}
						// auto_increment for mysqli
						if ($options['backend'] == 'mysqli') {
							if (!isset($v3['auto_increment'])) {
								$v3['auto_increment'] = 0;
							}
							if ($v3['auto_increment'] != $obj_slave['table'][$k][$k2]['columns'][$k3]['auto_increment']) {
								$temp_error = true;
							}
						}
						if ($temp_error) {
							$result['data']['change_columns'][$k . '.' . $k2 . '.' . $k3] = array('type' => 'column_change', 'name' => $k3, 'table' => $v2['full_table_name'], 'data'=>$v3, 'data_slave' => $obj_slave['table'][$k][$k2]['columns'][$k3]);
							$result['count']++;
						}
					}
				}

				// finding columns to be deleted
				foreach ($obj_slave['table'][$k][$k2]['columns'] as $k3 => $v3) {
					if (empty($obj_master['table'][$k][$k2]['columns'][$k3])) {
						$result['data']['delete_columns'][$k . '.' . $k2 . '.' . $k3] = array('type' => 'column_delete', 'name' => $k3, 'table' => $v2['full_table_name']);
						$result['count']++;
					}
				}
			}
		}

		// exceptions for mysqli submodule
		if ($options['backend'] == 'mysqli') {
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
		foreach ($obj_master['constraint'] as $k => $v) {
			foreach ($v as $k2 => $v2) {
				foreach ($v2 as $k3 => $v3) {
					if (empty($obj_slave['constraint'][$k][$k2][$k3])) {
						$result['data']['new_constraints'][$k . '.' . $k2 . '.' . $k3] = array('type' => 'constraint_new', 'name' => $k3, 'table' => $v3['full_table_name'], 'data' => $v3);
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
						}
						// if we have an error we rebuild
						if ($temp_error) {
							$result['data']['delete_constraints'][$k . '.' . $k2 . '.' . $k3] = array('type' => 'constraint_delete', 'name' => $k3, 'table' => $v3['full_table_name'], 'data' => $v3);
							$result['data']['new_constraints'][$k . '.' . $k2 . '.' . $k3] = array('type' => 'constraint_new', 'name' => $k3, 'table' => $v3['full_table_name'], 'data' => $v3);
							$result['count']+= 1;
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
							$result['data']['delete_constraints'][$k . '.' . $k2 . '.' . $k3] = array('type' => 'constraint_delete', 'name' => $k3, 'table' => $v3['full_table_name'], 'data' => $v3);
							$result['count']++;
						}
					}
				}
			}
		}

		// new indexes
		foreach ($obj_master['index'] as $k => $v) {
			foreach ($v as $k2 => $v2) {
				foreach ($v2 as $k3 => $v3) {
					if (empty($obj_slave['index'][$k][$k2][$k3])) {
						$result['data']['new_indexes'][$k . '.' . $k2 . '.' . $k3] = array('type' => 'index_new', 'name' => $k3, 'table' => $v3['full_table_name'], 'data' => $v3);
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
							$result['data']['delete_indexes'][$k . '.' . $k2 . '.' . $k3] = array('type' => 'index_delete', 'name' => $k3, 'table' => $v3['full_table_name'], 'data' => $obj_slave['index'][$k][$k2][$k3]);
							$result['data']['new_indexes'][$k . '.' . $k2 . '.' . $k3] = array('type' => 'index_new', 'name' => $k3, 'table' => $v3['full_table_name'], 'data' => $v3);
							$result['count']+= 1;
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
							$result['data']['delete_indexes'][$k . '.' . $k2 . '.' . $k3] = array('type' => 'index_delete', 'name' => $k3, 'table' => $v3['full_table_name'], 'data' => $v3);
							$result['count']++;
						}
					}
				}
			}
		}

		// for mysqli/pgsql we need to put new sequences after new tables
		if ($options['backend'] == 'mysqli' || $options['backend'] == 'pgsql') {
			unset($result['data']['new_sequences']);
			$result['data']['new_sequences'] = [];
		}

		// new sequences
		if (!empty($obj_master['sequence'])) {
			foreach ($obj_master['sequence'] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					if (empty($obj_slave['sequence'][$k][$k2])) {
						$result['data']['new_sequences'][$k . '.' . $k2] = array('type' => 'sequences_new', 'name' => $v2['full_sequence_name'], 'owner' => $v2['owner'], 'data' => $v2);
						$result['count']++;
					} else if ($options['backend'] == 'pgsql') {
						// todo: fix here for pgsql!!!
						// checking owner information
						if ($v2['owner'] != $obj_slave['sequence'][$k][$k2]['owner']) {
							$result['data']['new_sequence_owners'][$k . '.' . $k2] = array('type' => 'sequence_owner', 'name' => $v2['full_sequence_name'], 'owner'=>$v2['owner'], 'data' => $v2);
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
						$result['data']['delete_sequences'][$k . '.' . $k2] = array('type' => 'sequence_delete', 'name' => $v2['full_sequence_name'], 'data' => $v2);
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
						$result['data']['new_functions'][$k . '.' . $k2] = array('type' => 'function_new', 'name' => $v2['full_function_name'], 'owner' => $v2['owner'], 'data' => $v2);
						$result['count']++;
					} else {
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
						$result['data']['delete_functions'][$k . '.' . $k2] = array('type' => 'function_delete', 'name' => $v2['full_function_name'], 'data' => $v2);
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
		return implode("\n", $temp);
	}
}