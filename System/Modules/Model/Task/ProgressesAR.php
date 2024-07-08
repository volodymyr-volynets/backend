<?php

namespace Numbers\Backend\System\Modules\Model\Task;
class ProgressesAR extends \Object\ActiveRecord {
	/**
	 * @var string
	 */
	public string $object_table_class = \Numbers\Backend\System\Modules\Model\Task\Progresses::class;

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
	 * Tenant #
	 *
	 *
	 *
	 * {domain{tenant_id}}
	 *
	 * @var int Domain: tenant_id Type: integer
	 */
	public ?int $sm_tskprogress_tenant_id = NULL;

	/**
	 * Progress #
	 *
	 *
	 *
	 * {domain{big_id_sequence}}
	 *
	 * @var int Domain: big_id_sequence Type: bigserial
	 */
	public ?int $sm_tskprogress_id = null;

	/**
	 * Name
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_tskprogress_name = null;

	/**
	 * Percent
	 *
	 *
	 *
	 *
	 *
	 * @var float Type: numeric
	 */
	public ?float $sm_tskprogress_percent = 0;

	/**
	 * Tasks Total
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: integer
	 */
	public ?int $sm_tskprogress_task_total = 1;

	/**
	 * Tasks Completed
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: integer
	 */
	public ?int $sm_tskprogress_task_completed = 0;

	/**
	 * Finish
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_tskprogress_finish = 0;

	/**
	 * Inactive
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_tskprogress_inactive = 0;

	/**
	 * Inserted Datetime
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: timestamp
	 */
	public ?string $sm_tskprogress_inserted_timestamp = null;

	/**
	 * Inserted User #
	 *
	 *
	 *
	 * {domain{user_id}}
	 *
	 * @var int Domain: user_id Type: bigint
	 */
	public ?int $sm_tskprogress_inserted_user_id = NULL;

	/**
	 * Updated Datetime
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: timestamp
	 */
	public ?string $sm_tskprogress_updated_timestamp = null;

	/**
	 * Updated User #
	 *
	 *
	 *
	 * {domain{user_id}}
	 *
	 * @var int Domain: user_id Type: bigint
	 */
	public ?int $sm_tskprogress_updated_user_id = NULL;
}