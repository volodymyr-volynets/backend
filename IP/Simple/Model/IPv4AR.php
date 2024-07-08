<?php

namespace Numbers\Backend\IP\Simple\Model;
class IPv4AR extends \Object\ActiveRecord {
	/**
	 * @var string
	 */
	public string $object_table_class = \Numbers\Backend\IP\Simple\Model\IPv4::class;

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
	 * Start
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: bigint
	 */
	public ?int $sm_ipver4_start = 0;

	/**
	 * End
	 *
	 *
	 *
	 *
	 *
	 * @var int Type: bigint
	 */
	public ?int $sm_ipver4_end = 0;

	/**
	 * Country Code
	 *
	 *
	 *
	 * {domain{country_code}}
	 *
	 * @var string Domain: country_code Type: char
	 */
	public ?string $sm_ipver4_country_code = null;

	/**
	 * Province
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_ipver4_province = null;

	/**
	 * City
	 *
	 *
	 *
	 * {domain{name}}
	 *
	 * @var string Domain: name Type: varchar
	 */
	public ?string $sm_ipver4_city = null;

	/**
	 * Latitude
	 *
	 *
	 *
	 * {domain{geo_coordinate}}
	 *
	 * @var float Domain: geo_coordinate Type: numeric
	 */
	public ?float $sm_ipver4_latitude = NULL;

	/**
	 * Longitude
	 *
	 *
	 *
	 * {domain{geo_coordinate}}
	 *
	 * @var float Domain: geo_coordinate Type: numeric
	 */
	public ?float $sm_ipver4_longitude = NULL;
}