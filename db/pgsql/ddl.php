<?php

class numbers_backend_db_pgsql_ddl extends numbers_backend_db_class_ddl implements numbers_backend_db_interface_ddl {

	/**
	 * Column SQL type
	 *
	 * @param array $column
	 * @return array
	 */
	public function column_sql_type($column) {
		// presetting
		$column = $this->column_sql_type_base($column);
		// simple switch would do the work
		switch ($column['type']) {
			case 'boolean':
				$column['sql_type'] = 'smallint';
				break;
			case 'bcnumeric':
			case 'numeric':
				if ($column['precision'] > 0) {
					$column['sql_type'] = 'numeric(' . $column['precision'] . ', ' . $column['scale'] . ')';
				} else {
					$column['sql_type'] = 'numeric';
				}
				break;
			case 'char':
				$column['sql_type'] = 'character(' . $column['length'] . ')';
				break;
			case 'varchar':
				$column['sql_type'] = 'character varying(' . $column['length'] . ')';
				break;
			case 'json':
				$column['sql_type'] = 'jsonb';
				break;
			case 'time':
				$column['sql_type'] = 'time without time zone';
				break;
			case 'datetime':
				$column['sql_type'] = 'timestamp(0) without time zone';
				break;
			case 'timestamp':
				$column['sql_type'] = 'timestamp(6) without time zone';
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
	public function load_schema($db_link) {
		$result = [
			'success' => false,
			'error' => [],
			'data' => [],
			'count' => []
		];
		// getting information
		foreach (array('extensions', 'schemas', 'columns', 'constraints', 'sequences', 'functions') as $v) { //'views', 'domains', 'triggers'
			$temp = $this->load_schema_details($v, $db_link);
			if (!$temp['success']) {
				$result['error'] = array_merge($result['error'], $temp['error']);
			} else {
				switch ($v) {
					case 'columns':
						$tables = [];
						// small conversion for columns
						foreach ($temp['data'] as $k2 => $v2) {
							if ($k2 == 'public') {
								$k2 = '';
							}
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
									$type = $v4['type'];
									$sequence = false;
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
												$sequence = true;
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
									// putting column back into array
									$tables[$full_table_name]['data']['columns'][$k4] = [
										'type' => $type,
										'null' => !empty($v4['null']) ? true : false,
										'default' => $default,
										'length' => $v4['length'],
										'precision' => $v4['precision'],
										'scale' => $v4['scale'],
										'sequence' => $sequence,
										'sql_type' => $type
									];
								}
							}
						}
						// add tables
						foreach ($tables as $v2) {
							$this->object_add($v2, $db_link);
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
										if ($k3 == '') {
											$full_table_name = $v5['table_name'];
										} else {
											$full_table_name = $v5['schema_name'] . '.' . $v5['table_name'];
										}
										if ($v5['constraint_type'] == 'PRIMARY KEY') {
											$temp2 = [
												'type' => 'pk',
												'columns' => $v5['column_names'],
												'full_table_name' => $full_table_name
											];
										} else if ($v5['constraint_type'] == 'UNIQUE') {
											$temp2 = [
												'type' => 'unique',
												'columns' => $v5['column_names'],
												'full_table_name' => $full_table_name
											];
										} else if ($v5['constraint_type'] == 'INDEX') {
											// gin
											if ($v5['index_type'] == 'gin') {
												$v5['index_type'] = 'fulltext';
											}
											$temp2 = [
												'type' => mixedtolower($v5['index_type']),
												'columns' => $v5['column_names'],
												'full_table_name' => $full_table_name
											];
											$constraint_type = 'index';
										} else if ($v5['constraint_type'] == 'FOREIGN_KEY') {
											$name2 = ($k3 == '') ? $v5['foreign_table_name'] : ($v5['foreign_schema_name'] . '.' . $v5['foreign_table_name']);
											if ($v5['match_option'] == 'NONE') $v5['match_option'] = 'SIMPLE';
											$temp2 = [
												'type' => 'fk',
												'columns' => $v5['column_names'],
												'foreign_table' => $name2,
												'foreign_columns' => $v5['foreign_column_names'],
												'options' => [
													'match' => mixedtolower($v5['match_option']),
													'update' => mixedtolower($v5['update_rule']),
													'delete' => mixedtolower($v5['delete_rule'])
												],
												'name' => $v5['constraint_name'],
												'full_table_name' => $full_table_name
											];
										} else {
											print_r($v5);
											exit;
										}
										// add constraint
										$this->object_add([
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
								$this->object_add([
									'type' => 'extension',
									'schema' => $v5['schema_name'],
									'name' => $v5['extension_name'],
									'backend' => 'pgsql' // a must
								], $db_link);
							}
						}
						break;
					// schemas
					case 'schemas':
						foreach ($temp['data'] as $k2 => $v2) {
							$this->object_add([
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
						$sequence_model = factory::model('numbers_backend_db_class_model_sequences');
						if ($sequence_model->db_present()) {
							$sequence_attributes = $sequence_model->get();
						}
						// add sequences
						foreach ($temp['data'] as $k2 => $v2) {
							foreach ($v2 as $k3 => $v3) {
								if ($v3['schema_name'] == 'public') $v3['schema_name'] = '';
								$full_sequence_name = ltrim($v3['schema_name'] . '.' . $v3['sequence_name'], '.');
								$this->object_add([
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
							if ($k2 == 'public') {
								$k2 = '';
							}
							foreach ($v2 as $k3 => $v3) {
								$full_function_name = ltrim($v3['schema_name'] . '.' . $v3['function_name'], '.');
								// add object
								$this->object_add([
									'type' => 'function',
									'schema' => $k2,
									'name' => $k3,
									'backend' => 'pgsql',
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
								SELECT * FROM information_schema.key_column_usage ORDER BY ordinal_position DESC --position_in_unique_constraint
							) x ON x.constraint_name = c.constraint_name
							JOIN (
								SELECT * FROM information_schema.key_column_usage ORDER BY ordinal_position DESC
							) y ON y.ordinal_position = x.position_in_unique_constraint AND y.constraint_name = c.unique_constraint_name
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
						s2.nspname schema_name,
						a.relname sequence_name,
						r.rolname sequence_owner,
						(CASE WHEN s1.nspname = 'public' THEN '' ELSE s1.nspname END) || '.' || t.relname full_table_name
					FROM pg_class a
					INNER JOIN pg_catalog.pg_roles r ON r.oid = a.relowner
					INNER JOIN pg_namespace s2 ON s2.oid = a.relnamespace
					LEFT JOIN pg_depend d ON d.objid = a.oid AND d.deptype = 'a'
					LEFT JOIN pg_class t ON d.objid = a.oid AND d.refobjid = t.oid
					LEFT JOIN pg_namespace s1 ON s1.oid = t.relnamespace
					WHERE a.relkind = 'S'
TTT;
/**
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
*/
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
	 * Render SQL
	 *
	 * @param string $type
	 * @param array $data
	 * @param array $options
	 *		string mode
	 * @return string
	 * @throws Exception
	 */
	public function render_sql($type, $data, $options = array()) {
		$result = '';
		switch ($type) {
			// extension
			case 'extension_new':
				$result = "CREATE EXTENSION {$data['name']} SCHEMA {$data['schema']};";
				break;
			case 'extension_delete':
				$result = "DROP EXTENSION {$data['name']};";
				break;
			// schema
			case 'schema_new':
				$result = "CREATE SCHEMA {$data['data']['name']} AUTHORIZATION {$data['data']['owner']};";
				break;
			case 'schema_owner':
				$result = "ALTER SCHEMA {$data['schema']} OWNER TO {$data['owner']};";
				break;
			case 'schema_delete':
				$result = "DROP SCHEMA {$data['data']['name']};";
				break;
			// columns
			case 'column_delete':
				$result = "ALTER TABLE {$data['table']} DROP COLUMN {$data['name']};";
				break;
			case 'column_new':
				$default = $data['data']['default'] ?? null;
				if (is_string($default) && $default != 'now()') {
					$default = "'" . $default . "'";
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
					$columns[] = $this->render_sql('column_new', ['table' => '', 'name' => $k, 'data' => $v], ['column_new_no_alter' => true]);
				}
				$result2 = "CREATE TABLE {$data['data']['full_table_name']} (\n\t";
					$result2.= implode(",\n\t", $columns);
				$result2.= "\n);";
				$result = [$result2];
				$result[]= "ALTER TABLE {$data['data']['full_table_name']} OWNER TO {$data['data']['owner']};";
				break;
			case 'table_owner':
				$name = ltrim($data['schema'] . '.' . $data['name'], '.');
				$result = "ALTER TABLE {$name} OWNER TO {$data['owner']};";
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
				break;
			case 'constraint_delete':
				$result = "ALTER TABLE {$data['data']['full_table_name']} DROP CONSTRAINT {$data['name']};";
				break;
			// indexes
			case 'index_new':
				// fulltext indexes as gin
				if ($data['data']['type'] == 'fulltext') {
					$result = "CREATE INDEX {$data['name']} ON {$data['data']['full_table_name']} USING gin (" . implode(", ", $data['data']['columns']) . ");";
				} else {
					$result = "CREATE INDEX {$data['name']} ON {$data['data']['full_table_name']} USING {$data['data']['type']} (" . implode(", ", $data['data']['columns']) . ");";
				}
				break;
			case 'index_delete':
				$result = "DROP INDEX {$data['name']};";
				break;
			// sequences
			case 'sequence_new':
				$result = [];
				if (empty($data['data']['full_table_name'])) {
					$result[]= "CREATE SEQUENCE {$data['data']['full_sequence_name']} START 1;";
					$result[]= "ALTER SEQUENCE {$data['data']['full_sequence_name']} OWNER TO {$data['data']['owner']};";
				}
				// insert entry into sequences table
				$model = new numbers_backend_db_class_model_sequences();
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
					);
TTT;
				break;
			case 'sequence_delete':
				$result = [];
				if (empty($data['data']['full_table_name'])) {
					$result[]= "DROP SEQUENCE {$data['data']['full_sequence_name']};";
				}
				if (($options['mode'] ?? '') != 'drop') {
					$model = new numbers_backend_db_class_model_sequences();
					$result[]= "DELETE FROM {$model->full_table_name} WHERE sm_sequence_name = '{$data['data']['full_sequence_name']}'";
				}
				break;
			case 'sequence_owner':
				$name = ltrim($data['schema'] . '.' . $data['name'], '.');
				$result = "ALTER SEQUENCE {$name} OWNER TO {$data['owner']};";
				break;
			// functions
			case 'function_new':
				$result = [];
				$result[]= $data['data']['definition'] . ";";
				$result[]= "ALTER FUNCTION {$data['data']['header']} OWNER TO {$data['data']['owner']};";
				break;
			case 'function_delete':
				$result = "DROP FUNCTION {$data['data']['header']};";
				break;
			case 'function_owner':
				$result = "ALTER FUNCTION {$data['header']} OWNER TO {$data['owner']};";
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
				$result = "REVOKE ALL PRIVILEGES ON DATABASE {$data['database']} FROM {$data['owner']};";
				break;
			case 'permission_grant_schema':
				$result = "GRANT USAGE ON SCHEMA {$data['schema']} TO {$data['owner']};";
				break;
			case 'permission_grant_table':
				$result = "GRANT SELECT, INSERT, UPDATE, DELETE ON TABLE {$data['table']} TO {$data['owner']};";
				break;
			case 'permission_grant_sequence':
				$result = "GRANT USAGE, SELECT, UPDATE ON SEQUENCE {$data['sequence']} TO {$data['owner']};";
				break;
			case 'permission_grant_function':
				$result = "GRANT EXECUTE ON FUNCTION {$data['header']} TO {$data['owner']};";
				break;
			default:
				// nothing
				Throw new Exception($type . '?');
		}
		return $result;
	}
}