<?php

namespace Numbers\Backend\Session\Db\Model\Session;
class IPsAR extends \Object\ActiveRecord {
	/**
	 * @var string
	 */
	public string $object_table_class = \Numbers\Backend\Session\Db\Model\Session\IPs::class;

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
	public ?int $sm_sessips_tenant_id = NULL;

	/**
	 * Session #
	 *
	 *
	 *
	 * {domain{session_id}}
	 *
	 * @var string Domain: session_id Type: varchar
	 */
	public ?string $sm_sessips_session_id = null;

	/**
	 * Datetime Last Requested
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: timestamp
	 */
	public ?string $sm_sessips_last_requested = null;

	/**
	 * User #
	 *
	 *
	 *
	 * {domain{user_id}}
	 *
	 * @var int Domain: user_id Type: bigint
	 */
	public ?int $sm_sessips_user_id = NULL;

	/**
	 * User IP
	 *
	 *
	 *
	 * {domain{ip}}
	 *
	 * @var string Domain: ip Type: varchar
	 */
	public ?string $sm_sessips_user_ip = null;

	/**
	 * Pages Count
	 *
	 *
	 *
	 * {domain{counter}}
	 *
	 * @var int Domain: counter Type: integer
	 */
	public ?int $sm_sessips_pages_count = 0;

	/**
	 * Request Count
	 *
	 *
	 *
	 * {domain{counter}}
	 *
	 * @var int Domain: counter Type: integer
	 */
	public ?int $sm_sessips_request_count = 0;

	/**
	 * Bearer Token
	 *
	 *
	 *
	 * {domain{token}}
	 *
	 * @var string Domain: token Type: varchar
	 */
	public ?string $sm_sessips_bearer_token = null;
}