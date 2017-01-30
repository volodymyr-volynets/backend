<?php

/**
 * Schema/migration helper
 */
class numbers_backend_db_class_schemas {

	/**
	 * Get settings
	 *
	 * @param array $options
	 *		db_link
	 * @return array
	 * @throws Exception
	 */
	public static function get_settings($options = []) {
		$result = [
			'success' => false,
			'error' => [],
			'db_link' => $options['db_link'] ?? 'default',
			'db_list' => [],
			'db_settings' => [],
			'db_query_owner' => null, // insert, update, delete owner
			'db_schema_owner' => null, // schema owner
			'app_structure' => []
		];
		// gather credentials to primary master database server
		$default = application::get('db.' . $result['db_link']);
		$default_schema = application::get('db.' . $result['db_link'] . '_schema');
		$result['db_settings'] = [
			'submodule' => $default['submodule']
		];
		$temp = current($default['servers']);
		if (!empty($default_schema)) {
			$result['db_settings'] = array_merge_hard($result['db_settings'], $default_schema);
		} else {
			$result['db_settings'] = array_merge_hard($result['db_settings'], $temp);
		}
		$result['db_query_owner'] = $temp['username'];
		$result['db_schema_owner'] = $result['db_settings']['username'];
		// if its a multi database we need to get a list of databases
		$result['app_structure'] = application::get('application.structure');
		if (!empty($result['app_structure']['db_multiple'])) {
			// connect to db to get a list of databases
			$db_object = new db($result['db_link'] . '_temp', $result['db_settings']['submodule']);
			$db_status = $db_object->connect($result['db_settings']);
			if (!($db_status['success'] && $db_status['status'])) {
				$result['error'][] = 'Unable to open database connection!';
				return $result;
			}
			$result['db_list'] = [];
			if (!empty($result['app_structure']['db_prefix'])) {
				$sql = "SELECT * FROM (" . $db_object->sql_helper('fetch_databases') . ") a WHERE a.database_name LIKE '{$result['app_structure']['db_prefix']}%'";
			} else {
				$sql = $db_object->sql_helper('fetch_databases');
			}
			$temp = $db_object->query($sql);
			foreach ($temp['rows'] as $v) {
				$result['db_list'][] = $v['database_name'];
			}
		} else {
			$result['db_list'] = [$result['db_settings']['dbname']];
		}
		$result['success'] = true;
		return $result;
	}

	/**
	 * Process models from the code
	 *
	 * @param array $options
	 *		db_link
	 *		db_schema_owner
	 *		skip_db_object
	 * @return array
	 */
	public static function process_code_models($options = []) {
		$result = [
			'success' => false,
			'error' => [],
			'data' => [
				'object_attributes' => [],
				'object_documentation' => [],
				'object_import' => [],
				'object_forms' => [],
				'object_relations' => []
			],
			'objects' => [],
			'count' => []
		];
		do {
			// we need to process all dependencies first
			$dep = system_dependencies::process_deps_all($options);
			if (!$dep['success']) {
				$result = $dep;
				$result['error'][] = 'You must fix all dependency related errors first before processing models.';
				break;
			}
			// proccesing models
			if (empty($dep['data']['model_processed'])) {
				$result['error'][] = 'You do not have models to process!';
				break;
			}
			$ddl = new numbers_backend_db_class_ddl();
			// run 1 to deterine virtual tables
/*
			$first = true;
			$virtual_models = $dep['data']['model_processed'];
run_again:
			foreach ($virtual_models as $k => $v) {
				$k2 = str_replace('.', '_', $k);
				if ($v == 'object_table') {
					$model = factory::model($k2, true);
					foreach (object_widgets::widget_models as $v0) {
						if (!empty($model->{$v0})) {
							$v01 = $v0 . '_model';
							$virtual_models[str_replace('_', '.', $model->{$v01})] = 'object_table';
						}
					}
				}
			}
			if ($first) {
				$first = false;
				goto run_again; // some widgets have attributes
			}
			$dep['data']['model_processed'] = array_merge_hard($dep['data']['model_processed'], $virtual_models);
*/
			//$domains = object_data_domains::get_static();
			// run 2
			foreach ($dep['data']['model_processed'] as $k => $v) {
				$k2 = str_replace('.', '_', $k);
				if ($v == 'object_table') {
					$model = factory::model($k2, true, $options);
					// todo: disable non default db links
					$temp_result = $ddl->process_table_model($model, $options);
					if (!$temp_result['success']) {
						array_merge3($result['error'], $temp_result['error']);
					}
					//$object_documentation[$v][$k2] = $k2;
					// relation
					/*
					if ($flag_relation) {
						if (!empty($model->relation)) {
							$domain = $model->columns[$model->relation['field']]['domain'] ?? null;
							if (!empty($domain)) {
								$domain = str_replace('_sequence', '', $domain);
								$type = $domains[$domain]['type'];
							} else {
								$type = $model->columns[$model->relation['field']]['type'];
							}
							$object_relations[$k2] = [
								'rn_relattr_code' => $model->relation['field'],
								'rn_relattr_name' => $model->title,
								'rn_relattr_model' => $k2,
								'rn_relattr_domain' => $domain,
								'rn_relattr_type' => $type,
								'rn_relattr_inactive' => !empty($model->relation['inactive']) ? 1 : 0
							];
						}
						if (!empty($model->attributes)) {
							$object_attributes[$k2] = [
								'rn_attrmdl_code' => $k2,
								'rn_attrmdl_name' => $model->title,
								'rn_attrmdl_inactive' => 0
							];
						}
					}
					*/
				} else if ($v == 'object_sequence') {
					$temp_result = $ddl->process_sequence_model($k2, $options);
					if (!$temp_result['success']) {
						array_merge3($result['error'], $temp_result['error']);
					}
					//$object_documentation[$v][$k2] = $k2;
				} else if ($v == 'object_function') {
					$temp_result = $ddl->process_function_model($k2, $options);
					if (!$temp_result['success']) {
						array_merge3($result['error'], $temp_result['error']);
					}
					//$object_documentation[$v][$k2] = $k2;
				} else if ($v == 'object_extension') {
					$temp_result = $ddl->process_extension_model($k2, $options);
					if (!$temp_result['success']) {
						array_merge3($result['error'], $temp_result['error']);
					}
					//$object_documentation[$v][$k2] = $k2;
				} else if ($v == 'object_import') {
					$object_import[$k2] = [
						'model' => $k2
					];
				}
			}
			// if we have erros
			if (!empty($result['error'])) {
				break;
			}
			// if we got here - we are ok
			$result['success'] = true;
			$result['objects'] = $ddl->objects;
			$result['count'] = $ddl->count;
		} while(0);
		return $result;
	}

