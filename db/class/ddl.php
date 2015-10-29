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
			$engine = isset($model->table_engine[$db['backend']]) ? $model->table_engine[$db['backend']] : null;

			// process table name and schema
			$schema_supported = $ddl_object->is_schema_supported($model->table_name);
			if ($schema_supported['success']) {
				if (!empty($schema_supported['schema']) && $schema_supported['schema'] != 'public') {
					$this->object_add(['type' => 'schema', 'schema' => $schema_supported['schema'], 'name' => $schema_supported['schema'], 'data' => ['name' => $schema_supported['schema'], 'owner' => $owner]], $model->db_link);
				}
			}

			// process columns
			if (empty($model->table_columns)) {
				$result['error'][] = 'Table must have atleast one column!';
				break;
			}

			// columns would be here
			$columns = [];
			foreach ($model->table_columns as $k => $v) {
				$column_temp = $ddl_object->is_column_type_supported($v, $model);
				$columns[$k] = $column_temp['column'];
			}
			$this->object_add(['type' => 'table', 'schema' => $schema_supported['schema'], 'name' => $schema_supported['table'], 'data' => ['columns' => $columns, 'owner' => $owner, 'full_table_name' => $schema_supported['full_table_name'], 'engine' => $engine]], $model->db_link);

			// processing constraints
			if (!empty($model->table_constraints)) {
				foreach ($model->table_constraints as $k => $v) {
					$v['full_table_name'] = $schema_supported['full_table_name'];
					$this->object_add(['type' => 'constraint', 'schema' => $schema_supported['schema'], 'table' => $schema_supported['table'], 'name' => $k, 'data' => $v], $model->db_link);
				}
			}

			// processing indexes
			if (!empty($model->table_indexes)) {
				foreach ($model->table_indexes as $k => $v) {
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
		$result['data']['delete_schemas'] = []; // last

		// new second
		$result['data']['new_schemas'] = []; // first
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
		$result['data']['change_functions'] = [];
		$result['data']['new_triggers'] = []; // after functions
		$result['data']['change_triggers'] = [];

		// new schemas
		foreach ($obj_master['schema'] as $k => $v) {
			if (empty($obj_slave['schema'][$k])) {
				$result['data']['new_schemas'][$k] = array('type' => 'schema', 'data' => $v);
				$result['count']++;
			} else if ($v['owner']!=$obj_slave['schema'][$k]['owner']) {
				$result['data']['new_schema_owners'][$k] = array('type' => 'schema_owner', 'data' => $v);
				$result['count']++;
			}
		}

		// delete schema
		foreach ($obj_slave['schema'] as $k => $v) {
			if (empty($obj_master['schema'][$k])) {
				$result['data']['delete_schemas'][$k] = array('type'=>'schema_delete', 'data' => $v);
				$result['count']++;
			}
		}

		// new tables
		foreach ($obj_master['table'] as $k => $v) {
			foreach ($v as $k2 => $v2) {
				if (empty($obj_slave['table'][$k][$k2])) {
					$result['data']['new_tables'][$v2['full_table_name']] = array('type' => 'table_new', 'data' => $v2);
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
		foreach ($obj_master['table'] as $k=>$v) {
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
							$temp_error = true;
						}
						if ($v3['null'] != $obj_slave['table'][$k][$k2]['columns'][$k3]['null']) {
							$temp_error = true;
						}
						if ($v3['default'] != $obj_slave['table'][$k][$k2]['columns'][$k3]['default']) {
							$temp_error = true;
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
		if (!empty($options['backend'] == 'mysqli')) {
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
									print_r($v4);
									exit;
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
						if (!array_compare_level1($v3['columns'], $obj_slave['constraint'][$k][$k2][$k3]['columns'])) {
							$temp_error = true;
						}
						// todo: comparison for foreign key & check
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
				continue;
			}
		}

		$result['success'] = true;
		return $result;
	}
}