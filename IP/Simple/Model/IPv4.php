<?php

namespace Numbers\Backend\IP\Simple\Model;
class IPv4 extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M IP Version 4';
	public $schema;
	public $name = 'sm_ips_version4';
	public $pk;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_ipver4_';
	public $columns = [
		'sm_ipver4_start' => ['name' => 'Start', 'type' => 'bigint'],
		'sm_ipver4_end' => ['name' => 'End', 'type' => 'bigint'],
		'sm_ipver4_country_code' => ['name' => 'Country Code', 'domain' => 'country_code', 'null' => true],
		'sm_ipver4_province' => ['name' => 'Province', 'domain' => 'name', 'null' => true],
		'sm_ipver4_city' => ['name' => 'City', 'domain' => 'name', 'null' => true],
		'sm_ipver4_latitude' => ['name' => 'Latitude', 'domain' => 'geo_coordinate', 'null' => true, 'default' => null],
		'sm_ipver4_longitude' => ['name' => 'Longitude', 'domain' => 'geo_coordinate', 'null' => true, 'default' => null]
	];
	public $constraints = [
		'sm_ips_version4_pk' => ['type' => 'pk', 'columns' => ['sm_ipver4_start', 'sm_ipver4_end']],
	];
	public $indexes = [];
	public $history = false;
	public $audit = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'mysqli' => 'InnoDB'
	];

	public $cache = false;
	public $cache_tags = [];
	public $cache_memory = false;

	public $data_asset = [
		'classification' => 'public',
		'protection' => 0,
		'scope' => 'global'
	];
}