<?php

/**
 * Schema/migration helper
 */
namespace Numbers\Backend\Db\Common;
class Schemas {

	/**
	 * Get settings
	 *
	 * @param array $options
	 *		db_link
	 * @return array
	 * @throws Exception
	 */
	public static function getSettings($options = []) {
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
		$default = \Application::get('db.' . $result['db_link']);
		$default_schema = \Application::get('db.' . $result['db_link'] . '_schema');
		$result['db_settings'] = [
			'submodule' => $default['submodule'],
			'cache_link' => $default['cache_link'] ?? null
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
		$result['app_structure'] = \Application::get('application.structure');
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
	public static function processCodeModels($options = []) {
		$result = [
			'success' => false,
			'error' => [],
			'data' => [
				'object_attributes' => [],
				'\Object\Import' => [],
				'object_relations' => []
			],
			'objects' => [],
			'permissions' => [],
			'count' => []
		];
		do {
			// we need to process all dependencies first
			$dep = \System\Dependencies::processDepsAll($options);
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
			$ddl = new \Numbers\Backend\Db\Common\DDL();
			// run 1 to deterine virtual tables
			$first = true;
			$virtual_models = $dep['data']['model_processed'];
run_again:
			foreach ($virtual_models as $k => $v) {
				$k2 = str_replace('.', '_', $k);
				if ($v == '\Object\Table') {
					$model = \Factory::model($k2, true);
					foreach (\Object\Widgets::WIDGET_MODELS as $v0) {
						if (!empty($model->{$v0})) {
							$v01 = $v0 . '_model';
							$virtual_models[str_replace('_', '.', $model->{$v01})] = '\Object\Table';
						}
					}
				}
			}
			if ($first) {
				$first = false;
				goto run_again; // some widgets have attributes
			}
			$dep['data']['model_processed'] = array_merge_hard($dep['data']['model_processed'], $virtual_models);
			// run 2
			foreach ($dep['data']['model_processed'] as $k => $v) {
				$k2 = str_replace('.', '_', $k);
				if ($v == '\Object\Table') {
					$model = \Factory::model($k2, true, [$options]);
					// todo: disable non default db links
					$temp_result = $ddl->processTableModel($model, $options);
					if (!$temp_result['success']) {
						array_merge3($result['error'], $temp_result['error']);
					}
					// relation
					if (!empty($model->relation)) {
						$domain = $model->columns[$model->relation['field']]['domain'] ?? null;
						if (!empty($domain)) $domain = str_replace('_sequence', '', $domain);
						$result['data']['object_relations'][$k2] = [
							'sm_relation_model' => $k2,
							'sm_relation_name' => $model->title,
							'sm_relation_column' => $model->relation['field'],
							'sm_relation_domain' => $domain,
							'sm_relation_type' => $model->columns[$model->relation['field']]['type'],
							'sm_relation_inactive' => 0
						];
					}
					//$object_documentation[$v][$k2] = $k2;
					/*
					if (!empty($model->attributes)) {
						$object_attributes[$k2] = [
							'rn_attrmdl_code' => $k2,
							'rn_attrmdl_name' => $model->title,
							'rn_attrmdl_inactive' => 0
						];
					}
					*/
				} else if ($v == '\Object\Sequence') {
					$temp_result = $ddl->processSequenceModel($k2, $options);
					if (!$temp_result['success']) {
						array_merge3($result['error'], $temp_result['error']);
					}
					//$object_documentation[$v][$k2] = $k2;
				} else if ($v == '\Object\Function2') {
					$temp_result = $ddl->processFunctionModel($k2, $options);
					if (!$temp_result['success']) {
						array_merge3($result['error'], $temp_result['error']);
					}
					//$object_documentation[$v][$k2] = $k2;
				} else if ($v == '\Object\Extension') {
					$temp_result = $ddl->processExtensionModel($k2, $options);
					if (!$temp_result['success']) {
						array_merge3($result['error'], $temp_result['error']);
					}
					//$object_documentation[$v][$k2] = $k2;
				} else if ($v == '\Object\Import') {
					$result['data']['\Object\Import'][$k2] = $k2;
				} else {
					Throw new Exception('Unknown type: ' . $v);
				}
			}
			// if we have erros
			if (!empty($result['error'])) {
				break;
			}
			// generate permissions array
			foreach ($ddl->objects as $k => $v) {
				foreach ($v as $k2 => $v2) {
					// skip objects that does not have owner
					if (in_array($k2, ['constraint', 'index'])) continue;
					// loop through actual objects
					foreach ($v2 as $k3 => $v3) {
						if ($k2 == 'schema') {
							$result['permissions'][$k][$k2][$k3] = $k3;
						} else if ($k2 == 'function') { // we must pass header for function
							foreach ($v3 as $k4 => $v4) {
								foreach ($v4 as $k5 => $v5) {
									$name = ltrim($k4 . '.' . $k5, '.');
									$result['permissions'][$k][$k2][$name] = $v5['data']['header'];
								}
							}
						} else {
							foreach ($v3 as $k4 => $v4) {
								$name = ltrim($k3 . '.' . $k4, '.');
								$result['permissions'][$k][$k2][$name] = $name;
							}
						}
					}
				}
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
	public static function processDbSchema($options = []) {
		$options['db_link'] = $options['db_link'] ?? 'default';
		$ddl_object = \Factory::get(['db', $options['db_link'], 'ddl_object']);
		$temp_result = $ddl_object->loadSchema($options['db_link']);
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
	public static function compareTwoSetsOfObjects($master_objects, $slave_objects, $options = []) {
		$ddl = new \Numbers\Backend\Db\Common\DDL();
		$result = $ddl->compareSchemas($master_objects ?? [], $slave_objects ?? [], $options);
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
	public static function generateSqlFromDiffAndExecute($db_link, $diff, $options = []) {
		$options['mode'] = $options['mode'] ?? 'commit';
		$ddl_object = Factory::get(['db', $db_link, 'ddl_object']);
		$result = $ddl_object->generate_sql_from_diff_objects($db_link, $diff, ['mode' => $options['mode']]);
		$result['success'] = false;
		// if we need to execute
		if (!empty($options['execute']) && $result['count'] > 0) {
			$db_object = Factory::get(['db', $db_link, 'object']);
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
			$migration_model = new \Numbers\Backend\Db\Common\Model\Migrations();
			if ($migration_model->db_present()) {
				$ts = Format::now('timestamp');
				$temp_result = \Numbers\Backend\Db\Common\Model\Migrations::collection_static()->merge([
					'sm_migration_db_link' => $db_link,
					'sm_migration_type' => 'schema',
					'sm_migration_action' => 'up',
					'sm_migration_name' => $ts,
					'sm_migration_developer' => \Application::get('developer.name') ?? 'Unknown',
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

	/**
	 * Set permissions
	 *
	 * @param string $db_link
	 * @param string $db_query_owner
	 * @param array $objects
	 * @param array $options
	 *		database - database name
	 * @return array
	 */
	public static function setPermissions($db_link, $db_query_owner, $objects, $options = []) {
		$result = [
			'success' => false,
			'error' => [],
			'count' => 0,
			'legend' => []
		];
		// ddl object
		$ddl_object = Factory::get(['db', $db_link, 'ddl_object']);
		$sqls = [];
		// step 1: revoke all priviledges on database
		$temp = $ddl_object->render_sql('permission_revoke_all', [
			'database' => $options['database'],
			'owner' => $db_query_owner
		]);
		if (!empty($temp)) {
			$sqls[] = $temp;
			$result['legend'][] = "         * Revoke all privileges on database {$options['database']} from {$db_query_owner}";
		}
		// step 2: schemas
		if (!empty($objects['schema'])) {
			foreach ($objects['schema'] as $v) {
				$temp = $ddl_object->render_sql('permission_grant_schema', [
					'schema' => $v,
					'owner' => $db_query_owner
				]);
				if (!empty($temp)) {
					$sqls[] = $temp;
					$result['legend'][] = "         * Grant USAGE on schema {$v} to {$db_query_owner}";
				}
			}
		}
		// step 3: tables
		if (!empty($objects['table'])) {
			foreach ($objects['table'] as $v) {
				$temp = $ddl_object->render_sql('permission_grant_table', [
					'table' => $v,
					'owner' => $db_query_owner
				]);
				if (!empty($temp)) {
					$sqls[] = $temp;
					$result['legend'][] = "         * Grant SELECT, INSERT, UPDATE, DELETE on table {$v} to {$db_query_owner}";
				}
			}
		}
		// step 4: sequences
		if (!empty($objects['sequence'])) {
			foreach ($objects['sequence'] as $v) {
				$temp = $ddl_object->render_sql('permission_grant_sequence', [
					'sequence' => $v,
					'owner' => $db_query_owner
				]);
				if (!empty($temp)) {
					$sqls[] = $temp;
					$result['legend'][] = "         * Grant USAGE, SELECT, UPDATE on sequence {$v} to {$db_query_owner}";
				}
			}
		}
		// step 5: functions
		if (!empty($objects['function'])) {
			foreach ($objects['function'] as $k => $v) {
				$temp = $ddl_object->render_sql('permission_grant_function', [
					'function' => $k,
					'header' => $v,
					'owner' => $db_query_owner
				]);
				if (!empty($temp)) {
					$sqls[] = $temp;
					$result['legend'][] = "         * Grant EXECUTE on function {$k} to {$db_query_owner}";
				}
			}
		}
		// if we have changes
		if (!empty($sqls)) {
			array_unshift($result['legend'], '       * permission:');
			$db_object = Factory::get(['db', $db_link, 'object']);
			$db_object->begin();
			foreach ($sqls as $v) {
				$temp_result = $db_object->query($v);
				if (!$temp_result['success']) {
					array_merge3($result['error'], $temp_result['error']);
					$db_object->rollback();
					return $result;
				}
			}
			// see if we have a migration table
			$migration_model = new \Numbers\Backend\Db\Common\Model\Migrations();
			if ($migration_model->db_present()) {
				$ts = Format::now('timestamp');
				$temp_result = \Numbers\Backend\Db\Common\Model\Migrations::collection_static()->merge([
					'sm_migration_db_link' => $db_link,
					'sm_migration_type' => 'permission',
					'sm_migration_action' => 'update',
					'sm_migration_name' => $ts,
					'sm_migration_developer' => \Application::get('developer.name') ?? 'Unknown',
					'sm_migration_inserted' => $ts,
					'sm_migration_legend' => json_encode($result['legend']),
					'sm_migration_sql_counter' => count($sqls),
					'sm_migration_sql_changes' => json_encode($sqls)
				]);
				if (!$temp_result['success']) {
					array_merge3($result['error'], $temp_result['error']);
					return $result;
				}
			}
			$db_object->commit();
		}
		$result['success'] = true;
		$result['count'] = count($sqls);
		return $result;
	}

	/**
	 * Import data
	 *
	 * @param string $db_link
	 * @param array $data
	 * @param array $options
	 * @return array
	 */
	public static function importData($db_link, $data, $options = []) {
		$result = [
			'success' => false,
			'error' => [],
			'count' => 0,
			'legend' => []
		];
		$db_object = Factory::get(['db', $db_link, 'object']);
		$db_object->begin();
		// process import models one by one
		foreach ($data as $k => $v) {
			switch ($k) {
				case 'object_relations':
					$import_model = new \Numbers\Backend\Db\Common\Model\Relations();
					if ($import_model->db_present()) {
						$import_result = $import_model->collection(['pk' => ['sm_relation_model']])->merge_multiple($v);
						if (!$import_result['success']) {
							$result['error'] = array_merge($result['error'], $import_result['error']);
							return $result;
						}
						$result['count']+= $import_result['count'];
						$result['legend'][] = '         * Process relations changes ' . $import_result['count'];
					}
					break;
				case '\Object\Import':
					foreach ($v as $k2 => $v2) {
						$model = new $v2();
						$import_result = $model->process();
						if (!$import_result['success']) {
							$result['error'] = array_merge($result['error'], $import_result['error']);
							return $result;
						}
						$result['legend'] = array_merge($result['legend'], $import_result['legend']);
						$result['count']+= $import_result['count'];
					}
					break;
			}
		}
		// see if we have a migration table
		if (!empty($result['count'])) {
			$migration_model = new \Numbers\Backend\Db\Common\Model\Migrations();
			if ($migration_model->db_present()) {
				$ts = Format::now('timestamp');
				$temp_result = $migration_model->collection()->merge([
					'sm_migration_db_link' => $db_link,
					'sm_migration_type' => 'import',
					'sm_migration_action' => 'update',
					'sm_migration_name' => $ts,
					'sm_migration_developer' => \Application::get('developer.name') ?? 'Unknown',
					'sm_migration_inserted' => $ts,
					'sm_migration_legend' => json_encode($result['legend']),
					'sm_migration_sql_counter' => $result['count'],
					'sm_migration_sql_changes' => null
				]);
				if (!$temp_result['success']) {
					array_merge3($result['error'], $temp_result['error']);
					return $result;
				}
			}
		}
		$result['success'] = true;
		$db_object->commit();
		return $result;
	}
}