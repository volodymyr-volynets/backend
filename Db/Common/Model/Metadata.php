<?php

namespace Numbers\Backend\Db\Common\Model;
class Metadata extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Metadata';
	public $schema;
	public $name = 'sm_metadatas';
	public $pk = ['sm_metadata_db_link', 'sm_metadata_type', 'sm_metadata_name'];
	public $orderby = [
		'sm_metadata_name' => SORT_DESC
	];
	public $limit;
	public $column_prefix = 'sm_metadata_';
	public $columns = [
		'sm_metadata_db_link' => ['name' => 'Db Link', 'domain' => 'code'],
		'sm_metadata_type' => ['name' => 'Type', 'domain' => 'code'],
		'sm_metadata_name' => ['name' => 'Name', 'domain' => 'code'],
		'sm_metadata_sql_version' => ['name' => 'SQL Version', 'domain' => 'code'],
	];
	public $constraints = [
		'sm_metadatas_pk' => ['type' => 'pk', 'columns' => ['sm_metadata_db_link', 'sm_metadata_type', 'sm_metadata_name']],
	];
	public $history = false;
	public $audit = false;
	public $optimistic_lock = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'MySQLi' => 'InnoDB'
	];

	public $cache = false;
	public $cache_tags = [];
	public $cache_memory = false;

	public $data_asset = [
		'classification' => 'public',
		'protection' => 0,
		'scope' => 'global'
	];

	/**
	 * Make schema changes
	 *
	 * @param string $db_link
	 * @param string $type
	 * @param string $name
	 * @param string $sql_version
	 * @return array
	 */
	public static function makeSchemaChanges(string $db_link, string $type, string $name, string $sql_version = '', bool $drop_only = false) : array {
		$result = [];
		// we need to fix name
		if (strpos($name, '.') === false) {
			$model = new \Numbers\Backend\Db\Common\Model\Metadata();
			$name = $model->schema . '.' . $name;
		}
		// delete first
		$result[] = self::queryBuilderStatic()->delete()->whereMultiple('AND', [
			'sm_metadata_db_link' => $db_link,
			'sm_metadata_type' => $type,
			'sm_metadata_name' => $name,
		])->sql();
		// insert
		if (!$drop_only) {
			$result[] = self::queryBuilderStatic()->insert()->columns([
				'sm_metadata_db_link',
				'sm_metadata_type',
				'sm_metadata_name',
				'sm_metadata_sql_version'
			])->values([[
				'sm_metadata_db_link' => $db_link,
				'sm_metadata_type' => $type,
				'sm_metadata_name' => $name,
				'sm_metadata_sql_version' => $sql_version
			]])->sql();
		}
		return $result;
	}
}