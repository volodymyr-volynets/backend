<?php

namespace Numbers\Backend\Db\MySQLi;
class DDL extends \Numbers\Backend\Db\Common\DDL implements \Numbers\Backend\Db\Common\Interface2\DDL {

	/**
	 * Extra queries
	 *
	 * @var array
	 */
	public static $extra = [];

	/**
	 * Column SQL type
	 *
	 * @param array $column
	 * @return array
	 */
	public function columnSqlType($column) {
		// presetting
		$column = $this->columnSqlTypeBase($column);
		// simple switch would do the work
		switch ($column['type']) {
			case 'boolean':
				$column['sql_type'] = 'tinyint';
				break;
			case 'smallserial':
			case 'serial':
			case 'bigserial':
				$column['sql_type'] = str_replace('serial', 'int', $column['type']);
				// 'auto_increment' => 1, there are issues with auto increment in MySQL, it does not work as a sequence
				break;
			case 'integer':
				$column['sql_type'] = 'int';
				break;
			case 'bcnumeric':
			case 'numeric':
				if ($column['precision'] > 0) {
					$column['sql_type'] = 'decimal(' . $column['precision'] . ',' . $column['scale'] . ')';
				} else {
					$column['sql_type'] = 'decimal(30,10)';
				}
				break;
			case 'char':
				$column['sql_type'] = 'char(' . $column['length'] . ')';
				break;
			case 'varchar':
				$column['sql_type'] = 'varchar(' . $column['length'] . ')';
				break;
			case 'json':
				$column['sql_type'] = 'json';
				break;
			case 'time':
				$column['sql_type'] = 'time';
				break;
			case 'datetime':
				$column['sql_type'] = 'datetime';
				break;
			case 'timestamp':
				$column['sql_type'] = 'datetime(6)';
				break;
			default:
				$column['sql_type'] = $column['type'];
		}
		return $column;
	}

