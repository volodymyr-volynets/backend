<?php

class numbers_backend_db_class_migration_processor {

	/**
	 * Migration directory
	 *
	 * @var string
	 */
	public static $migration_dir = './misc/migrations/';

	/**
	 * Load migrations from the code
	 *
	 * @param array $options
	 *		db_link
	 *		load_migration_objects
	 * @return array
	 */
	public static function load_code_migrations($options = []) {
		$result = [
			'success' => false,
			'error' => [],
			'data' => [],
			'count' => 0,
			'last_migration_name' => null
		];
		$options['db_link'] = $options['db_link'] ?? 'default';
		// iterate over all migrations
		$migrations = helper_file::iterate(self::$migration_dir . $options['db_link'] . '/', ['only_extensions' => ['php']]);
		if (!empty($migrations)) {
			// sort by name
			usort($migrations, 'strcmp');
			// populate an array
			foreach ($migrations as $v) {
				$migration_name = pathinfo($v, PATHINFO_FILENAME);
				$temp = explode('__', $migration_name);
				$class = 'numbers_backend_db_class_migration_template_' . $migration_name;
				$result['data'][$migration_name] = [
					'class' => $class,
					'object' => null,
					'timestamp' => $temp[0],
					'developer' => $temp[1]
				];
				// load objects
				if (!empty($options['load_migration_objects'])) {
					require_once($v);
					$result['data'][$migration_name]['object'] = new $class();
				}
			}
			// find last migration name
			end($result['data']);
			$result['last_migration_name'] = key($result['data']);
			$result['count'] = count($result['data']);
		}
		$result['success'] = true;
		return $result;
	}

	/**
	 * Process migrations from the code
	 *
	 * @param array $options
	 *		db_link
	 *		mode
	 *			test
	 *			commit
	 * @return array
	 */
	public static function process_code_migrations($options = []) {
		$result = [
			'success' => false,
			'error' => [],
			'data' => [],
			'objects' => [],
			'count' => [],
			'rollback_count' => []
		];
		$options['db_link'] = $options['db_link'] ?? 'default';
		$options['mode'] = $options['mode'] ?? 'test';
		do {
			// ddl
			$ddl = new numbers_backend_db_class_ddl();
			// load migrations
			$migrations_result = self::load_code_migrations([
				'db_link' => $options['db_link'],
				'load_migration_objects' => true
			]);
			if (!$migrations_result['success']) {
				$result['error'] = array_merge($result['error'], $migrations_result['error']);
				break;
			}
			// if we have migrations
			if ($migrations_result['count'] > 0) {
				// process up migrations
				foreach ($migrations_result['data'] as $k => $v) {
					// execute migration in test mode
					$v['object']->reset(['mode' => 'test']);
					$v['object']->up();
					// add changes to ddl object
					foreach ($v['object']->data as $k2 => $v2) {
						if ($v2['operation'] == 'sql_query') continue;
						$temp_result = self::process_migration_object($ddl, $v2['name'], $v2['data']);
						if (!$temp_result['success']) {
							$result['error'] = array_merge($result['error'], $temp_result['error']);
							return $result;
						}
					}
				}
				$result['objects'] = $ddl->objects;
				$result['count'] = $ddl->count;
				// we need to see if we have merge issues so we migrate backwards
				foreach (array_reverse($migrations_result['data'], true) as $k => $v) {
					// execute migration in test mode
					$v['object']->reset(['mode' => 'test']);
					$v['object']->down();
					// add changes to ddl object
					foreach ($v['object']->data as $k2 => $v2) {
						if ($v2['operation'] == 'sql_query') continue;
						$temp_result = self::process_migration_object($ddl, $v2['name'], $v2['data']);
						if (!$temp_result['success']) {
							$result['error'] = array_merge($result['error'], $temp_result['error']);
							return $result;
						}
					}
				}
				$result['rollback_count'] = $ddl->count;
			}
			// if we got here means we are ok
			$result['success'] = true;
		} while(0);
		return $result;
	}

