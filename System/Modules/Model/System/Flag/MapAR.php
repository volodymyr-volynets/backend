<?php

namespace Numbers\Backend\System\Modules\Model\System\Flag;
class MapAR extends \Object\ActiveRecord {
	/**
	 * @var string
	 */
	public string $object_table_class = \Numbers\Backend\System\Modules\Model\System\Flag\Map::class;

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
	 * Subresource #
	 *
	 *
	 *
	 * {domain{resource_id}}
	 *
	 * @var int Domain: resource_id Type: integer
	 */
	public ?int $sm_sysflgmap_sysflag_id = 0;

	/**
	 * Action #
	 *
	 *
	 *
	 * {domain{action_id}}
	 *
	 * @var int Domain: action_id Type: smallint
	 */
	public ?int $sm_sysflgmap_action_id = 0;

	/**
	 * Disabled
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_sysflgmap_disabled = 0;

	/**
	 * Inactive
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_sysflgmap_inactive = 0;
}