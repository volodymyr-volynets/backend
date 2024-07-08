<?php

namespace Numbers\Backend\System\Modules\Model;
class NotificationsAR extends \Object\ActiveRecord {
	/**
	 * @var string
	 */
	public string $object_table_class = \Numbers\Backend\System\Modules\Model\Notifications::class;

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
	 * Code
	 *
	 *
	 *
	 * {domain{feature_code}}
	 *
	 * @var string Domain: feature_code Type: varchar
	 */
	public ?string $sm_notification_code = null;

	/**
	 * Name
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_notification_name = null;

	/**
	 * Subject
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: text
	 */
	public ?string $sm_notification_subject = null;

	/**
	 * Body
	 *
	 *
	 *
	 *
	 *
	 * @var string Type: text
	 */
	public ?string $sm_notification_body = null;

	/**
	 * Important
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_notification_important = 0;

	/**
	 * Email Model
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_notification_email_model_code = null;

	/**
	 * Inactive
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_notification_inactive = 0;
}