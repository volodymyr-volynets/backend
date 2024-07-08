<?php

namespace Numbers\Backend\ABAC\Model;
class AttributesAR extends \Object\ActiveRecord {
	/**
	 * @var string
	 */
	public string $object_table_class = \Numbers\Backend\ABAC\Model\Attributes::class;

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
	public ?int $sm_abacattr_id = null;

	/**
	 * Code
	 *
	 *
	 *
	 * {domain{field_code}}
	 *
	 * @var string Domain: field_code Type: varchar
	 */
	public ?string $sm_abacattr_code = null;

	/**
	 * Name
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_abacattr_name = null;

	/**
	 * Module Code
	 *
	 *
	 *
	 * {domain{module_code}}
	 *
	 * @var string Domain: module_code Type: char
	 */
	public ?string $sm_abacattr_module_code = null;

	/**
	 * Parent Attribute #
	 *
	 *
	 *
	 * {domain{attribute_id}}
	 *
	 * @var int Domain: attribute_id Type: integer
	 */
	public ?int $sm_abacattr_parent_abacattr_id = NULL;

	/**
	 * Tenant
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_abacattr_tenant = 0;

	/**
	 * Module
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_abacattr_module = 0;

	/**
	 * Flag ABAC
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_abacattr_flag_abac = 0;

	/**
	 * Flag Assignment
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_abacattr_flag_assingment = 0;

	/**
	 * Flag Attribute
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_abacattr_flag_attribute = 0;

	/**
	 * Flag Link
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_abacattr_flag_link = 0;

	/**
	 * Flag Other Table
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_abacattr_flag_other_table = 0;

	/**
	 * Model #
	 *
	 *
	 *
	 * {domain{model_id}}
	 *
	 * @var int Domain: model_id Type: integer
	 */
	public ?int $sm_abacattr_model_id = NULL;

	/**
	 * Link Model #
	 *
	 *
	 *
	 * {domain{model_id}}
	 *
	 * @var int Domain: model_id Type: integer
	 */
	public ?int $sm_abacattr_link_model_id = NULL;

	/**
	 * Domain
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_abacattr_domain = null;

	/**
	 * Type
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_abacattr_type = null;

	/**
	 * Is Numeric Key
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_abacattr_is_numeric_key = 0;

	/**
	 * Environment Method
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_abacattr_environment_method = null;

	/**
	 * Inactive
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_abacattr_inactive = 0;
}