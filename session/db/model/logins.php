<?php

class numbers_backend_session_db_model_logins extends object_table {
	public $db_link;
	public $db_link_flag = 'flag.numbers.backend.session.db.default_db_link';
	public $table_name = 'sm.logins';
	public $table_pk = ['sm_login_id'];
	public $table_orderby = null;
	public $table_get_limit;
	public $table_columns = [
		'sm_login_id' => ['name' => 'Login #', 'type' => 'bigserial'],
		'sm_login_started' => ['name' => 'Datetime Started', 'type' => 'timestamp'],
		'sm_login_last_requested' => ['name' => 'Datetime Last Requested', 'type' => 'timestamp'],
		'sm_login_pages_count' => ['name' => 'Pages Count', 'type' => 'integer', 'default' => 0],
		'sm_login_user_ip' => ['name' => 'User IP', 'type' => 'varchar', 'length' => 50],
		'sm_login_user_id' => ['name' => 'User #', 'type' => 'integer', 'default' => 0],
		'sm_login_geo_country_code' => ['name' => 'Country', 'type' => 'varchar', 'length' => 50, 'null' => true],
		'sm_login_geo_region' => ['name' => 'Region', 'type' => 'varchar', 'length' => 50, 'null' => true],
		'sm_login_geo_city' => ['name' => 'City', 'type' => 'varchar', 'length' => 50, 'null' => true],
		'sm_login_geo_postal' => ['name' => 'Postal Code', 'type' => 'varchar', 'length' => 50, 'null' => true],
		'sm_login_geo_lat' => ['name' => 'Latitude', 'type' => 'numeric', 'default' => 0],
		'sm_login_geo_lon' => ['name' => 'Longitude', 'type' => 'numeric', 'default' => 0],
	];
	public $table_constraints = [
		'sm_logins_pk' => ['type' => 'pk', 'columns' => ['sm_login_id']],
	];
	public $table_indexes = [
		'sm_login_user_id_idx' => ['type' => 'btree', 'columns' => ['sm_login_user_id']]
	];
	public $table_history = false;
	public $table_audit = false;
	public $table_row_details = [];
	public $table_options_map = [];
	public $table_options_active = [];
	public $table_engine = [
		'mysqli' => 'InnoDB'
	];

	public $cache = false;
	public $cache_link;
	public $cache_link_flag;
	public $cache_tags = [];
	public $cache_memory = false;
}