	/**
	 * Load database schema
	 *
	 * @param string $db_link
	 * @return array
	 */
	public function loadSchema($db_link) {
		$result = [
			'success' => false,
			'error' => [],
			'data' => [],
			'count' => []
		];
		$sequence_model = \Factory::model('\Numbers\Backend\Db\Common\Model\Sequences');
		// getting information
		foreach (array('sequences', 'columns', 'constraints', 'functions') as $v) {
			// we only load sequences if we have a sequence table
			if ($v == 'sequences') {
				if (!$sequence_model->dbPresent()) {
					continue;
				}
			}
			$temp = $this->loadSchemaDetails($v, $db_link);
			if (!$temp['success']) {
				$result['error'] = array_merge($result['error'], $temp['error']);
			} else {
				switch ($v) {
					case 'columns':
						$tables = [];
						// small conversion for columns
						foreach ($temp['data'] as $k2 => $v2) {
							foreach ($v2 as $k3 => $v3) {
								foreach ($v3 as $k4 => $v4) {
									// full table name
									if ($k2 == '') {
										$full_table_name = $v4['table_name'];
									} else {
										$full_table_name = $v4['schema_name'] . '.' . $v4['table_name'];
									}
									// preset empty table
									if (!isset($tables[$full_table_name])) {
										$tables[$full_table_name] = [
											'type' => 'table',
											'schema' => $k2,
											'name' => $v4['table_name'],
											'data' => [
												'columns' => [],
												'owner' => $v4['table_owner'],
												'engine' => [],
												'full_table_name' => $full_table_name
											]
										];
									}
									// processing type
									if (in_array($v4['type'], ['tinyint', 'smallint', 'int', 'bigint'])) {
										$sql_type = $type = $v4['type'];
									} else if ($v4['type'] == 'decimal') {
										$type = 'numeric';
										$sql_type = $v4['full_type'];
									} else {
										$sql_type = $type = $v4['full_type'];
									}
									// processing default
									$default = $v4['default'];
									if ($default !== null) {
										if (in_array($v4['type'], ['tinyint', 'smallint', 'int', 'bigint'])) {
											$default = (int) $default;
										} else if ($v4['type'] == 'decimal') {
											$default = (float) $default;
										} else if (is_string($default) && strpos($default, 'CURRENT_TIMESTAMP') !== false) {
											$default = 'now()';
										}
									}
									// see if column is a sequence
									$sequence = false;
									if (!empty($this->objects[$db_link]['sequence'][''][$full_table_name . '_' . $k4 . '_seq'])) {
										$sequence = true;
									}
									// putting column back into array
									$tables[$full_table_name]['data']['columns'][$k4] = [
										'type' => $type,
										'null' => !empty($v4['null']) ? true : false,
										'default' => $default,
										'length' => (int) $v4['length'],
										'precision' => (int) $v4['precision'],
										'scale' => (int) $v4['scale'],
										'sequence' => $sequence,
										'sql_type' => $sql_type
									];
								}
							}
						}
						// add tables
						foreach ($tables as $v2) {
							$this->objectAdd($v2, $db_link);
						}
						break;
					case 'constraints':
						foreach ($temp['data'] as $k2 => $v2) {
							foreach ($v2 as $k3 => $v3) {
								foreach ($v3 as $k4 => $v4) {
									foreach ($v4 as $k5 => $v5) {
										$constraint_type = 'constraint';
										if ($v5['constraint_type'] == 'PRIMARY KEY') {
											if ($k5 == 'PRIMARY') {
												$k5 = $v5['table_name'] . '_pk';
											}
											$temp2 = [
												'type' => 'pk',
												'columns' => explode(',', $v5['column_names']),
												'full_table_name' => $v5['table_name']
											];
										} else if ($v5['constraint_type'] == 'UNIQUE') {
											$temp2 = [
												'type' => 'unique',
												'columns' => explode(',', $v5['column_names']),
												'full_table_name' => $v5['table_name']
											];
										} else if ($v5['constraint_type'] == 'INDEX') {
											$temp2 = [
												'type' => strtolower($v5['index_type']),
												'columns' => explode(',', $v5['column_names']),
												'full_table_name' => $v5['table_name']
											];
											$constraint_type = 'index';
										} else if ($v5['constraint_type'] == 'FOREIGN KEY') {
											$temp2 = [
												'type' => 'fk',
												'columns' => explode(',', $v5['column_names']),
												'foreign_table' => $v5['foreign_table_name'],
												'foreign_columns' => explode(',', $v5['foreign_column_names']),
												'options' => [
													'match' => 'simple',
													'update' => 'cascade',
													'delete' => 'restrict'
												],
												'name' => $v5['constraint_name'],
												'full_table_name' => $v5['table_name']
											];
										} else {
											print_r($v5);
											exit;
										}
										// add constraint
										$this->objectAdd([
											'type' => $constraint_type,
											'schema' => $k3,
											'table' => $v5['table_name'],
											'name' => $k5,
											'data' => $temp2
										], $db_link);
									}
								}
							}
						}
						break;
					case 'sequences':
						// load sequence attributes
						$sequence_attributes = $sequence_model->get();
						// add sequences
						foreach ($temp['data'] as $k2 => $v2) {
							foreach ($v2 as $k3 => $v3) {
								$full_sequence_name = ltrim($v3['schema_name'] . '.' . $v3['sequence_name'], '.');
								$this->objectAdd([
									'type' => 'sequence',
									'schema' => $v3['schema_name'],
									'name' => $v3['sequence_name'],
									'data' => [
										'owner' => $v3['sequence_owner'],
										'full_sequence_name' => $full_sequence_name,
										'full_table_name' => $v3['full_table_name'],
										'type' => $sequence_attributes[$full_sequence_name]['sm_sequence_type'] ?? 'global_simple',
										'prefix' => $sequence_attributes[$full_sequence_name]['sm_sequence_prefix'] ?? '',
										'suffix' => $sequence_attributes[$full_sequence_name]['sm_sequence_suffix'] ?? '',
										'length' => $sequence_attributes[$full_sequence_name]['sm_sequence_length'] ?? 0
									]
								], $db_link);
							}
						}
						break;
					case 'functions':
						foreach ($temp['data'] as $k2 => $v2) {
							foreach ($v2 as $k3 => $v3) {
								$full_function_name = ltrim($v3['schema_name'] . '.' . $v3['function_name'], '.');
								// add object
								$this->objectAdd([
									'type' => 'function',
									'schema' => $k2,
									'name' => $k3,
									'backend' => 'MySQLi',
									'data' => [
										'owner' => $v3['function_owner'],
										'full_function_name' => $full_function_name,
										'header' => null,
										'definition' => $v3['routine_definition']
									]
								], $db_link);
							}
						}
						break;
					default:
						// nothing
				}
			}
		}
		if (empty($result['error'])) {
			$result['success'] = true;
		}
		$result['data'] = $this->objects;
		$result['count'] = $this->count;
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
	public function loadSchemaDetails($type, $db_link, $options = array()) {
		$result = array(
			'success' => false,
			'error' => array(),
			'data' => array()
		);
		// we need to get database name
		$db_object = new \Db($db_link);
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
							b.referenced_table_schema foreign_schema_name,
							b.referenced_table_name foreign_table_name,
							b.referenced_column_name foreign_column_names,
							null match_option,
							null update_rule,
							null delete_rule
						FROM information_schema.table_constraints a
						LEFT JOIN (
							SELECT
								table_schema schema_name,
								table_name,
								constraint_name,
								GROUP_CONCAT(column_name ORDER BY ordinal_position SEPARATOR ',') column_names,
								MAX(referenced_table_schema) referenced_table_schema,
								MAX(referenced_table_name) referenced_table_name,
								GROUP_CONCAT(referenced_column_name ORDER BY ordinal_position SEPARATOR ',') referenced_column_name
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
								AND b.index_name NOT LIKE '%_fk'
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
					WHERE 1=1
						AND a.table_schema = '{$database_name}'
					ORDER BY schema_name, table_name, ordinal_position
TTT;
				break;
			case 'sequences':
				$key = array('schema_name', 'sequence_name');
				$sql = <<<TTT
					SELECT
						'' schema_name,
						sm_sequence_name sequence_name,
						'{$owner}' sequence_owner,
						sm_sequence_type "type",
						sm_sequence_prefix prefix,
						sm_sequence_length length,
						sm_sequence_suffix suffix
					FROM sm_sequences
TTT;
				break;
			case 'functions':
				$key = array('schema_name', 'function_name');
				$sql = <<<TTT
					SELECT
						'' schema_name,
						routine_name function_name,
						'{$owner}' function_owner,
						routine_definition
					FROM information_schema.routines
					WHERE 1=1
						AND routine_type = 'FUNCTION'
						AND routine_schema = '{$database_name}'
TTT;
				break;
			default:
				Throw new \Exception('type?');
		}
		// options
		if (!empty($options['where'])) {
			$sql = "SELECT * FROM (" . $sql . ") a WHERE 1=1 AND " . $db_object->prepareCondition($options['where'], 'AND');
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
	public function renderSql($type, $data, $options = array()) {
		$result = '';
		switch ($type) {
			// columns
			case 'column_delete':
				$result = "ALTER TABLE {$data['table']} DROP COLUMN {$data['name']};";
				break;
			case 'column_new':
				$default = $data['data']['default'] ?? null;
				if (is_string($default) && $default != 'now()') {
					$default = "'" . $default . "'";
				} else if ($default === 'now()') {
					if ($data['data']['sql_type'] == 'datetime(6)') {
						$default = 'CURRENT_TIMESTAMP(6)';
					} else {
						$default = 'CURRENT_TIMESTAMP';
					}
				}
				$null = $data['data']['null'] ?? false;
				if (empty($options['column_new_no_alter'])) {
					$result = "ALTER TABLE {$data['table']} ADD COLUMN {$data['name']} {$data['data']['sql_type']}" . ($default !== null ? (' DEFAULT ' . $default) : '') . (!$null ? (' NOT NULL') : '') . ";";
				} else {
					$result = "{$data['name']} {$data['data']['sql_type']}" . ($default !== null ? (' DEFAULT ' . $default) : '') . (!$null ? (' NOT NULL') : '');
				}
				break;
			case 'column_change':
				$result = [];
				$master = $data['data'];
				$slave = $data['data_old'];
				if ($master['sql_type'] !== $slave['sql_type']) {
					$result[]= "ALTER TABLE {$data['table']} ALTER COLUMN {$data['name']} SET DATA TYPE {$master['sql_type']};\n";
				}
				if ($master['default'] !== $slave['default']) {
					if (is_string($master['default'])) {
						$master['default'] = "'" . $master['default'] . "'";
					}
					$temp = !isset($master['default']) ? ' DROP DEFAULT' : ('SET DEFAULT ' . $master['default']);
					$result[]= "ALTER TABLE {$data['table']} ALTER COLUMN {$data['name']} $temp;\n";
				}
				if ($master['null'] !== $slave['null']) {
					$temp = !empty($master['null']) ? 'DROP'  : 'SET';
					$result[]= "ALTER TABLE {$data['table']} ALTER COLUMN {$data['name']} $temp NOT NULL;\n";
				}
				break;
			// table
			case 'table_new':
				$columns = [];
				foreach ($data['data']['columns'] as $k => $v) {
					$columns[] = $this->renderSql('column_new', ['table' => '', 'name' => $k, 'data' => $v], ['column_new_no_alter' => true]);
				}
				$result = "CREATE TABLE {$data['data']['full_table_name']} (\n\t";
					$result.= implode(",\n\t", $columns);
					$engine = isset($data['data']['engine']['MySQLi']) ? $data['data']['engine']['MySQLi'] : 'InnoDB';
				$result.= "\n) ENGINE={$engine} DEFAULT CHARSET=utf8;";
				break;
			case 'table_delete':
				$result = "DROP TABLE {$data['data']['full_table_name']} CASCADE;";
				break;
			// view
			case 'view_new':
				$result = "CREATE OR REPLACE VIEW {$data['name']} AS {$data['definition']}\nALTER VIEW {$data['name']} OWNER TO {$data['owner']};";
				break;
			case 'view_change':
				$result = "DROP VIEW {$data['name']};\nCREATE OR REPLACE VIEW {$data['name']} AS {$data['definition']}\nALTER VIEW {$data['name']} OWNER TO {$data['owner']};";
				break;
			case 'view_delete':
				$result = "DROP VIEW {$data['name']};";
				break;
			// foreign key/unique/primary key
			case 'constraint_new':
				switch ($data['data']['type']) {
					case 'pk':
						$result = "ALTER TABLE {$data['data']['full_table_name']} ADD CONSTRAINT {$data['name']} PRIMARY KEY (" . implode(", ", $data['data']['columns']) . ");";
						break;
					case 'unique':
						$result = "ALTER TABLE {$data['data']['full_table_name']} ADD CONSTRAINT {$data['name']} UNIQUE (" . implode(", ", $data['data']['columns']) . ");";
						break;
					case 'fk':
						$result = "ALTER TABLE {$data['data']['full_table_name']} ADD CONSTRAINT {$data['name']} FOREIGN KEY (" . implode(", ", $data['data']['columns']) . ") REFERENCES {$data['data']['foreign_table']} (" . implode(", ", $data['data']['foreign_columns']) . ") ON UPDATE " . strtoupper($data['data']['options']['update'] ?? 'NO ACTION') . " ON DELETE " . strtoupper($data['data']['options']['delete'] ?? 'NO ACTION') . ";";
						break;
					default:
						Throw new \Exception($data['data']['type'] . '?');
				}
				break;
			case 'constraint_delete':
				// drop primary key
				if ($data['data']['type'] == 'pk') {
					$result = "ALTER TABLE {$data['data']['full_table_name']} DROP PRIMARY KEY;";
				} else if ($data['data']['type'] == 'fk') {
					$result = [
						"ALTER TABLE {$data['data']['full_table_name']} DROP FOREIGN KEY {$data['name']};",
						"ALTER TABLE {$data['data']['full_table_name']} DROP INDEX {$data['name']};"
					];
				} else if ($data['data']['type'] == 'unique') {
					$result = "ALTER TABLE {$data['data']['full_table_name']} DROP INDEX {$data['name']};";
				}
				break;
			// indexes
			case 'index_new':
				// fulltext indexes as gin
				if ($data['data']['type'] == 'fulltext') {
					$result = "CREATE FULLTEXT INDEX {$data['name']} ON {$data['data']['full_table_name']} (" . implode(", ", $data['data']['columns']) . ");";
				} else {
					$result = "CREATE INDEX {$data['name']} ON {$data['data']['full_table_name']} (" . implode(", ", $data['data']['columns']) . ") USING {$data['data']['type']};";
				}
				break;
			case 'index_delete':
				$result = "DROP INDEX {$data['name']};";
				break;
			// sequences
			case 'sequence_new':
				// insert entry into sequences table
				$model = new \Numbers\Backend\Db\Common\Model\Sequences();
				$result = <<<TTT
					INSERT INTO {$model->full_table_name} (
						sm_sequence_name,
						sm_sequence_description,
						sm_sequence_prefix,
						sm_sequence_length,
						sm_sequence_suffix,
						sm_sequence_counter,
						sm_sequence_type
					) VALUES (
						'{$data['data']['full_sequence_name']}',
						null,
						'{$data['data']['prefix']}',
						{$data['data']['length']},
						'{$data['data']['suffix']}',
						0,
						'{$data['data']['type']}'
					);
TTT;
				break;
			case 'sequence_delete':
				$result = [];
				if (($options['mode'] ?? '') != 'drop') {
					$model = new \Numbers\Backend\Db\Common\Model\Sequences();
					$result[]= "DELETE FROM {$model->full_table_name} WHERE sm_sequence_name = '{$data['data']['full_sequence_name']}'";
				}
				break;
			// functions
			case 'function_new':
				$result = $data['data']['definition'] . ";";
				break;
			case 'function_delete':
				$result = "DROP FUNCTION {$data['data']['full_function_name']};";
				break;
			// trigger
			case 'trigger_new':
				$result.= trim($data['definition']) . ";";
				break;
			case 'trigger_delete':
				$result = "DROP TRIGGER {$data['name']} ON {$data['table']};";
				break;
			case 'trigger_change':
				$result = "DROP TRIGGER {$data['name']} ON {$data['table']};\n";
				$result.= trim($data['definition']) . ";";
				break;
			case 'permission_revoke_all':
				//$result = "REVOKE ALL PRIVILEGES ON DATABASE {$data['database']} FROM {$data['owner']};";
				break;
			case 'permission_grant_schema':
				//$result = "GRANT USAGE ON SCHEMA {$data['schema']} TO {$data['owner']};";
				break;
			case 'permission_grant_table':
				$result = "GRANT SELECT, INSERT, UPDATE, DELETE ON {$data['database']}.{$data['table']} TO {$data['owner']};";
				break;
			case 'permission_grant_sequence':
				//$result = "GRANT USAGE, SELECT, UPDATE ON SEQUENCE {$data['sequence']} TO {$data['owner']};";
				break;
			case 'permission_grant_function':
				$result = "GRANT EXECUTE ON FUNCTION {$data['database']}.{$data['function']} TO {$data['owner']};";
				break;
			default:
				// nothing
				Throw new \Exception($type . '?');
		}
		return $result;
	}
}