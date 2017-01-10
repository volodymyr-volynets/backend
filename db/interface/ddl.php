<?php

interface numbers_backend_db_interface_ddl {
	public function column_sql_type($column);
	public function load_schema($db_link);
	public function render_sql($type, $data, $options = array());
}