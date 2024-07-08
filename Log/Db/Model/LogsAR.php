<?php

namespace Numbers\Backend\Log\Db\Model;
class LogsAR extends \Object\ActiveRecord {
	/**
	 * @var string
	 */
	public string $object_table_class = \Numbers\Backend\Log\Db\Model\Logs::class;

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
	public ?int $sm_log_tenant_id = 0;

	/**
	 * Log #
	 *
	 *
	 *
	 * {domain{uuid}}
	 *
	 * @var string Domain: uuid Type: char
	 */
	public ?string $sm_log_id = null;

	/**
	 * Group #
	 *
	 *
	 *
	 * {domain{uuid}}
	 *
	 * @var string Domain: uuid Type: char
	 */
	public ?string $sm_log_group_id = null;

	/**
	 * Originated #
	 *
	 *
	 *
	 * {domain{uuid}}
	 *
	 * @var string Domain: uuid Type: char
	 */
	public ?string $sm_log_originated_id = null;

	/**
	 * Host
	 *
	 *
	 *
	 * {domain{host}}
	 *
	 * @var string Domain: host Type: varchar
	 */
	public ?string $sm_log_host = 'CLI';

	/**
	 * Year
	 *
	 *
	 *
	 * {domain{year}}
	 *
	 * @var int Domain: year Type: smallint
	 */
	public ?int $sm_log_year = 0;

	/**
	 * User #
	 *
	 *
	 *
	 * {domain{user_id}}
	 *
	 * @var int Domain: user_id Type: bigint
	 */
	public ?int $sm_log_user_id = NULL;

	/**
	 * User IP
	 *
	 *
	 *
	 * {domain{ip}}
	 *
	 * @var string Domain: ip Type: varchar
	 */
	public ?string $sm_log_user_ip = NULL;

	/**
	 * Chanel
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_log_chanel = NULL;

	/**
	 * Type
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_log_type = 'General';

	/**
	 * Level
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_log_level = 'ALL';

	/**
	 * Status
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_log_status = null;

	/**
	 * Message
	 *
	 *
	 *
	 * {domain{message}}
	 *
	 * @var string Domain: message Type: text
	 */
	public ?string $sm_log_message = null;

	/**
	 * Trace
	 *
	 *
	 *
	 *
	 *
	 * @var mixed Type: json
	 */
	public ?mixed $sm_log_trace = null;

	/**
	 * Content Type
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_log_content_type = 'text/html';

	/**
	 * Controller Name
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_log_controller_name = NULL;

	/**
	 * Form Name
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_log_form_name = NULL;

	/**
	 * Form Statistics
	 *
	 *
	 *
	 *
	 *
	 * @var mixed Type: json
	 */
	public ?mixed $sm_log_form_statistics = null;

	/**
	 * Motifications
	 *
	 *
	 *
	 *
	 *
	 * @var mixed Type: json
	 */
	public ?mixed $sm_log_notifications = null;

	/**
	 * Affected Users
	 *
	 *
	 *
	 *
	 *
	 * @var mixed Type: json
	 */
	public ?mixed $sm_log_affected_users = null;

	/**
	 * Affected Rows
	 *
	 *
	 *
	 * {domain{counter}}
	 *
	 * @var int Domain: counter Type: integer
	 */
	public ?int $sm_log_affected_rows = 0;

	/**
	 * Error Rows
	 *
	 *
	 *
	 * {domain{counter}}
	 *
	 * @var int Domain: counter Type: integer
	 */
	public ?int $sm_log_error_rows = 0;

	/**
	 * Operation
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_log_operation = null;

	/**
	 * Duration (Float)
	 *
	 *
	 *
	 * {domain{duration_float}}
	 *
	 * @var bcnumeric Domain: duration_float Type: bcnumeric
	 */
	public ?bcnumeric $sm_log_duration = 0;

	/**
	 * Request URL
	 *
	 *
	 *
	 * {domain{url}}
	 *
	 * @var string Domain: url Type: varchar
	 */
	public ?string $sm_log_request_url = 'None';

	/**
	 * SQL
	 *
	 *
	 *
	 * {domain{sql_long_query}}
	 *
	 * @var string Domain: sql_long_query Type: text
	 */
	public ?string $sm_log_sql = NULL;

	/**
	 * AJAC
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_log_ajax = 0;

	/**
	 * Inactive
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_log_inactive = 0;

	/**
	 * Inserted Datetime
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: timestamp
	 */
	public ?string $sm_log_inserted_timestamp = null;

	/**
	 * Inserted User #
	 *
	 *
	 *
	 * {domain{user_id}}
	 *
	 * @var int Domain: user_id Type: bigint
	 */
	public ?int $sm_log_inserted_user_id = NULL;
}