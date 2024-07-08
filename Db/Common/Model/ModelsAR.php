<?php

namespace Numbers\Backend\Db\Common\Model;
class ModelsAR extends \Object\ActiveRecord {
	/**
	 * @var string
	 */
	public string $object_table_class = \Numbers\Backend\Db\Common\Model\Models::class;

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
	 * Model #
	 *
	 *
	 *
	 * {domain{model_id_sequence}}
	 *
	 * @var int Domain: model_id_sequence Type: serial
	 */
	public ?int $sm_model_id = null;

	/**
	 * Model
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_model_code = null;

	/**
	 * Name
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_model_name = null;

	/**
	 * Module Code
	 *
	 *
	 *
	 * {domain{module_code}}
	 *
	 * @var string Domain: module_code Type: char
	 */
	public ?string $sm_model_module_code = null;

	/**
	 * Tenant
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_model_tenant = 0;

	/**
	 * Period
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_model_period = 0;

	/**
	 * Widget Attributes
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_model_widget_attributes = 0;

	/**
	 * Widget Audit
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_model_widget_audit = 0;

	/**
	 * Widget Addresses
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_model_widget_addressees = 0;

	/**
	 * Data Asset Classification
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: text
	 */
	public ?string $sm_model_da_classification = null;

	/**
	 * Data Asset Protection
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: smallint
	 */
	public ?int $sm_model_da_protection = 0;

	/**
	 * Data Asset Scope
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: text
	 */
	public ?string $sm_model_da_scope = null;

	/**
	 * Optimistic Lock
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_model_optimistic_lock = 0;

	/**
	 * Inactive
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_model_inactive = 0;
}