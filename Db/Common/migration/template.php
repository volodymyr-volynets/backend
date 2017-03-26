<?php

class numbers_backend_db_class_migration_template extends numbers_backend_db_class_migration_base {

	/**
	 * Db link
	 *
	 * @var string
	 */
	public $db_link = '[[db_link]]';

	/**
	 * Developer
	 *
	 * @var string
	 */
	public $developer = '[[developer]]';

	/**
	 * Migrate up
	 *
	 * Throw exceptions if something fails!!!
	 */
	public function up() {
/*[[migrate_up]]*/
	}

	/**
	 * Migrate down
	 *
	 * Throw exceptions if something fails!!!
	 */
	public function down() {
/*[[migrate_down]]*/
	}
}