	/**
	 * Process schema from database
	 *
	 * @param array $options
	 *		db_link
	 * @return array
	 */
	public static function process_db_schema($options = []) {
		$options['db_link'] = $options['db_link'] ?? 'default';
		$ddl_object = factory::get(['db', $options['db_link'], 'ddl_object']);
		$temp_result = $ddl_object->load_schema($options['db_link']);
		if (!$temp_result['success']) {
			return $temp_result;
		} else {
			return [
				'success' => true,
				'error' => [],
				'data' => [],
				'objects' => $temp_result['data'],
				'count' => $temp_result['count']
			];
		}
	}

	/**
	 * Compare two sets of objects
	 *
	 * @param array $master_objects
	 * @param array $slave_objects
	 * @param array $options
	 *		type
	 *			schema
	 *			migration
	 *		db_link - mandatory for type = schema
	 * @return array
	 */
	public static function compare_two_set_of_objects($master_objects, $slave_objects, $options = []) {
		$ddl = new numbers_backend_db_class_ddl();
		$result = $ddl->compare_schemas($master_objects ?? [], $slave_objects ?? [], $options);
		// generate legend
		$result['legend'] = [
			'up' => [],
			'down' => []
		];
		// generate hints
		if ($result['success'] && $result['count'] > 0) {
			foreach (['up', 'down'] as $k0) {
				$result['hint'][] = '       * ' . $k0 . ':';
				foreach ($result[$k0] as $k2 => $v2) {
					$temp = '         * ' . $k2 . ': ';
					$result['hint'][] = $temp;
					$result['legend'][$k0][] = $temp;
					foreach ($v2 as $k3 => $v3) {
						$temp = '          * ' . $k3 . ' - ' . $v3['type'];
						$result['hint'][] = $temp;
						$result['legend'][$k0][] = $temp;
					}
				}
			}
		}
		return $result;
	}

	/**
	 * Generate SQL from difference and execute if required
	 *
	 * @param string $db_link
	 * @param array $diff
	 * @param array $options
	 *		mode
	 *			commit
	 *			drop
	 *		execute
	 *			true
	 *			false
	 *		legend
	 * @return array
	 */
	public static function generate_sql_from_diff_and_execute($db_link, $diff, $options = []) {
		$options['mode'] = $options['mode'] ?? 'commit';
		$ddl_object = factory::get(['db', $db_link, 'ddl_object']);
		$result = $ddl_object->generate_sql_from_diff_objects($db_link, $diff, ['mode' => $options['mode']]);
		$result['success'] = false;
		// if we need to execute
		if (!empty($options['execute']) && $result['count'] > 0) {
			$db_object = factory::get(['db', $db_link, 'object']);
			$db_object->begin();
			foreach ($result['data'] as $v) {
				$temp_result = $db_object->query($v);
				if (!$temp_result['success']) {
					array_merge3($result['error'], $temp_result['error']);
					$db_object->rollback();
					return $result;
				}
			}
			// see if we have a migration table
			$migration_model = new numbers_backend_db_class_model_migrations();
			if ($migration_model->db_present()) {
				$ts = format::now('timestamp');
				$temp_result = numbers_backend_db_class_model_migrations::collection()->merge([
					'sm_migration_db_link' => $db_link,
					'sm_migration_type' => 'schema',
					'sm_migration_action' => 'up',
					'sm_migration_name' => $ts,
					'sm_migration_developer' => application::get('developer.name') ?? 'Unknown',
					'sm_migration_inserted' => $ts,
					'sm_migration_legend' => json_encode($options['legend']),
					'sm_migration_sql_counter' => count($result['data']),
					'sm_migration_sql_changes' => json_encode($result['data'])
				]);
				if (!$temp_result['success']) {
					array_merge3($result['error'], $temp_result['error']);
					return $result;
				}
			}
			$db_object->commit();
		}
		$result['success'] = true;
		return $result;
	}
}