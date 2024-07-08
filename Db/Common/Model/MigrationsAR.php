<?php

namespace Numbers\Backend\Db\Common\Model;
class MigrationsAR extends \Object\ActiveRecord {
	/**
	 * @var string
	 */
	public string $object_table_class = \Numbers\Backend\Db\Common\Model\Migrations::class;

	/**
	 * Constructing object
	 *
	 * @param array $options
	 *		skip_db_object
	 *		skip_table_object
	 */
	public function __construct($options = []) {
		if (empty($options['skip_table_object'])) {
			$this->object_table_object = new $this->object_table_class($options);
		}
	}
	/**
	 * Migration #
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: serial
	 */
	public ?int $sm_migration_id = null;

	/**
	 * Db Link
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_migration_db_link = null;

	/**
	 * Type
	 *
	 *
	 * {options_model{\Numbers\Backend\Db\Common\Model\Migration\Types}}
	 * {domain{type_code}}
	 *
	 * @var string Domain: type_code Type: varchar
	 */
	public ?string $sm_migration_type = null;

	/**
	 * Action
	 *
	 *
	 * {options_model{\Numbers\Backend\Db\Common\Model\Migration\Actions}}
	 * {domain{type_code}}
	 *
	 * @var string Domain: type_code Type: varchar
	 */
	public ?string $sm_migration_action = null;

	/**
	 * Name
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_migration_name = null;

	/**
	 * Developer
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_migration_developer = null;

	/**
	 * Inserted
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: timestamp
	 */
	public ?string $sm_migration_inserted = null;

	/**
	 * Rolled Back
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_migration_rolled_back = 0;

	/**
	 * Legend
	 *
	 *
	 *
	 *
	 *
	 * @var mixed Type: json
	 */
	public ?mixed $sm_migration_legend = null;

	/**
	 * SQL Counter
	 *
	 *
	 *
	 * {domain{counter}}
	 *
	 * @var int Domain: counter Type: integer
	 */
	public ?int $sm_migration_sql_counter = 0;

	/**
	 * SQL Changes
	 *
	 *
	 *
	 *
	 *
	 * @var mixed Type: json
	 */
	public ?mixed $sm_migration_sql_changes = null;
}