<?php

class Numbers_Backend_Db_Common_Migration_Template extends \Numbers\Backend\Db\Common\Migration\Base {

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