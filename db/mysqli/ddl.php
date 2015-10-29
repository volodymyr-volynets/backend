<?php

class numbers_backend_db_mysqli_ddl extends numbers_backend_db_class_ddl implements numbers_backend_db_interface_ddl {

	/**
	 * Check is schema suported
	 *
	 * @param string $table_name
	 * @return boolean
	 */
	public function is_schema_supported($table_name) {
		$temp = explode('.', $table_name);
		if (count($temp) > 1) {
			return [
				'success' => true,
				'error' => [],
				'schema' => '',
				'table' => $temp[0] . '_' . $temp[1],
				'full_table_name' => $temp[0] . '_' . $temp[1]
			];
		} else {
			return [
				'success' => true,
				'error' => [],
				'schema' => '',
				'table' => $temp[0],
				'full_table_name' => $temp[0]
			];
		}
	}

	/**
	 * Column type checker and converter
	 *
	 * @param array $column
	 * @param object $table_object
	 * @return array
	 */
	public function is_column_type_supported($column, $table_object) {
		$result = [
			'success' => true,
			'error' => [],
			'column' => []
		];

		// presetting
		$column['type'] = isset($column['type']) ? $column['type'] : 'text';
		$column['null'] = isset($column['null']) ? $column['null'] : false;
		$column['default'] = isset($column['default']) ? $column['default'] : null;
		$column['length'] = isset($column['length']) ? $column['length'] : 0;
		$column['precision'] = isset($column['precision']) ? $column['precision'] : 0;
		$column['scale'] = isset($column['scale']) ? $column['scale'] : 0;

		// simple switch would do the work
		switch ($column['type']) {
			case 'boolean':
				$result['column'] = ['type' => 'tinyint', 'null' => false, 'default' => 0];
				break;
			case 'smallint':
			case 'bigint':
				$result['column'] = ['type' => $column['type'], 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'integer':
				$result['column'] = ['type' => 'int', 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'numeric':
				if ($column['precision'] > 0) {
					$result['column'] = ['type' => 'decimal(' . $column['precision'] . ', ' . $column['scale'] . ')', 'null' => $column['null'], 'default' => $column['default']];
				} else {
					$result['column'] = ['type' => 'decimal(30,10)', 'null' => $column['null'], 'default' => $column['default']];
				}
				break;
			case 'smallserial':
			case 'serial':
			case 'bigserial':
				$temp = str_replace('serial', 'int', $column['type']);
				$result['column'] = ['type' => $temp, 'auto_increment' => 1];
				break;
			case 'char':
				$temp = 'char(' . $column['length'] . ')';
				$result['column'] = ['type' => $temp, 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'varchar':
				$temp = 'varchar(' . $column['length'] . ')';
				$result['column'] = ['type' => $temp, 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'json':
				$result['column'] = ['type' => 'json', 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'date':
			case 'time':
			case 'datetime':
				$result['column'] = ['type' => $column['type'], 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'numbers_code':
				$result['column'] = ['type' => 'varchar(50)', 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'numbers_hash':
				$result['column'] = ['type' => 'varchar(128)', 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'text':
				$result['column'] = ['type' => 'text', 'null' => $column['null'], 'default' => $column['default']];
			default:
				// if we got here, means we do not replace data type and send it to db as is !!!
				$result['column'] = ['type' => $column['type'], 'null' => $column['null'], 'default' => $column['default']];
		}
		return $result;
	}

	/**
	 * Load database schema
	 *
	 * @param string $db_link
	 * @return array
	 */
	public function load_schema($db_link) {
		$result = [
			'success' => false,
			'error' => [],
			'data' => []
		];
		// getting information
		foreach (array('columns', 'constraints') as $v) {
			$temp = $this->load_schema_details($v, $db_link);
			if (!$temp['success']) {
				$result['error'] = array_merge($result['error'], $temp['error']);
			} else {
				switch ($v) {
					case 'columns':
						// small conversion for columns
						foreach ($temp['data'] as $k2 => $v2) {
							foreach ($v2 as $k3 => $v3) {
								foreach ($v3 as $k4 => $v4) {
									// processing type
									if (in_array($v4['type'], ['tinyint', 'smallint', 'int', 'bigint'])) {
										$type = $v4['type'];
									} else {
										$type = $v4['full_type'];
									}
									// processing default
									$default = $v4['default'];
									if ($default !== null) {
										if (in_array($v4['type'], ['tinyint', 'smallint', 'int', 'bigint'])) {
											$default = (int) $default;
										} else if ($v4['type'] == 'decimal') {
											$default = (float) $default;
										} else {
											// as is
										}
									}
									$temp2 = [
										'type' => $type,
										'null' => ($v4['null'] ? true : false),
										'default' => $default,
										'auto_increment' => $v4['auto_increment']
									];
									// putting column back into array
									$result['data']['table'][$k2][$k3]['columns'][$k4] = $temp2;
									if (!isset($result['data']['table'][$k2][$k3]['owner'])) {
										$result['data']['table'][$k2][$k3]['owner'] = $v4['table_owner'];
									}
									if (!isset($result['data']['table'][$k2][$k3]['full_table_name'])) {
										$result['data']['table'][$k2][$k3]['full_table_name'] = $v4['table_name'];
									}
								}
							}
						}
						break;
					case 'constraints':
						foreach ($temp['data'] as $k2 => $v2) {
							foreach ($v2 as $k3 => $v3) {
								foreach ($v3 as $k4 => $v4) {
									foreach ($v4 as $k5 => $v5) {
										if ($v5['constraint_type'] == 'PRIMARY KEY') {
											$temp2 = [
												'type' => 'pk',
												'columns' => explode(',', $v5['column_names']),
												'full_table_name' => $v5['table_name']
											];
											$result['data']['constraint'][$k3][$k4][$k5] = $temp2;
										} else if ($v5['constraint_type'] == 'UNIQUE') {
											$temp2 = [
												'type' => 'unique',
												'columns' => explode(',', $v5['column_names']),
												'full_table_name' => $v5['table_name']
											];
											$result['data']['constraint'][$k3][$k4][$k5] = $temp2;
										} else if ($v5['constraint_type'] == 'INDEX') {
											$temp2 = [
												'type' => strtolower($v5['index_type']),
												'columns' => explode(',', $v5['column_names']),
												'full_table_name' => $v5['table_name']
											];
											$result['data']['index'][$k3][$k4][$k5] = $temp2;
										} else {
											print_r($v5);
											exit;
										}
									}
								}
							}
						}
						break;
					default:
						// todo: add sequencing processing
						// nothing
				}
			}
		}
		if (empty($result['error'])) {
			$result['success'] = true;
		}
		return $result;
	}

	/**
	 * Get schema details
	 *
	 * @param string $type
	 * @param string $db_link
	 * @param array $options
	 * @return array
	 * @throws Exception
	 */
	public function load_schema_details($type, $db_link, $options = array()) {
		$result = array(
			'success' => false,
			'error' => array(),
			'data' => array()
		);

		// we need to get database name
		$db_object = new db($db_link);
		$database_name = $db_object->object->connect_options['dbname'];
		$owner = $db_object->object->connect_options['username'];

		// getting proper query
		switch($type) {
			case 'constraints':
				$key = array('constraint_type', 'schema_name', 'table_name', 'constraint_name');
				$sql = <<<TTT
					SELECT
							*
					FROM (
						SELECT
							a.constraint_type,
							null schema_name,
							a.table_name,
							a.constraint_name,
							null index_type,
							b.column_names,
							null foreign_schema_name,
							null foreign_table_name,
							null foreign_column_names,
							null match_option,
							null update_rule,
							null delete_rule
						FROM information_schema.table_constraints a
						LEFT JOIN (
							SELECT
								table_schema schema_name,
								table_name,
								constraint_name,
								GROUP_CONCAT(column_name ORDER BY ordinal_position SEPARATOR ',') column_names
							FROM information_schema.key_column_usage c
							WHERE 1=1
								AND c.table_schema = '{$database_name}'
							GROUP BY table_schema, table_name, constraint_name
						) b ON a.table_schema = b.schema_name AND a.table_name = b.table_name AND a.constraint_name = b.constraint_name
						WHERE 1=1
							AND a.table_schema = '{$database_name}'

						UNION ALL

						SELECT
							'INDEX' constraint_type,
							null schema_name,
							a.table_name,
							a.index_name constraint_name,
							MAX(a.index_type) index_type,
							GROUP_CONCAT(a.column_name ORDER BY a.seq_in_index SEPARATOR ',') column_names,
							null foreign_schema_name,
							null foreign_table_name,
							null foreign_column_names,
							null match_option,
							null update_rule,
							null delete_rule
						FROM (
							SELECT
								*
							FROM information_schema.statistics b
							WHERE 1=1
								AND b.table_schema = '{$database_name}'
								AND b.non_unique = 1
							ORDER BY b.seq_in_index
						) a
						GROUP BY a.table_schema, a.table_name, a.index_name
					) a
TTT;
				break;
			 case 'columns':
				$key = array('schema_name', 'table_name', 'column_name');
				$sql = <<<TTT
					SELECT
						null schema_name,
						a.table_name,
						'{$owner}' table_owner,
						a.column_name,
						a.data_type "type",
						CASE WHEN a.is_nullable = 'NO' THEN 0 ELSE 1 END "null",
						a.column_default "default",
						a.character_maximum_length "length",
						a.numeric_precision "precision",
						a.numeric_scale "scale",
						a.column_type "full_type",
						b.engine "engine",
						CASE WHEN a.extra LIKE '%auto_increment%' THEN 1 ELSE 0 END auto_increment
					FROM information_schema.columns a
					LEFT JOIN (
						SELECT
							table_schema schema_name,
							table_name,
							engine
						FROM information_schema.tables
						WHERE 1=1
							AND table_schema = '{$database_name}'
							AND engine IS NOT NULL
					) b ON a.table_schema = b.schema_name AND a.table_name = b.table_name
					WHERE a.table_schema = '{$database_name}'
					ORDER BY schema_name, table_name, ordinal_position
TTT;
				break;
			default:
				Throw new Exception('type?');
		}
		// options
		if (!empty($options['where'])) {
			$sql = "SELECT * FROM (" . $sql . ") a WHERE 1=1 AND " . $db_object->prepare_condition($options['where'], 'AND');
		}
		$result2 = $db_object->query($sql, $key);
		if ($result2['error']) {
			$result['error'] = array_merge($result['error'], $result2['error']);
		} else {
			$result['data'] = $result2['rows'];
			$result['success'] = true;
		}
		return $result;
	}

	/**
	 * Render sql
	 * 
	 * @param string $type
	 * @param array $data
	 * @param array $options
	 * @return string
	 * @throws Exception
	 */
	public function render_sql($type, $data, $options = array()) {
		$result = '';
		switch ($type) {
			// columns
			case 'column_delete':
				$result = "ALTER TABLE {$data['table']} DROP COLUMN {$data['name']};";
				break;
			case 'column_new':
				$type = $data['data']['type'];
				$default = $data['data']['default'];
				if (is_string($default)) {
					$default = "'" . $default . "'";
				}
				$null = $data['data']['null'];
				if (empty($options['column_new_no_alter'])) {
					$result = "ALTER TABLE {$data['table']} ADD COLUMN {$data['name']} {$type}" . ($default !== null ? (' DEFAULT ' . $default) : '') . (!$null ? (' NOT NULL') : '') . ";";
				} else {
					$result = "{$data['name']} {$type}" . ($default !== null ? (' DEFAULT ' . $default) : '') . (!$null ? (' NOT NULL') : '');
				}
				break;
			case 'column_change':
				$master = $data['data'];
				$result = "ALTER TABLE {$data['table']} CHANGE {$data['name']} {$data['name']} {$master['type']}" . (!$master['null'] ? ' NOT NULL' : '') . ($master['default'] !== null ? (" DEFAULT " . (is_string($master['default']) ? ("'" . $master['default'] . "'") : $master['default'])) : '') . ";";
				break;
			// table
			case 'table_owner':
				$result = "ALTER TABLE {$data['data']['full_table_name']} OWNER TO {$data['data']['owner']};";
				break;
			case 'table_new':
				$columns = array();
				foreach ($data['data']['columns'] as $k => $v) {
					$columns[] = $this->render_sql('column_new', ['table' => '', 'name' => $k, 'data' => $v], ['column_new_no_alter' => true]);
				}
				$result = "CREATE TABLE {$data['data']['full_table_name']} (\n\t";
					$result.= implode(",\n\t", $columns);
				$engine = isset($data['data']['engine']) ? $data['data']['engine'] : 'InnoDB';
				$result.= "\n) ENGINE={$engine} DEFAULT CHARSET=utf8;";
				break;
			case 'table_delete':
				$result = "DROP TABLE {$data['data']['full_table_name']};";
				break;
			// foreign key/unique/primary key
			case 'constraint_new':
				switch ($data['data']['type']) {
					case 'pk':
						$result = "ALTER TABLE {$data['data']['full_table_name']} ADD PRIMARY KEY (" . implode(", ", $data['data']['columns']) . ");";
						break;
					default:
						Throw new Exception($data['data']['type'] . '?');
				}
				/*
				if ($data['index']['constraint_type']=='INDEX') {
					$result = "CREATE INDEX {$data['name']} ON {$data['table']} USING {$data['index']['index_type']} (" . implode(", ", $data['index']['column_names']) . ");";
				} else if (in_array($data['data']['type'], array('PRIMARY KEY', 'UNIQUE'))) {
					$result = "ALTER TABLE {$data['table']} ADD CONSTRAINT {$data['name']} {$data['index']['constraint_type']} (" . implode(", ", $data['index']['column_names']) . ");";
				} else if ($data['index']['constraint_type']=='FOREIGN_KEY') {
					if ($data['index']['match_option']=='NONE') $data['index']['match_option'] = 'SIMPLE';
					$result = "ALTER TABLE {$data['table']} ADD CONSTRAINT {$data['name']} FOREIGN KEY (" . implode(", ", $data['index']['column_names']) . ") REFERENCES {$data['index']['foreign_schema_name']}.{$data['index']['foreign_table_name']} (" . implode(", ", $data['index']['foreign_column_names']) . ") MATCH {$data['index']['match_option']} ON UPDATE {$data['index']['update_rule']} ON DELETE {$data['index']['delete_rule']};";
				} else if ($data['index']['constraint_type']=='CHECK') {
					$result = "ALTER TABLE {$data['table']} ADD CONSTRAINT {$data['name']} CHECK {$data['index']['match_option']};";
				}
				 * 
				 */
				break;
			case 'constraint_delete':
				if ($data['data']['type'] == 'pk') {
					$result = "ALTER TABLE {$data['data']['full_table_name']} DROP PRIMARY KEY;";
				} else if ($data['data']['type'] == 'unique') {
					$result = "ALTER TABLE {$data['data']['full_table_name']} DROP INDEX {$data['name']};";
				} else {
					Throw new Exception($data['data']['type'] . '?');
				}
				break;
			// indexes
			case 'index_new':
				$result = "CREATE INDEX {$data['name']} ON {$data['data']['full_table_name']} (" . implode(", ", $data['data']['columns']) . ") USING {$data['data']['type']};";
				break;
			case 'index_delete':
				$result = "DROP INDEX {$data['name']} ON {$data['data']['full_table_name']};";
				break;
			default:
				// nothing
				Throw new Exception($type . '?');
		}
		return $result;
	}
}