	/**
	 * Process migration object
	 *
	 * @param object $ddl
	 * @param string $name
	 * @param array $object
	 * @param array $options
	 *		db_link
	 * @throws Exception
	 * @return array
	 */
	public static function process_migration_object(& $ddl, $name, $object, $options = []) {
		$result = [
			'success' => false,
			'error' => []
		];
		$options['db_link'] = $options['db_link'] ?? 'default';
		// behavior is based on object type
		switch ($object['type']) {
			case 'schema_new':
				$object['type'] = str_replace('_new', '', $object['type']);
				// see if object already exists
				if (isset($ddl->objects[$options['db_link']][$object['type']][$object['name']])) {
					$result['error'][] = "Object already exists {$object['type']} {$object['name']}!";
					break;
				}
				$ddl->object_add($object, $options['db_link']);
				break;
			case 'table_new':
			case 'sequence_new':
				$object['type'] = str_replace('_new', '', $object['type']);
				// see if object already exists
				if (isset($ddl->objects[$options['db_link']][$object['type']][$object['schema']][$object['name']])) {
					$result['error'][] = "Object already exists {$object['type']} {$object['schema']}.{$object['name']}!";
					break;
				}
				$ddl->object_add($object, $options['db_link']);
				break;
			case 'constraint_new':
			case 'index_new':
				$object['type'] = str_replace('_new', '', $object['type']);
				// see if object already exists
				if (isset($ddl->objects[$options['db_link']][$object['type']][$object['schema']][$object['table']][$object['name']])) {
					$result['error'][] = "New object already exists {$object['type']} {$object['schema']}.{$object['table']}.{$object['name']}!";
					break;
				}
				$ddl->object_add($object, $options['db_link']);
				break;
			case 'extension_new':
			case 'function_new':
				$object['type'] = str_replace('_new', '', $object['type']);
				// see if object already exists
				if (isset($ddl->objects[$options['db_link']][$object['type']][$object['backend']][$object['schema']][$object['name']])) {
					$result['error'][] = "Object already exists {$object['type']} {$object['backend']}.{$object['schema']}.{$object['name']}!";
					break;
				}
				$ddl->object_add($object, $options['db_link']);
				break;
			case 'column_new':
			case 'column_change':
			case 'column_delete':
				// see if table does not exists
				if (!isset($ddl->objects[$options['db_link']]['table'][$object['schema']][$object['table']])) {
					$result['error'][] = "Object does not exists table {$object['schema']}.{$object['table']}!";
					break;
				}
				if ($object['type'] == 'column_new') {
					if (isset($ddl->objects[$options['db_link']]['table'][$object['schema']][$object['table']]['data']['columns'][$object['name']])) {
						$result['error'][] = "New object does not exists {$object['type']} {$object['schema']}.{$object['table']}.{$object['name']}!";
						break;
					}
					$ddl->object_add($object, $options['db_link']);
				} else if ($object['type'] == 'column_change') {
					if (!isset($ddl->objects[$options['db_link']]['table'][$object['schema']][$object['table']]['data']['columns'][$object['name']])) {
						$result['error'][] = "Change object does not exists {$object['type']} {$object['schema']}.{$object['table']}.{$object['name']}!";
						break;
					}
					$ddl->object_add($object, $options['db_link']);
				} else {
					if (!isset($ddl->objects[$options['db_link']]['table'][$object['schema']][$object['table']]['data']['columns'][$object['name']])) {
						$result['error'][] = "Delete object does not exists {$object['type']} {$object['schema']}.{$object['table']}.{$object['name']}!";
						break;
					}
					$ddl->object_remove($object, $options['db_link']);
				}
				break;
			case 'schema_delete':
				$object['type'] = str_replace('_delete', '', $object['type']);
				// see if object does not exists
				if (!isset($ddl->objects[$options['db_link']][$object['type']][$object['name']])) {
					$result['error'][] = "Delete object does not exists {$object['type']} {$object['name']}!";
					break;
				}
				$ddl->object_remove($object, $options['db_link']);
				break;
			case 'sequence_delete':
			case 'table_delete':
				$object['type'] = str_replace('_delete', '', $object['type']);
				// see if object does not exists
				if (!isset($ddl->objects[$options['db_link']][$object['type']][$object['schema']][$object['name']])) {
					$result['error'][] = "Delete object does not exists {$object['type']} {$object['schema']}.{$object['name']}!";
					break;
				}
				$ddl->object_remove($object, $options['db_link']);
				break;
			case 'constraint_delete':
			case 'index_delete':
				$object['type'] = str_replace('_delete', '', $object['type']);
				// see if object does not exists
				if (!isset($ddl->objects[$options['db_link']][$object['type']][$object['schema']][$object['table']][$object['name']])) {
					$result['error'][] = "Delete object already exists {$object['type']} {$object['schema']}.{$object['table']}.{$object['name']}!";
					break;
				}
				$ddl->object_remove($object, $options['db_link']);
				break;
			case 'extension_delete':
			case 'function_delete';
				$object['type'] = str_replace('_delete', '', $object['type']);
				// see if object does not exists
				if (!isset($ddl->objects[$options['db_link']][$object['type']][$object['backend']][$object['schema']][$object['name']])) {
					$result['error'][] = "Delete object does not exists {$object['type']} {$object['backend']}.{$object['schema']}.{$object['name']}!";
					break;
				}
				$ddl->object_remove($object, $options['db_link']);
				break;
			case 'table_owner':
			case 'sequence_owner':
			case 'schema_owner':
			case 'function_owner':
				$ddl->object_add($object, $options['db_link']);
				break;
			default:
				Throw new Exception("Migration object type {$object['type']} ?");
		}
		if (empty($result['error'])) {
			$result['success'] = true;
		}
		return $result;
	}

