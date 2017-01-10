<?php

class numbers_backend_db_class_migration_base {

	/**
	 * Up
	 *
	 * @var array
	 */
	public $up = [];

	/**
	 * Up
	 */
	public function up() {
		// executed after up sequence
	}

	/**
	 * Down
	 *
	 * @var array
	 */
	public $down = [];

	/**
	 * Down
	 */
	public function down() {
		// executed after down sequence
	}

	/**
	 * Data
	 *
	 * @var array
	 */
	public $data = [];

	/**
	 * Db link
	 *
	 * @var string
	 */
	public $db_link;

	/**
	 * Db Object
	 *
	 * @var object
	 */
	private $db_object;

	/**
	 * Constructor
	 */
	public function __construct() {
		// initialize db object
		$this->db_object = new db($this->db_link);
	}
}