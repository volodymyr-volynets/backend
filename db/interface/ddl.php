<?php

interface numbers_backend_db_interface_ddl {
	public function is_column_type_supported($column, $table);
	public function load_schema($db_link);
	public function render_sql($type, $data, $options = array());
}