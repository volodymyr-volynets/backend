<?php

namespace Numbers\Backend\Db\Oracle;
class DDL extends \Numbers\Backend\Db\Common\DDL implements \Numbers\Backend\Db\Common\Interface2\DDL {

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
				$column['sql_type'] = 'number(3, 0)';
				break;
			case 'smallserial':
			case 'serial':
			case 'bigserial':
			case 'smallint':
			case 'integer':
			case 'bigint':
				$overrides = [
					'smallserial' => 5,
					'serial' => 10,
					'bigserial' => 19,
					'smallint' => 5,
					'integer' => 10,
					'bigint' => 19,
				];
				$column['sql_type'] = 'number(' . $overrides[$column['type']] . ', 0)';
				break;
			case 'bcnumeric':
			case 'numeric':
				if ($column['precision'] > 0) {
					$column['sql_type'] = 'number(' . $column['precision'] . ', ' . $column['scale'] . ')';
				} else {
					$column['sql_type'] = 'number';
				}
				break;
			case 'char':
				$column['sql_type'] = 'char(' . $column['length'] . ')';
				break;
			case 'varchar':
				$column['sql_type'] = 'varchar2(' . $column['length'] . ')';
				break;
			case 'json':
				$column['sql_type'] = 'clob';
				break;
			case 'time':
				$column['sql_type'] = 'timestamp(6)';
				break;
			case 'datetime':
				$column['sql_type'] = 'timestamp(0)';
				break;
			case 'timestamp':
				$column['sql_type'] = 'timestamp(6)';
				break;
			case 'text':
				$column['sql_type'] = 'clob';
				break;
			default:
				$column['sql_type'] = $column['type'];
		}
		return $column;
	}

	/**
	 * Handle name
	 *
	 * @param string $schema
	 * @param string $name
	 * @param array $options
	 *		db_link
	 * @return string
	 */
	public function handleName(& $schema, & $name, $options = []) {
		if (empty($schema)) $schema = 'public2';
		return $schema . '.' . $name;
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
		// getting information
		$counter = 0;
		$ddl_objects = ['schemas', 'columns', 'sequences', 'constraints', 'functions', 'views', 'triggers'];
		foreach ($ddl_objects as $v) {
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
									$full_table_name = $v4['schema_name'] . '.' . $v4['table_name'];
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
									$sql_type = $type = $v4['type'];
									$default = $v4['default'];
									$sequence = false;
									if ($type == 'number' && $v4['precision'] > 0) {
										$sql_type = 'number(' . $v4['precision'] . ', ' . $v4['scale'] . ')';
									} else if (strpos($type, 'timestamp') !== false) {
										$type = 'timestamp';
										$sql_type = 'timestamp(' . $v4['scale'] . ')';
										$v4['length'] = 0;
										$v4['scale'] = 0;
										if (trim($default) == 'LOCALTIMESTAMP') {
											$default = 'now()';
										}
									} else if ($type == 'date') {
										$v4['length'] = 0;
									} else if ($v4['length'] > 0) {
										$sql_type.= '(' . $v4['length'] . ')';
									}
									// processing default
									if (isset($default)) {
										$default = trim($default);
										$default = trim($default, "'");
										if ($default == 'NULL') {
											$default = null;
										} else if (is_numeric($default) && $v4['type'] == 'number') {
											$default = $default * 1;
										}
									}
									// serial types
									if ($v4['type'] == 'number') {
										if (strpos($v4['comments'], 'sequence') !== false) {
											if ($v4['precision'] == 5) {
												$type = 'smallserial';
											} else if ($v4['precision'] == 10) {
												$type = 'serial';
											} else if ($v4['precision'] == 19) {
												$type = 'bigserial';
											}
											$sequence = true;
											// we need to zero out
											$default = null;
											$v4['precision'] = 0;
											$v4['scale'] = 0;
										} else if (strpos($v4['comments'], 'bcnumeric') !== false) {
											$type = 'bcnumeric';
											if (!empty($default)) {
												$default = trim($default, "'");
											}
										} else if (strpos($v4['comments'], 'numeric') !== false) {
											$type = 'numeric';
											$sql_type = 'number';
											if (!empty($default)) {
												$default = trim($default, "'");
											}
										} else if ($v4['scale'] == 0) {
											if ($v4['precision'] == 5) {
												$type = 'smallint';
											} else if ($v4['precision'] == 10) {
												$type = 'integer';
											} else if ($v4['precision'] == 19) {
												$type = 'bigint';
											}
											$v4['precision'] = 0;
										}
										$v4['length'] = 0;
									} else if ($v4['type'] == 'varchar2') { // varchar
										$type = 'varchar';
									} else if (in_array($v4['type'], ['clob', 'blob'])) { // text, json
										if (strpos($v4['comments'], 'json') !== false) {
											$type = 'json';
											$sql_type = 'clob';
										} else if ($v4['type'] == 'clob') {
											$type = 'text';
											$sql_type = 'clob';
										}
										$v4['length'] = 0;
									}
									// putting column back into array
									$tables[$full_table_name]['data']['columns'][$k4] = [
										'type' => $type,
										'null' => !empty($v4['null']) ? true : false,
										'default' => $default,
										'length' => $v4['length'],
										'precision' => $v4['precision'],
										'scale' => $v4['scale'],
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
								if ($k3 == 'public') {
									$k3 = '';
								}
								foreach ($v3 as $k4 => $v4) {
									foreach ($v4 as $k5 => $v5) {
										$constraint_type = 'constraint';
										$full_table_name = $v5['schema_name'] . '.' . $v5['table_name'];
										if ($v5['constraint_type'] == 'PRIMARY KEY') {
											$temp2 = [
												'type' => 'pk',
												'columns' => explode(',', $v5['column_names']),
												'full_table_name' => $full_table_name
											];
										} else if ($v5['constraint_type'] == 'UNIQUE') {
											$temp2 = [
												'type' => 'unique',
												'columns' => explode(',', $v5['column_names']),
												'full_table_name' => $full_table_name
											];
										} else if ($v5['constraint_type'] == 'INDEX') {
											// gin
											if ($v5['index_type'] == 'gin') {
												$v5['index_type'] = 'fulltext';
											}
											$temp2 = [
												'type' => mixedtolower($v5['index_type']),
												'columns' => explode(',', $v5['column_names']),
												'full_table_name' => $full_table_name
											];
											$constraint_type = 'index';
										} else if ($v5['constraint_type'] == 'FOREIGN_KEY') {
											$name2 = $v5['foreign_schema_name'] . '.' . $v5['foreign_table_name'];
											if ($v5['match_option'] == 'NONE') $v5['match_option'] = 'SIMPLE';
											$temp2 = [
												'type' => 'fk',
												'columns' => explode(',', $v5['column_names']),
												'foreign_table' => $name2,
												'foreign_columns' => explode(',', $v5['foreign_column_names']),
												'options' => [
													'match' => mixedtolower($v5['match_option']),
													'update' => mixedtolower($v5['update_rule']),
													'delete' => mixedtolower($v5['delete_rule'])
												],
												'name' => $v5['constraint_name'],
												'full_table_name' => $full_table_name
											];
										} else {
											print_r2($v5);
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
					// extensions
					case 'extensions':
						$result['data']['extension'] = [];
						foreach ($temp['data'] as $k4 => $v4) {
							foreach ($v4 as $k5 => $v5) {
								if ($v5['schema_name'] == 'public') $v5['schema_name'] = '';
								$this->objectAdd([
									'type' => 'extension',
									'schema' => $v5['schema_name'],
									'name' => $v5['extension_name'],
									'backend' => 'PostgreSQL' // a must
								], $db_link);
							}
						}
						break;
					// schemas
					case 'schemas':
						foreach ($temp['data'] as $k2 => $v2) {
							$this->objectAdd([
								'type' => 'schema',
								'name' => $v2['name'],
								'data' => [
									'owner' => $v2['owner'],
									'name' => $v2['name']
								]
							], $db_link);
						}
						break;
					// sequences
					case 'sequences':
						// load sequence attributes
						$sequence_attributes = [];
						$sequence_model = \Factory::model('\Numbers\Backend\Db\Common\Model\Sequences');
						if ($sequence_model->dbPresent()) {
							$sequence_attributes = $sequence_model->get();
						}
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
									'backend' => 'Oracle',
									'data' => [
										'owner' => $v3['function_owner'],
										'full_function_name' => $full_function_name,
										'header' => $v3['full_function_name'],
										'definition' => $v3['routine_definition']
									]
								], $db_link);
							}
						}
						break;
					case 'triggers':
						foreach ($temp['data'] as $k2 => $v2) {
							foreach ($v2 as $k3 => $v3) {
								$full_function_name = ltrim($v3['schema_name'] . '.' . $v3['function_name'], '.');
								// add object
								$this->objectAdd([
									'type' => 'trigger',
									'schema' => $k2,
									'name' => $k3,
									'backend' => 'Oracle',
									'data' => [
										'full_function_name' => $full_function_name,
										'full_table_name' => $v3['full_table_name'],
										'header' => $full_function_name,
										'definition' => $v3['routine_definition']
									]
								], $db_link);
							}
						}
						break;
					case 'views':
						foreach ($temp['data'] as $k2 => $v2) {
							foreach ($v2 as $k3 => $v3) {
								// add object
								$this->objectAdd([
									'type' => 'view',
									'schema' => $k2,
									'name' => $k3,
									'backend' => 'Oracle',
									'data' => [
										'full_view_name' => $v3['full_view_name'],
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
			$counter++;
			// update progress bar
			\Helper\Cmd::progressBar(25 + round($counter / count($ddl_objects) * 100 / 4, 0), 100, 'Loading DB objects');
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
		$db_object = new \Db($db_link);
		// schema users
		$schema_users = $db_object->object->sqlHelper('numbers_applications_schemas');
		// getting proper query
		switch($type) {
			case 'schemas':
				$key = array('name');
				$sql = <<<TTT
					SELECT
						LOWER(a.username) "name",
						LOWER(a.username) "owner"
					FROM dba_users a
					WHERE a.username IN ({$schema_users})
TTT;
				break;
			 case 'columns':
				$key = array('schema_name', 'table_name', 'column_name');
				$sql = <<<TTT
					SELECT
						LOWER(a.owner) "schema_name",
						LOWER(a.table_name) "table_name",
						LOWER(a.owner) "table_owner",
						LOWER(a.column_name) "column_name",
						LOWER(a.data_type) "type",
						CASE WHEN nullable = 'N' THEN 0 ELSE 1 END "null",
						a.data_default "default",
						COALESCE(a.data_length, 0) "length",
						COALESCE(a.data_precision, 0) "precision",
						COALESCE(a.data_scale, 0) "scale",
                        b.comments "comments"
					FROM all_tab_columns a
                    LEFT JOIN all_col_comments b ON a.owner = b.owner AND a.table_name = b.table_name AND a.column_name = b.column_name
					WHERE 1=1
						AND a.owner IN ({$schema_users})
						AND INSTR(a.table_name, '$') = 0
						AND EXISTS (
                            SELECT 1 FROM dba_tables x WHERE x.owner = a.owner AND x.table_name = a.table_name
                        )
                    ORDER BY "schema_name", "table_name", column_id
TTT;
				break;
			case 'constraints':
				$key = array('constraint_type', 'schema_name', 'table_name', 'constraint_name');
				$sql = <<<TTT
					SELECT
							*
					FROM (
						SELECT
							CASE
								WHEN a.constraint_type = 'R' THEN 'FOREIGN_KEY'
								WHEN a.constraint_type = 'P' THEN 'PRIMARY KEY'
								WHEN a.constraint_type = 'U' THEN 'UNIQUE'
							END constraint_type,
							LOWER(a.owner) schema_name,
							LOWER(a.table_name) table_name,
							LOWER(a.constraint_name) constraint_name,
							NULL index_type,
							b.column_names,
							LOWER(c.owner) foreign_schema_name,
							LOWER(c.table_name) foreign_table_name,
							d.column_names foreign_column_names,
							NULL match_option,
							NULL update_rule,
							a.delete_rule
						FROM all_constraints a
						INNER JOIN (
							SELECT
								a.owner schema_name,
								a.table_name table_name,
								a.constraint_name constraint_name,
								LISTAGG(LOWER(a.column_name), ',') WITHIN GROUP (ORDER BY a.position) column_names
							FROM all_cons_columns a
							WHERE a.owner IN ({$schema_users})
							GROUP BY a.owner, a.table_name, a.constraint_name
						) b ON a.owner = b.schema_name AND a.table_name = b.table_name AND a.constraint_name = b.constraint_name
						LEFT JOIN all_constraints c ON a.r_owner = c.owner AND a.r_constraint_name = c.constraint_name
						LEFT JOIN (
							SELECT
								a.owner schema_name,
								a.table_name table_name,
								a.constraint_name constraint_name,
								LISTAGG(LOWER(a.column_name), ',') WITHIN GROUP (ORDER BY a.position) column_names
							FROM all_cons_columns a
							WHERE a.owner IN ({$schema_users})
							GROUP BY a.owner, a.table_name, a.constraint_name
						) d ON c.owner = d.schema_name AND c.table_name = d.table_name AND c.constraint_name = d.constraint_name
						WHERE 1=1
							AND a.constraint_type IN ('R', 'P', 'U')
							AND a.owner IN ({$schema_users})
							AND INSTR(LOWER(a.table_name), '$') = 0

						UNION ALL

						SELECT
							'INDEX' constraint_type,
							LOWER(a.owner) schema_name,
							LOWER(a.table_name) table_name,
							LOWER(a.index_name) constraint_name,
							CASE
                                WHEN a.ityp_owner = 'CTXSYS' THEN 'fulltext'
								WHEN a.index_type = 'NORMAL' THEN 'btree'
                                ELSE LOWER(a.index_type)
                            END index_type,
							COALESCE(d.column_names, b.column_names) column_names,
							NULL foreign_schema_name,
							NULL foreign_table_name,
							NULL foreign_column_names,
							NULL match_option,
							NULL update_rule,
							NULL delete_rule
						FROM all_indexes a
						INNER JOIN (
							SELECT
								a.index_owner schema_name,
								a.table_name table_name,
								a.index_name constraint_name,
								LISTAGG(LOWER(a.column_name), ',') WITHIN GROUP (ORDER BY a.column_position) column_names
							FROM all_ind_columns a
							WHERE a.index_owner IN ({$schema_users})
							GROUP BY a.index_owner, a.table_name, a.index_name
						) b ON a.owner = b.schema_name AND a.table_name = b.table_name AND a.index_name = b.constraint_name
                        LEFT JOIN (
                            SELECT
                                a.index_owner,
                                a.index_name
                            FROM all_constraints a
                            WHERE 1=1
                                AND a.constraint_type IN ('R', 'P', 'U')
                                AND a.owner IN ({$schema_users})
                        ) c ON a.owner = c.index_owner AND a.index_name = c.index_name
						LEFT JOIN (
                            SELECT
                                a.prv_owner || '.' || a.prv_preference preference,
                                a.prv_value column_names
                            FROM ctxsys.ctx_preference_values a
                            WHERE a.prv_attribute = 'COLUMNS'
								AND a.prv_owner IN ({$schema_users})
                        ) d ON d.preference = REPLACE(REGEXP_SUBSTR(a.parameters, 'DataStore (.+)[$ ]?'), 'DataStore ', '')
						WHERE a.owner IN ({$schema_users})
							AND c.index_owner IS NULL
							AND INSTR(a.table_name, '$') = 0
					) a
TTT;
				break;
			case 'sequences':
				$key = array('schema_name', 'sequence_name');
				$sql = <<<TTT
					SELECT
						LOWER(a.sequence_owner) schema_name,
						LOWER(a.sequence_name) sequence_name,
						LOWER(a.sequence_owner) sequence_owner,
						'' full_table_name
					FROM dba_sequences a
					WHERE a.sequence_owner IN ({$schema_users})
TTT;
				break;
			case 'functions':
				$key = array('schema_name', 'function_name');
				$sql = <<<TTT
					SELECT
						LOWER(a.owner) schema_name,
						LOWER(a.object_name) function_name,
						LOWER(a.owner || '.' || a.object_name) full_function_name,
						LOWER(a.owner) function_owner,
						dbms_metadata.get_ddl(a.object_type, a.object_name, a.owner) routine_definition
					FROM dba_objects a
					WHERE 1=1
						AND a.owner IN ({$schema_users})
						AND a.object_type IN ('FUNCTION', 'PROCEDURE')
TTT;
				break;
			case 'triggers':
				$key = array('schema_name', 'function_name');
				$sql = <<<TTT
					SELECT
						LOWER(a.owner) schema_name,
						LOWER(a.trigger_name) function_name,
						LOWER(a.owner || '.' || a.trigger_name) full_function_name,
						LOWER(a.table_owner || '.' || a.table_name) full_table_name,
						dbms_metadata.get_ddl('TRIGGER', a.trigger_name, a.owner) routine_definition
					FROM dba_triggers a
					WHERE 1=1
						AND a.owner IN ({$schema_users})
TTT;
				break;
			case 'extensions':
				$key = array('schema_name', 'extension_name');
				$sql = <<<TTT
					SELECT
						n.nspname schema_name,
						a.extname extension_name
					FROM pg_catalog.pg_extension a
					LEFT JOIN pg_catalog.pg_namespace n ON a.extnamespace = n.oid
TTT;
				break;
			case 'views':
				$key = array('schema_name', 'view_name');
				$sql = <<<TTT
					SELECT
						LOWER(a.owner) schema_name,
						LOWER(a.view_name) view_name,
						LOWER(a.owner || '.' || a.view_name) full_view_name,
						a.text routine_definition
					FROM dba_views a
					WHERE 1=1
						AND a.owner IN ({$schema_users})
TTT;
				break;
			default:
				Throw new Exception('type?');
		}
		$db_object = new \Db($db_link);
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
	 * Render SQL
	 *
	 * @param string $type
	 * @param array $data
	 * @param array $options
	 *		string mode
	 * @param mixed $extra_comments
	 * @return string
	 * @throws Exception
	 */
	public function renderSql($type, $data, $options = [], & $extra_comments = null) {
		$result = '';
		switch ($type) {
			// extension
			case 'extension_new':
				$result = "CREATE EXTENSION {$data['name']}";
				if (!empty($data['schema'])) {
					$result.= " SCHEMA {$data['schema']};";
				} else {
					$result.= ';';
				}
				break;
			case 'extension_delete':
				$result = "DROP EXTENSION {$data['name']};";
				break;
			// schema
			case 'schema_new':
				$result = [];
				$password = 'na' . substr(md5(rand(10000, 99999)), 0, 8);
				$result[]= "ALTER SESSION SET \"_ORACLE_SCRIPT\" = true";
				$result[]= <<<TTT
					DECLARE
						e NUMBER;
					BEGIN
						SELECT 1 INTO e FROM dba_roles WHERE LOWER(role) = 'numbers_applications';
						EXCEPTION
							WHEN no_data_found THEN
								EXECUTE IMMEDIATE 'CREATE ROLE numbers_applications IDENTIFIED BY {$password}';
					END;
TTT;
				$result[]= "CREATE USER {$data['data']['name']} IDENTIFIED BY \"{$password}\"";
				$result[]= "GRANT UNLIMITED TABLESPACE TO {$data['data']['name']}";
				$result[]= "GRANT numbers_applications TO {$data['data']['name']}";
				$result[]= "GRANT EXECUTE ON CTX_DDL TO {$data['data']['name']}";
				$result[]= "GRANT CREATE JOB TO {$data['data']['name']}";
				$result[]= "GRANT CREATE TABLE TO {$data['data']['name']}";
				break;
			case 'schema_delete':
				$result = [];
				$result[]= "ALTER SESSION SET \"_ORACLE_SCRIPT\"=true";
				$result[]= "DROP USER {$data['data']['name']}";
				$result[]= "DROP ROLE numbers_applications";
				break;
			// columns
			case 'column_delete':
				$result = "ALTER TABLE {$data['table']} DROP COLUMN {$data['name']};";
				break;
			case 'column_new':
				$default = $data['data']['default'] ?? null;
				if (is_string($default) && $default != 'now()') {
					$default = "'" . $default . "'";
				} else if (is_string($default) && $default == 'now()') {
					$default = 'LOCALTIMESTAMP';
				}
				$null = $data['data']['null'] ?? false;
				// handling sequence & json
				$sequence_comment = "";
				if (!empty($data['data']['sequence'])) { // sequence
					$sequence_comment = "COMMENT ON COLUMN {$data['table']}.{$data['name']} IS 'sequence'";
				} else if ($data['data']['type'] == 'json') { // json
					$sequence_comment = "COMMENT ON COLUMN {$data['table']}.{$data['name']} IS 'json'";
				} else if ($data['data']['type'] == 'bcnumeric') {
					$sequence_comment = "COMMENT ON COLUMN {$data['table']}.{$data['name']} IS 'bcnumeric'";
				} else if ($data['data']['type'] == 'numeric' && $data['data']['sql_type'] == 'number') {
					$sequence_comment = "COMMENT ON COLUMN {$data['table']}.{$data['name']} IS 'numeric'";
				}
				if (empty($options['column_new_no_alter'])) {
					$result = [];
					$result[]= "ALTER TABLE {$data['table']} ADD COLUMN {$data['name']} {$data['data']['sql_type']}" . ($default !== null ? (' DEFAULT ' . $default) : '') . (!$null ? (' NOT NULL') : '') . ";";
					$result[]= $sequence_comment;
				} else {
					$result = "{$data['name']} {$data['data']['sql_type']}" . ($default !== null ? (' DEFAULT ' . $default) : '') . (!$null ? (' NOT NULL') : '');
					if (is_array($extra_comments)) {
						$extra_comments[] = $sequence_comment;
					}
				}
				break;
			case 'column_change':
				$master = $data['data'];
				$slave = $data['data_old'];
				$temp_default = '';
				if ($master['sql_type'] !== $slave['sql_type']) {
					$temp_default.= $master['sql_type'];
				}
				if ($master['default'] !== $slave['default']) {
					if (is_string($master['default'])) {
						$temp_default.= " DEFAULT '" . $master['default'] . "'";
					} else if (is_null($master['default'])) {
						$temp_default.= ' DEFAULT NULL';
					} else {
						$temp_default.= ' DEFAULT ' . $master['default'];
					}
				}
				if ($master['null'] !== $slave['null']) {
					if (!empty($master['null'])) {
						$temp_default.= " NULL";
					} else {
						$temp_default.= " NOT NULL";
					}
				}
				if (!empty($temp_default)) {
					$result = "ALTER TABLE {$data['schema']}.{$data['table']} MODIFY {$data['name']} {$temp_default}";
				}
				break;
			// table
			case 'table_new':
				$columns = [];
				$comments = [];
				foreach ($data['data']['columns'] as $k => $v) {
					$columns[] = $this->renderSql('column_new', ['table' => $data['data']['full_table_name'], 'name' => $k, 'data' => $v], ['column_new_no_alter' => true], $comments);
				}
				$result = [];
				$result[]= "CREATE TABLE {$data['data']['full_table_name']} (\n\t" . implode(",\n\t", $columns) . "\n)";
				if (!empty($comments)) {
					$result = array_merge($result, $comments);
				}
				break;
			case 'table_delete':
				$result = "DROP TABLE {$data['data']['full_table_name']} CASCADE CONSTRAINTS";
				break;
			// view
			case 'view_new':
				$result = "CREATE OR REPLACE VIEW {$data['schema']}.{$data['name']} AS {$data['data']['definition']}";
				break;
			case 'view_delete':
				$result = "DROP VIEW {$data['schema']}.{$data['name']}";
				break;
			// foreign key/unique/primary key
			case 'constraint_new':
				switch ($data['data']['type']) {
					case 'pk':
						$result = "ALTER TABLE {$data['data']['full_table_name']} ADD CONSTRAINT {$data['name']} PRIMARY KEY (" . implode(", ", $data['data']['columns']) . ")";
						break;
					case 'unique':
						//$result = "CREATE UNIQUE INDEX {$data['schema']}.{$data['name']} ON {$data['data']['full_table_name']} (" . implode(", ", $data['data']['columns']) . ") INVISIBLE";
						$result = "ALTER TABLE {$data['data']['full_table_name']} ADD CONSTRAINT {$data['name']} UNIQUE (" . implode(", ", $data['data']['columns']) . ")";
						break;
					case 'fk':
						if (strtoupper($data['data']['options']['delete']) == 'RESTRICT' || strtoupper($data['data']['options']['delete']) == 'NO ACTION') {
							$temp_on_delete = '';
						} else {
							$temp_on_delete = ' ON DELETE ' . strtoupper($data['data']['options']['delete']);
						}
						$result = "ALTER TABLE {$data['data']['full_table_name']} ADD CONSTRAINT {$data['name']} FOREIGN KEY (" . implode(", ", $data['data']['columns']) . ") REFERENCES {$data['data']['foreign_table']} (" . implode(", ", $data['data']['foreign_columns']) . ")" . $temp_on_delete;
						break;
					default:
						Throw new Exception($data['data']['type'] . '?');
				}
				break;
			case 'constraint_delete':
				$result = "ALTER TABLE {$data['data']['full_table_name']} DROP CONSTRAINT {$data['name']}";
				break;
			// indexes
			case 'index_new':
				// fulltext indexes as gin
				if ($data['data']['type'] == 'fulltext') {
					$result = [];
					$temp_columns = implode(',', $data['data']['columns']);
					$temp_name = strtoupper($data['schema'] . '.na_index_' . substr(md5("{$data['schema']}_{$data['name']}_columns"), 0, 21));
					$result[] = <<<TTT
						DECLARE
							preference_count number := 0;
						BEGIN
							SELECT COUNT(*) INTO preference_count FROM ctxsys.ctx_preferences a WHERE LOWER(a.pre_owner || '.' || a.pre_name) = LOWER('{$temp_name}');
							IF preference_count > 0 THEN
								ctx_ddl.drop_preference('{$temp_name}');
							END IF;
							ctx_ddl.create_preference('{$temp_name}', 'MULTI_COLUMN_DATASTORE');
							ctx_ddl.set_attribute('{$temp_name}', 'COLUMNS', '{$temp_columns}');
						END;
TTT;
					$one_column = current($data['data']['columns']);
					$result[] = "ALTER SESSION ENABLE PARALLEL DML";
					$result[] = "ALTER SESSION FORCE PARALLEL QUERY";
					$result[] = "CREATE INDEX {$data['schema']}.{$data['name']} ON {$data['data']['full_table_name']} ({$one_column}) INDEXTYPE IS ctxsys.context Parameters('DataStore {$temp_name}') PARALLEL 32";
					$result[] = "ALTER INDEX {$data['schema']}.{$data['name']} NOPARALLEL";
				} else {
					$result = "CREATE INDEX {$data['schema']}.{$data['name']} ON {$data['data']['full_table_name']} (" . implode(", ", $data['data']['columns']) . ")";
				}
				break;
			case 'index_delete':
				$result = [];
				$result[]= "DROP INDEX {$data['schema']}.{$data['name']}";
				if ($data['data']['type'] == 'fulltext') {
					$temp_name = strtoupper($data['schema'] . '.na_index_' . substr(md5("{$data['schema']}_{$data['name']}_columns"), 0, 21));
					$result[] = <<<TTT
						DECLARE
							preference_count number := 0;
						BEGIN
							SELECT COUNT(*) INTO preference_count FROM ctxsys.ctx_preferences a WHERE LOWER(a.pre_owner || '.' || a.pre_name) = LOWER('{$temp_name}');
							IF preference_count > 0 THEN
								ctx_ddl.drop_preference('{$temp_name}');
							END IF;
						END;
TTT;
				}
				break;
			// sequences
			case 'sequence_new':
				$result = [];
				$result[]= "CREATE SEQUENCE {$data['data']['full_sequence_name']} START WITH 1";
				// insert entry into sequences table
				$model = new \Numbers\Backend\Db\Common\Model\Sequences();
				$result[]= <<<TTT
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
					)
TTT;
				break;
			case 'sequence_delete':
				$result = [];
				$result[]= "DROP SEQUENCE {$data['data']['full_sequence_name']}";
				if (($options['mode'] ?? '') != 'drop') {
					$model = new \Numbers\Backend\Db\Common\Model\Sequences();
					$result[]= "DELETE FROM {$model->full_table_name} WHERE sm_sequence_name = '{$data['data']['full_sequence_name']}'";
				}
				break;
			// functions
			case 'function_new':
				$result = $data['data']['definition'];
				break;
			case 'function_delete':
				$result = "DROP FUNCTION {$data['schema']}.{$data['name']}";
				break;
			// trigger
			case 'trigger_new':
				$result = $data['data']['definition'];
				break;
			case 'trigger_delete':
				$result = "DROP TRIGGER {$data['data']['full_function_name']}";
				break;
			// permissions
			case 'permission_grant_schema':
				$result = [];
				$result[]= <<<TTT
					DECLARE
						user_exists integer;
					BEGIN
						SELECT COUNT(*) INTO user_exists FROM dba_users WHERE LOWER(username) = LOWER('{$data['owner']}');
						IF (user_exists = 0) THEN
							EXECUTE IMMEDIATE 'CREATE USER {$data['owner']} IDENTIFIED BY "{$data['password']}"';
						END IF;
					END;
TTT;
				$result[]= "GRANT RESOURCE, CONNECT TO {$data['owner']}";
				break;
			case 'permission_grant_table':
				$result = "GRANT SELECT, INSERT, UPDATE, DELETE ON {$data['table']} TO {$data['owner']}";
				break;
			case 'permission_grant_view':
				$result = [];
				$result[]= "GRANT SELECT ON {$data['view']} TO {$data['owner']}";
				// we must grant access to all tables used in view
				foreach ($data['grant_tables'] as $v) {
					$result[]= "GRANT SELECT ON {$v} TO {$data['owner']}";
				}
				break;
			case 'permission_grant_sequence':
				$result = "GRANT SELECT ON {$data['sequence']} TO {$data['owner']}";
				break;
			case 'permission_grant_function':
				$result = "GRANT EXECUTE ON {$data['function']} TO {$data['owner']}";
				break;
			default:
				// nothing
		}
		return $result;
	}
}