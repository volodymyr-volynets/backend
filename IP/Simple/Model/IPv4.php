<?php

namespace Numbers\Backend\IP\Simple\Model;
class IPv4 extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $schema;
	public $name = 'ip_version4';
	public $pk = ['ip_ver4_id'];
	public $orderby;
	public $limit;
	public $column_prefix = 'ip_ver4_';
	public $columns = [
		'ip_ver4_start' => ['name' => 'Start', 'type' => 'bigint'],
		'ip_ver4_end' => ['name' => 'End', 'type' => 'bigint'],
		'ip_ver4_country_code' => ['name' => 'Country Code', 'domain' => 'country_code', 'null' => true],
		'ip_ver4_province' => ['name' => 'Province', 'domain' => 'name', 'null' => true],
		'ip_ver4_city' => ['name' => 'City', 'domain' => 'name', 'null' => true],
		'ip_ver4_latitude' => ['name' => 'Latitude', 'domain' => 'geo_coordinate', 'null' => true, 'default' => null],
		'ip_ver4_longitude' => ['name' => 'Longitude', 'domain' => 'geo_coordinate', 'null' => true, 'default' => null]
	];
	public $constraints = [
		'ip_version4_pk' => ['type' => 'pk', 'columns' => ['ip_ver4_start', 'ip_ver4_end']],
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