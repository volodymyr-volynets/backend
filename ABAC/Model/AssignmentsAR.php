<?php

namespace Numbers\Backend\ABAC\Model;
class AssignmentsAR extends \Object\ActiveRecord {
	/**
	 * @var string
	 */
	public string $object_table_class = \Numbers\Backend\ABAC\Model\Assignments::class;

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
	 * Attribute #
	 *
	 *
	 *
	 * {domain{attribute_id_sequence}}
	 *
	 * @var int Domain: attribute_id_sequence Type: serial
	 */
	public ?int $sm_abacassign_id = null;

	/**
	 * Code
	 *
	 *
	 *
	 * {domain{group_code}}
	 *
	 * @var string Domain: group_code Type: varchar
	 */
	public ?string $sm_abacassign_code = null;

	/**
	 * Name
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_abacassign_name = null;

	/**
	 * Module Code
	 *
	 *
	 *
	 * {domain{module_code}}
	 *
	 * @var string Domain: module_code Type: char
	 */
	public ?string $sm_abacassign_module_code = null;

	/**
	 * Model #
	 *
	 *
	 *
	 * {domain{model_id}}
	 *
	 * @var int Domain: model_id Type: integer
	 */
	public ?int $sm_abacassign_model_id = NULL;

	/**
	 * Code
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_abacassign_model_code = null;

	/**
	 * Inactive
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_abacassign_inactive = 0;
}