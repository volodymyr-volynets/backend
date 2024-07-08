<?php

namespace Numbers\Backend\System\Modules\Model\Resource;
class FeaturesAR extends \Object\ActiveRecord {
	/**
	 * @var string
	 */
	public string $object_table_class = \Numbers\Backend\System\Modules\Model\Resource\Features::class;

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
	 * Resource #
	 *
	 *
	 *
	 * {domain{resource_id}}
	 *
	 * @var int Domain: resource_id Type: integer
	 */
	public ?int $sm_rsrcftr_resource_id = 0;

	/**
	 * Feature Code
	 *
	 *
	 *
	 * {domain{feature_code}}
	 *
	 * @var string Domain: feature_code Type: varchar
	 */
	public ?string $sm_rsrcftr_feature_code = null;

	/**
	 * Inactive
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: boolean
	 */
	public ?int $sm_rsrcftr_inactive = 0;
}