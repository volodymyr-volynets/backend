<?php

namespace Numbers\Backend\Session\Db\Model;
class SessionsAR extends \Object\ActiveRecord {
	/**
	 * @var string
	 */
	public string $object_table_class = \Numbers\Backend\Session\Db\Model\Sessions::class;

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
	public ?int $sm_session_tenant_id = NULL;

	/**
	 * Session #
	 *
	 *
	 *
	 * {domain{session_id}}
	 *
	 * @var string Domain: session_id Type: varchar
	 */
	public ?string $sm_session_id = null;

	/**
	 * Datetime Started
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: timestamp
	 */
	public ?string $sm_session_started = null;

	/**
	 * Datetime Expires
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: timestamp
	 */
	public ?string $sm_session_expires = null;

	/**
	 * Datetime Last Requested
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: timestamp
	 */
	public ?string $sm_session_last_requested = null;

	/**
	 * Pages Count
	 *
	 *
	 *
	 * {domain{counter}}
	 *
	 * @var int Domain: counter Type: integer
	 */
	public ?int $sm_session_pages_count = 0;

	/**
	 * User #
	 *
	 *
	 *
	 * {domain{user_id}}
	 *
	 * @var int Domain: user_id Type: bigint
	 */
	public ?int $sm_session_user_id = NULL;

	/**
	 * User IP
	 *
	 *
	 *
	 * {domain{ip}}
	 *
	 * @var string Domain: ip Type: varchar
	 */
	public ?string $sm_session_user_ip = null;

	/**
	 * Session Data
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: text
	 */
	public ?string $sm_session_data = null;

	/**
	 * Country Code
	 *
	 *
	 *
	 * {domain{country_code}}
	 *
	 * @var string Domain: country_code Type: char
	 */
	public ?string $sm_session_country_code = null;

	/**
	 * Request Count
	 *
	 *
	 *
	 * {domain{counter}}
	 *
	 * @var int Domain: counter Type: integer
	 */
	public ?int $sm_session_request_count = 0;

	/**
	 * Bearer Token
	 *
	 *
	 *
	 * {domain{token}}
	 *
	 * @var string Domain: token Type: varchar
	 */
	public ?string $sm_session_bearer_token = null;
}