	/**
	 * Generate migration
	 *
	 * @param string $db_link
	 * @param array $data
	 *		return from self::compare_two_set_of_objects()
	 * @param array $options
	 * @return array
	 */
	public static function generate_migration($db_link, $data, $options = []) {
		$result = [
			'success' => false,
			'error' => [],
			'count' => 0,
			'migration_name' => null
		];
		// generate up and down
		$temp = [
			'up' => [],
			'down' => []
		];
		foreach (['up', 'down'] as $k0) {
			foreach ($data[$k0] as $k => $v) {
				foreach ($v as $k2 => $v2) {
					$v2['migration_id']*= 10;
					$data_variable = var_export_condensed($v2);
					$temp[$k0][] = "\t\t\$this->process('{$k}', {$v2['migration_id']}, '{$k2}', $data_variable);";
				}
			}
		}
		// generate timestamp with server timezone
		$ts = Format::now('timestamp', ['format' => 'Ymd_His_u', 'skip_i18n' => true, 'skip_user_timezone' => true]);
		// developers name
		$developer = trim(Application::get('developer.name') ?? 'Unknown');
		$developer = preg_replace('/\s+/', ' ', $developer);
		$developer_class = str_replace(' ', '_', $developer);
		// up & down changes
		$changes = '__up_' . count($temp['up']) . '__down_' . count($temp['down']);
		// class name
		$class = $ts . '__' . $developer_class . $changes;
		// load template
		$template = helper_file::read('../libraries/vendor/numbers/backend/db/class/migration/template.php');
		// add data to template
		$template = str_replace('[[db_link]]', $db_link, $template);
		$template = str_replace('[[developer]]', $developer, $template);
		$template = str_replace('numbers_backend_db_class_migration_template', 'numbers_backend_db_class_migration_template_' . $class, $template);
		$template = str_replace('/*[[migrate_up]]*/', implode("\n", $temp['up']), $template);
		$template = str_replace('/*[[migrate_down]]*/', implode("\n", $temp['down']), $template);
		// create a directory for db_link
		$migration_dir = self::$migration_dir . $db_link . '/';
		$migration_filename = $migration_dir . $class . '.php';
		if (!file_exists($migration_dir)) {
			helper_file::mkdir($migration_dir);
		}
		// write a file
		if (helper_file::write($migration_filename, $template, 0777, LOCK_EX, true)) {
			$result['success'] = true;
			$result['count'] = 1;
			$result['migration_name'] = $class;
		} else {
			$result['error'][] = 'Could not write migration file!';
		}
		return $result;
	}

	/**
	 * Drop migrations from the code
	 *
	 * @param array $options
	 *		db_link
	 * @return array
	 */
	public static function drop_code_migrations($options = []) {
		$result = [
			'success' => false,
			'error' => [],
			'count' => 0
		];
		$options['db_link'] = $options['db_link'] ?? 'default';
		do {
			$migrations = helper_file::iterate(self::$migration_dir . $options['db_link'] . '/', ['only_extensions' => ['php']]);
			if (!empty($migrations)) {
				$result['count'] = count($migrations);
				foreach ($migrations as $v) {
					unlink($v);
				}
			}
			// if we got here means we are ok
			$result['success'] = true;
		} while(0);
		return $result;
	}
}