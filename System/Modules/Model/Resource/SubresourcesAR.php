<?php

namespace Numbers\Backend\System\Modules\Model\Resource;
class SubresourcesAR extends \Object\ActiveRecord {
	/**
	 * @var string
	 */
	public string $object_table_class = \Numbers\Backend\System\Modules\Model\Resource\Subresources::class;

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
	 * {domain{resource_id_sequence}}
	 *
	 * @var int Domain: resource_id_sequence Type: serial
	 */
	public ?int $sm_rsrsubres_id = null;

	/**
	 * Resource #
	 *
	 *
	 *
	 * {domain{resource_id}}
	 *
	 * @var int Domain: resource_id Type: integer
	 */
	public ?int $sm_rsrsubres_resource_id = 0;

	/**
	 * Parent Subresource #
	 *
	 *
	 *
	 * {domain{resource_id}}
	 *
	 * @var int Domain: resource_id Type: integer
	 */
	public ?int $sm_rsrsubres_parent_rsrsubres_id = 0;

	/**
	 * Code
	 *
	 *
	 *
	 * {domain{code}}
	 *
	 * @var string Domain: code Type: varchar
	 */
	public ?string $sm_rsrsubres_code = null;

	/**
	 * Name
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_rsrsubres_name = null;

	/**
	 * Icon
	 *
	 *
	 *
	 * {domain{icon}}
	 *
	 * @var string Domain: icon Type: varchar
	 */
	public ?string $sm_rsrsubres_icon = null;

	/**
	 * Module Code
	 *
	 *
	 *
	 * {domain{module_code}}
	 *
	 * @var string Domain: module_code Type: char
	 */
	public ?string $sm_rsrsubres_module_code = null;

	/**
	 * Disabled
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_rsrsubres_disabled = 0;

	/**
	 * Inactive
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_rsrsubres_inactive = 0;
}