<?php

class numbers_backend_db_pgsql_ddl extends numbers_backend_db_class_ddl implements numbers_backend_db_interface_ddl {

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
		$column['type'] = $column['type'] ?? 'unsupported';
		$column['null'] = $column['null'] ?? false;
		$column['default'] = $column['default'] ?? null;
		$column['length'] = $column['length'] ?? 0;
		$column['precision'] = $column['precision'] ?? 0;
		$column['scale'] = $column['scale'] ?? 0;

		// simple switch would do the work
		switch ($column['type']) {
			case 'boolean':
				$result['column'] = ['type' => 'smallint', 'null' => false, 'default' => 0];
				break;
			case 'smallint':
			case 'integer':
			case 'bigint':
				$result['column'] = ['type' => $column['type'], 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'numeric':
				if ($column['precision'] > 0) {
					$result['column'] = ['type' => 'numeric(' . $column['precision'] . ', ' . $column['scale'] . ')', 'null' => $column['null'], 'default' => $column['default']];
				} else {
					$result['column'] = ['type' => $column['type'], 'null' => $column['null'], 'default' => $column['default']];
				}
				break;
			case 'smallserial':
			case 'serial':
			case 'bigserial':
				$result['column'] = ['type' => $column['type']];
				break;
			case 'char':
				$temp = 'character(' . $column['length'] . ')';
				$result['column'] = ['type' => $temp, 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'varchar':
				$temp = 'character varying(' . $column['length'] . ')';
				$result['column'] = ['type' => $temp, 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'json':
				$result['column'] = ['type' => 'jsonb', 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'date':
				$result['column'] = ['type' => $column['type'], 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'time':
				$result['column'] = ['type' => 'time without time zone', 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'datetime':
				$result['column'] = ['type' => 'timestamp(0) without time zone', 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'timestamp':
				$result['column'] = ['type' => 'timestamp(6) without time zone', 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'text':
				$result['column'] = ['type' => 'text', 'null' => $column['null'], 'default' => $column['default']];
				break;
			case 'unsupported':
				Throw new Exception($table_object->name . ': unsupported type for column: ' . $column['name']);
				break;
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
		foreach (array('extensions', 'schemas', 'columns', 'constraints', 'sequences', 'functions') as $v) { //'views', 'domains', 'triggers'
			$temp = $this->load_schema_details($v, $db_link);
			if (!$temp['success']) {
				$result['error'] = array_merge($result['error'], $temp['error']);
			} else {
				switch ($v) {
					case 'columns':
						// small conversion for columns
						foreach ($temp['data'] as $k2 => $v2) {
							if ($k2 == 'public') {
								$k2 = '';
							}
							foreach ($v2 as $k3 => $v3) {
								foreach ($v3 as $k4 => $v4) {
									// processing type
									$type = $v4['type'];
									if ($v4['length'] > 0) {
										$type.= '(' . $v4['length'] . ')';
									} else if ($type == 'numeric' && $v4['precision'] > 0) {
										$type.= '(' . $v4['precision'] . ', ' . $v4['scale'] . ')';
									} else if (strpos($type, 'timestamp') === 0) {
										$type = str_replace('timestamp', 'timestamp(' . ($v4['precision'] ?? 0) . ')', $type);
									}
									// processing default
									$default = $v4['default'];
									if ($default !== null) {
										if ($default == 'NULL') {
											$default = null;
										} else if (is_string($default)) {
											if (strpos($default, 'nextval') === 0 && in_array($type, ['smallint', 'integer', 'bigint'])) {
												if ($type == 'smallint') {
													$type = 'smallserial';
												} else if ($type == 'integer') {
													$type = 'serial';
												} else if ($type == 'bigint') {
													$type = 'bigserial';
												}
												$default = null;
											} else if (strpos($default, '::') !== false) {
												$temp3 = explode('::', $default);
												$default = $temp3[0];
											}
											if ($default[0] == "'") {
												$default = trim($default, "'");
											} else if (is_numeric($default)) {
												$default = $default * 1;
											}
										}
									}
									$temp2 = [
										'type' => $type,
										'null' => ($v4['null'] ? true : false),
										'default' => $default
									];
									// putting column back into array
									$result['data']['table'][$k2][$k3]['columns'][$k4] = $temp2;
									if (!isset($result['data']['table'][$k2][$k3]['owner'])) {
										$result['data']['table'][$k2][$k3]['owner'] = $v4['table_owner'];
									}
									if (!isset($result['data']['table'][$k2][$k3]['full_table_name'])) {
										if ($v4['schema_name'] == 'public') {
											$result['data']['table'][$k2][$k3]['full_table_name'] = $v4['table_name'];
										} else {
											$result['data']['table'][$k2][$k3]['full_table_name'] = $v4['schema_name'] . '.' . $v4['table_name'];
										}
									}
								}
							}
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
										if ($k3 == '') {
											$name = $v5['table_name'];
										} else {
											$name = $v5['schema_name'] . '.' . $v5['table_name'];
										}
										if ($v5['constraint_type'] == 'PRIMARY KEY') {
											$temp2 = [
												'type' => 'pk',
												'columns' => $v5['column_names'],
												'full_table_name' => $name
											];
											$result['data']['constraint'][$k3][$k4][$k5] = $temp2;
										} else if ($v5['constraint_type'] == 'UNIQUE') {
											$temp2 = [
												'type' => 'unique',
												'columns' => $v5['column_names'],
												'full_table_name' => $name
											];
											$result['data']['constraint'][$k3][$k4][$k5] = $temp2;
										} else if ($v5['constraint_type'] == 'INDEX') {
											$temp2 = [
												'type' => $v5['index_type'],
												'columns' => $v5['column_names'],
												'full_table_name' => $name
											];
											$result['data']['index'][$k3][$k4][$k5] = $temp2;
										} else if ($v5['constraint_type'] == 'FOREIGN_KEY') {
											$name2 = ($k3 == '') ? $v5['foreign_table_name'] : ($v5['foreign_schema_name'] . '.' . $v5['foreign_table_name']);
											$temp2 = [
												'type' => 'fk',
												'columns' => $v5['column_names'],
												'foreign_table' => $name2,
												'foreign_columns' => $v5['foreign_column_names'],
												'options' => [
													'match' => $v5['match_option'],
													'update' => $v5['update_rule'],
													'delete' => $v5['delete_rule']
												],
												'name' => $v5['constraint_name'],
												'full_table_name' => $name
											];
											$result['data']['constraint'][$k3][$k4][$k5] = $temp2;
										} else {
											print_r($v5);
											exit;
										}
									}
								}
							}
						}
						break;
					case 'extensions':
						$result['data']['extension'] = $temp['data'];
						break;
					case 'schemas':
						$result['data']['schema'] = $temp['data'];
						break;
					case 'sequences':
						foreach ($temp['data'] as $k2 => $v2) {
							if ($k2 == 'public') {
								$k2 = '';
							}
							foreach ($v2 as $k3 => $v3) {
								$result['data']['sequence'][$k2][$k3] = [
									'owner' => $v3['sequence_owner'],
									'full_sequence_name' => $k2 . '.' . $v3['sequence_name'],
									'type' => $v3['type'],
									'prefix' => $v3['prefix'],
									'length' => $v3['length'],
									'suffix' => $v3['suffix']
								];
							}
						}
						break;
					case 'functions':
						foreach ($temp['data'] as $k2 => $v2) {
							if ($k2 == 'public') {
								$k2 = '';
							}
							foreach ($v2 as $k3 => $v3) {
								//$k3 = str_replace('public.', '', $k3);
								$name = ($k2 == '') ? $k3 : ($k2 . '.' . $k3);
								$result['data']['function'][$k2][$k3] = [
									'owner' => $v3['function_owner'],
									'full_function_name' => $name,
									'sql_full' => $v3['routine_definition'],
									'sql_parts' => [
										'definition' => $v3['full_function_name'],
										'header' => null,
										'body' => $v3['routine_definition'],
										'footer' => null
									]
								];
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

		// getting proper query
		switch($type) {
			case 'schemas':
				$key = array('name');
				$sql = <<<TTT
					SELECT 
							schema_name AS name,
							schema_owner AS owner
					FROM information_schema.schemata
					WHERE schema_name !~ 'pg_' AND schema_name != 'information_schema' AND schema_name != 'public'
					ORDER BY name
TTT;
				break;
/*
			case 'tables':
				$key = array('schema_name', 'table_name');
				$sql = <<<TTT
					SELECT
							schemaname schema_name,
							tablename table_name,
							tableowner table_owner
					FROM pg_tables a
					WHERE 1=1
							AND schemaname NOT IN ('pg_catalog', 'information_schema')
					ORDER BY schema_name, table_name
TTT;
				break;
*/
			case 'constraints':
				$key = array('constraint_type', 'schema_name', 'table_name', 'constraint_name');
				$sql = <<<TTT
					SELECT
							*
					FROM (
							-- indexes
							SELECT
									'INDEX' constraint_type,
									n.nspname schema_name,
									t.relname table_name,
									i.relname constraint_name,
									max(f.amname) index_type,
									array_agg(a.attname) column_names,
									'' foreign_schema_name,
									'' foreign_table_name,
									'{}'::text[] foreign_column_names,
									null match_option,
									null update_rule,
									null delete_rule
							FROM pg_class t, pg_class i, pg_index ix, pg_attribute a, pg_namespace n, pg_am f
							WHERE 1=1
								AND t.oid = ix.indrelid
								AND i.oid = ix.indexrelid
								AND a.attrelid = t.oid
								AND a.attnum = ANY(ix.indkey)
								AND t.relkind = 'r'
								AND n.oid = t.relnamespace
								AND n.nspname NOT IN ('pg_catalog', 'information_schema')
								AND ix.indisprimary != 't'
								AND ix.indisunique != 't'
								AND f.oid = i.relam
							GROUP BY n.nspname, t.relname, i.relname

							UNION ALL

							-- unique and primary key
							SELECT
									min(tc.constraint_type) constraint_type,
									tc.table_schema schema_name,
									tc.table_name table_name,
									tc.constraint_name constraint_name,
									null index_type,
									array_agg(kc.column_name::text) column_names,
									'' foreign_schema_name,
									'' foreign_table_name,
									'{}'::text[] foreign_column_names,
									null match_option,
									null update_rule,
									null delete_rule
							FROM information_schema.table_constraints tc, information_schema.key_column_usage kc  
							WHERE 1=1
									AND kc.table_name = tc.table_name
									AND kc.table_schema = tc.table_schema
									AND kc.constraint_name = tc.constraint_name
									AND tc.constraint_type IN ('PRIMARY KEY', 'UNIQUE')
							GROUP BY tc.table_schema, tc.table_name, tc.constraint_name

							UNION ALL

							-- foreign key
							SELECT
									'FOREIGN_KEY' constraint_type,
									x.table_schema schema_name,
									x.table_name table_name,
									c.constraint_name constraint_name,
									null index_type,
									array_agg(x.column_name::text) column_names,
									y.table_schema foreign_schema_name,
									y.table_name foreign_table_name,
									array_agg(y.column_name::text) foreign_column_name,
									min(match_option::text) match_option,
									min(update_rule::text) update_rule,
									min(delete_rule::text) delete_rule
							FROM information_schema.referential_constraints c
							JOIN (
								SELECT * FROM information_schema.key_column_usage ORDER BY position_in_unique_constraint
							) x ON x.constraint_name = c.constraint_name
							JOIN information_schema.key_column_usage y ON y.ordinal_position = x.position_in_unique_constraint AND y.constraint_name = c.unique_constraint_name
							GROUP BY x.table_schema, x.table_name, c.constraint_name, y.table_schema, y.table_name

							UNION ALL

							SELECT
								'CHECK' constraint_type,
								n.nspname schema_name,
								r.relname table_name,
								c.conname constraint_name,
								'' index_type,
								'{}'::text[] column_names,
								'' foreign_schema_name,
								'' foreign_table_name,
								'{}'::text[] foreign_column_names,
								c.consrc match_option,
								null update_rule,
								null delete_rule
							FROM pg_class r, pg_constraint c, pg_namespace n, pg_class i
							WHERE r.oid = c.conrelid
								AND c.contype = 'c'
								AND n.oid = r.relnamespace
					) a
TTT;
				break;
			 case 'columns':
				$key = array('schema_name', 'table_name', 'column_name');
				$sql = <<<TTT
					SELECT 
							b.table_schema schema_name,
							b.table_name table_name,
							c.tableowner table_owner,
							a.column_name column_name,
							a.data_type "type",
							CASE WHEN a.is_nullable = 'NO' THEN 0 ELSE 1 END "null",
							a.column_default "default",
							a.character_maximum_length "length",
							coalesce(a.numeric_precision, a.datetime_precision) "precision",
							a.numeric_scale "scale"
					FROM information_schema.columns a
					LEFT JOIN information_schema.tables b ON a.table_schema = b.table_schema AND a.table_name = b.table_name
					LEFT JOIN pg_tables c ON a.table_schema = c.schemaname AND a.table_name = c.tablename
					WHERE 1=1
							AND b.table_schema NOT IN ('pg_catalog', 'information_schema')
							AND b.table_type = 'BASE TABLE'
					ORDER BY b.table_schema, b.table_name, a.ordinal_position
TTT;
				break;
/*
			case 'views':
				$key = array('schema_name', 'view_name');
				$sql = <<<TTT
					SELECT
							schemaname schema_name,
							viewname view_name,
							viewowner view_owner,
							definition view_definition
					FROM pg_views 
					WHERE 1=1
							AND schemaname NOT IN('information_schema', 'pg_catalog')
TTT;
				break;
*/
/*
			case 'domains':
				$key = array('schema_name', 'domain_name');
				$sql = <<<TTT
					SELECT 
							a.domain_schema schema_name,
							a.domain_name domain_name,
							a.data_type data_type,
							CASE WHEN b.typnotnull = 't' THEN 'NOT NULL' ELSE '' END is_nullable,
							a.domain_default domain_default,
							a.character_maximum_length character_maximum_length,
							a.numeric_precision numeric_precision,
							a.numeric_scale numeric_scale,
							a.udt_name data_type_udt,
							c.constraint_name constraint_name,
							c.constraint_definition constraint_definition,
							b.rolname domain_owner
					FROM information_schema.domains a
					LEFT JOIN (
							SELECT 
									n.nspname schema_name,
									pg_catalog.format_type(t.oid, NULL) type_name,
									t.typnotnull,
									x.rolname,
									t.typowner
							FROM pg_catalog.pg_type t
							LEFT JOIN pg_catalog.pg_namespace n ON n.oid = t.typnamespace
							LEFT JOIN pg_catalog.pg_authid x ON x.oid = t.typowner
					) b ON a.domain_schema = b.schema_name AND b.type_name = (case when b.schema_name='public' then a.domain_name ELSE b.schema_name || '.' || a.domain_name END)
					LEFT JOIN (
							SELECT 
									s.nspname as schema_name, 
									pg_type.typname as domain_name,
									array_agg(c.conname) constraint_name,
									array_agg(pg_get_constraintdef(c.oid)) AS constraint_definition
							FROM (SELECT oid,* FROM pg_constraint WHERE contypid>0) as c
							LEFT JOIN pg_type ON pg_type.oid = c.contypid
							JOIN pg_namespace s ON s.oid = c.connamespace
							WHERE s.nspname NOT IN ('information_schema', 'pg_catalog')
							GROUP BY s.nspname, pg_type.typname
					) c ON a.domain_schema = c.schema_name AND c.domain_name = a.domain_name
					WHERE domain_schema NOT IN ('information_schema', 'pg_catalog')
TTT;
				break;
*/
			case 'sequences':
				$key = array('schema_name', 'sequence_name');
				$sql = <<<TTT
					SELECT
							s.nspname schema_name,
							c.relname sequence_name,
							c.rolname sequence_owner,
							null "type",
							null prefix,
							0 length,
							null suffix
					FROM (
							SELECT 
									a.oid,
									a.relnamespace, 
									a.relname,
									r.rolname
							FROM pg_class a
							INNER JOIN pg_catalog.pg_roles r ON r.oid = a.relowner
							WHERE a.relkind = 'S'
					) c
					LEFT JOIN pg_namespace s ON s.oid = c.relnamespace
					WHERE 1=1
						AND s.nspname || '.' || c.relname NOT IN (
							SELECT
								n.nspname || '.' || s.relname sequence_name
							FROM pg_class s
							INNER JOIN pg_depend d ON d.objid = s.oid
							INNER JOIN pg_class t ON d.objid = s.oid AND d.refobjid = t.oid
							INNER JOIN pg_attribute a ON (d.refobjid, d.refobjsubid) = (a.attrelid, a.attnum)
							INNER JOIN pg_namespace n ON n.oid = s.relnamespace
							WHERE s.relkind = 'S'
						)
TTT;
				break;
			case 'functions':
				$key = array('schema_name', 'function_name');
				$sql = <<<TTT
					SELECT
							n.nspname schema_name,
							p.proname function_name,
							p.proname || '(' || pg_get_function_identity_arguments(p.oid) || ')' full_function_name,
							r.rolname function_owner,
							pg_catalog.pg_get_functiondef(p.oid) routine_definition
					FROM pg_catalog.pg_proc p
					INNER JOIN pg_catalog.pg_roles r ON r.oid = p.proowner
					LEFT JOIN pg_catalog.pg_namespace n ON p.pronamespace = n.oid
					WHERE 1=1
							AND n.nspname NOT IN ('pg_catalog', 'information_schema')
							AND p.proisagg = 'f'
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

/*
			case 'triggers':
				$key = array('schema_name', 'table_name', 'trigger_name');
				$sql = <<<TTT
					SELECT
							n.nspname schema_name,
							b.relname table_name,
							a.tgname trigger_name,
							pg_get_triggerdef(a.oid) trigger_definition
					FROM pg_trigger a
					LEFT JOIN pg_class b ON a.tgrelid = b.oid
					LEFT JOIN pg_namespace n ON n.oid = b.relnamespace
					WHERE 1=1
							AND tgisinternal = 'f'
TTT;
				break;
*/
			default:
				Throw new Exception('type?');
		}
		$db_object = new db($db_link);
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
			// extension
			case 'extension':
				$result = "CREATE EXTENSION {$data['data']['name']} SCHEMA {$data['data']['schema']};";
				break;
			case 'extension_delete':
				$result = "DROP EXTENSION {$data['data']['extension_name']};";
				break;
			// schema
			case 'schema':
				$result = "CREATE SCHEMA {$data['data']['name']} AUTHORIZATION {$data['data']['owner']};";
				break;
			case 'schema_owner':
				$result = "ALTER SCHEMA {$data['data']['name']} OWNER TO {$data['data']['owner']};";
				break;
			case 'schema_delete':
				$result = "DROP SCHEMA {$data['name']};";
				break;
			// columns
			case 'column_delete':
				$result = "ALTER TABLE {$data['table']} DROP COLUMN {$data['name']};";
				break;
			case 'column_new':
				$type = $data['data']['type'];
				$default = $data['data']['default'] ?? null;
				if (is_string($default) && $default != 'now()') {
					$default = "'" . $default . "'";
				}
				$null = $data['data']['null'] ?? false;
				if (empty($options['column_new_no_alter'])) {
					$result = "ALTER TABLE {$data['table']} ADD COLUMN {$data['name']} {$type}" . ($default !== null ? (' DEFAULT ' . $default) : '') . (!$null ? (' NOT NULL') : '') . ";";
				} else {
					$result = "{$data['name']} {$type}" . ($default !== null ? (' DEFAULT ' . $default) : '') . (!$null ? (' NOT NULL') : '');
				}
				break;
			case 'column_change':
				$result = '';
				$master = $data['data'];
				$slave = $data['data_slave'];
				if ($master['type'] != $slave['type']) {
					$result.= "ALTER TABLE {$data['table']} ALTER COLUMN {$data['name']} SET DATA TYPE {$master['type']};\n";
				}
				if ($master['default'] !== $slave['default']) {
					if (is_string($master['default'])) {
						$master['default'] = "'" . $master['default'] . "'";
					}
					$temp = !isset($master['default']) ? ' DROP DEFAULT' : ('SET DEFAULT ' . $master['default']);
					$result.= "ALTER TABLE {$data['table']} ALTER COLUMN {$data['name']} $temp;\n";
				}
				if ($master['null'] != $slave['null']) {
					$temp = !empty($master['null']) ? 'DROP'  : 'SET';
					$result.= "ALTER TABLE {$data['table']} ALTER COLUMN {$data['name']} $temp NOT NULL;\n";
				}
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
				$result2 = "CREATE TABLE {$data['data']['full_table_name']} (\n\t";
					$result2.= implode(",\n\t", $columns);
				$result2.= "\n);";
				$result = [$result2];
				$result[]= "ALTER TABLE {$data['data']['full_table_name']} OWNER TO {$data['data']['owner']};";
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
			case 'view_owner':
				$result = "ALTER TABLE {$data['name']} OWNER TO {$data['owner']};";
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
						$result = "ALTER TABLE {$data['data']['full_table_name']} ADD CONSTRAINT {$data['name']} FOREIGN KEY (" . implode(", ", $data['data']['columns']) . ") REFERENCES {$data['data']['foreign_table']} (" . implode(", ", $data['data']['foreign_columns']) . ") MATCH " . strtoupper($data['data']['options']['match'] ?? 'SIMPLE') . " ON UPDATE " . strtoupper($data['data']['options']['update'] ?? 'NO ACTION') . " ON DELETE " . strtoupper($data['data']['options']['delete'] ?? 'NO ACTION') . ";";
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
				$result = "ALTER TABLE {$data['data']['full_table_name']} DROP CONSTRAINT {$data['name']};";
				break;
			// indexes
			case 'index_new':
				$result = "CREATE INDEX {$data['name']} ON {$data['data']['full_table_name']} USING {$data['data']['type']} (" . implode(", ", $data['data']['columns']) . ");";
				break;
			case 'index_delete':
				$temp = explode('.', $data['table']);
				$result = "DROP INDEX {$temp[0]}.{$data['name']};";
				break;
			// domains
			case 'domain_new':
				$result = "CREATE DOMAIN {$data['name']} AS {$data['definition']['data_type']}" . ($data['definition']['domain_default']!==null ? (' DEFAULT ' . $data['definition']['domain_default']) : '') . (!empty($data['definition']['is_nullable']) ? (' ' . $data['definition']['is_nullable']) : '') . ";\n";
				// adding constraints
				if (!empty($data['definition']['constraint_name'])) {
					foreach ($data['definition']['constraint_name'] as $k=>$v) {
						$result.= "ALTER DOMAIN {$data['name']} ADD CONSTRAINT {$v} {$data['definition']['constraint_definition'][$k]};\n"; 
					}
				}
				// adding owner name
				$result.= "ALTER DOMAIN {$data['name']} OWNER TO {$data['owner']};";
				break;
			case 'domain_delete':
				$result = "DROP DOMAIN {$data['name']};";
				break;
			case 'domain_owner':
				$result.= "ALTER DOMAIN {$data['name']} OWNER TO {$data['owner']};";
				break;
			// sequences
			case 'sequences_new':
				$result = [];
				$result[]= "CREATE SEQUENCE {$data['name']} START 1;";
				$result[]= "ALTER SEQUENCE {$data['name']} OWNER TO {$data['owner']};";
				$result[]= <<<TTT
					INSERT INTO sm_sequences (
						sm_sequence_name,
						sm_sequence_description,
						sm_sequence_prefix,
						sm_sequence_length,
						sm_sequence_suffix,
						sm_sequence_count,
						sm_sequence_type
					) VALUES (
						'{$data['name']}',
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
				$result[]= "DROP SEQUENCE {$data['name']};";
				$result[]= "DELETE FROM sm_sequences WHERE sm_sequence_name = '{$data['name']}'";
				break;
			case 'sequence_owner':
				$result = "ALTER SEQUENCE {$data['name']} OWNER TO {$data['owner']};";
				break;
			// functions
			case 'function_new':
				$result = [];
				$result[]= $data['data']['sql_full'] . ";";
				$result[]= "ALTER FUNCTION {$data['data']['sql_parts']['definition']} OWNER TO {$data['owner']};";
				break;
			case 'function_delete':
				$result = "DROP FUNCTION {$data['data']['sql_parts']['definition']};";
				break;
			case 'function_owner':
				$result = "ALTER FUNCTION {$data['data']['sql_parts']['definition']} OWNER TO {$data['owner']};";
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
			default:
				// nothing
				Throw new Exception($type . '?');
		}
		return $result;
	}
}