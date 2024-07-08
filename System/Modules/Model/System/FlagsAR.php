<?php

namespace Numbers\Backend\System\Modules\Model\System;
class FlagsAR extends \Object\ActiveRecord {
	/**
	 * @var string
	 */
	public string $object_table_class = \Numbers\Backend\System\Modules\Model\System\Flags::class;

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
	 * Flag #
	 *
	 *
	 *
	 * {domain{resource_id_sequence}}
	 *
	 * @var int Domain: resource_id_sequence Type: serial
	 */
	public ?int $sm_sysflag_id = null;

	/**
	 * Parent Flag #
	 *
	 *
	 *
	 * {domain{resource_id}}
	 *
	 * @var int Domain: resource_id Type: integer
	 */
	public ?int $sm_sysflag_parent_sysflag_id = 0;

	/**
	 * Code
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_sysflag_code = null;

	/**
	 * Name
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_sysflag_name = null;

	/**
	 * Icon
	 *
	 *
	 *
	 * {domain{icon}}
	 *
	 * @var string Domain: icon Type: varchar
	 */
	public ?string $sm_sysflag_icon = null;

	/**
	 * Module Code
	 *
	 *
	 *
	 * {domain{module_code}}
	 *
	 * @var string Domain: module_code Type: char
	 */
	public ?string $sm_sysflag_module_code = null;

	/**
	 * Disabled
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_sysflag_disabled = 0;

	/**
	 * Inactive
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_sysflag_inactive = 0;
}