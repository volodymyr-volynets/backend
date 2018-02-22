<?php

namespace Numbers\Backend\Db\Extension\PostgreSQL\PostGIS\Model\Geo;
class PostGIS extends \Object\Extension {
	public $db_link;
	public $db_link_flag;
	public $title = 'postgis';
	public $schema = 'extensions';
	public $name = 'postgis';
	public $backend = 'PostgreSQL';
}