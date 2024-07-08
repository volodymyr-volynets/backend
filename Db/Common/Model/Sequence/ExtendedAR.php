<?php

namespace Numbers\Backend\Db\Common\Model\Sequence;
class ExtendedAR extends \Object\ActiveRecord {
	/**
	 * @var string
	 */
	public string $object_table_class = \Numbers\Backend\Db\Common\Model\Sequence\Extended::class;

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
	 * Name
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_sequence_name = null;

	/**
	 * Tenant #
	 *
	 *
	 *
	 * {domain{tenant_id}}
	 *
	 * @var int Domain: tenant_id Type: integer
	 */
	public ?int $sm_sequence_tenant_id = NULL;

	/**
	 * Module #
	 *
	 *
	 *
	 * {domain{module_id}}
	 *
	 * @var int Domain: module_id Type: integer
	 */
	public ?int $sm_sequence_module_id = NULL;

	/**
	 * Description
	 *
	 *
	 *
	 * {domain{description}}
	 *
	 * @var string Domain: description Type: varchar
	 */
	public ?string $sm_sequence_description = null;

	/**
	 * Type
	 *
	 *
	 * {options_model{\Numbers\Backend\Db\Common\Model\Sequence\Types}}
	 * {domain{type_code}}
	 *
	 * @var string Domain: type_code Type: varchar
	 */
	public ?string $sm_sequence_type = null;

	/**
	 * Prefix
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: varchar
	 */
	public ?string $sm_sequence_prefix = null;

	/**
	 * Length
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: smallint
	 */
	public ?int $sm_sequence_length = 0;

	/**
	 * Suffix
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: varchar
	 */
	public ?string $sm_sequence_suffix = null;

	/**
	 * Counter
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: bigint
	 */
	public ?int $sm_sequence_counter = 0;
}