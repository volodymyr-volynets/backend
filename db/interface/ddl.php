<?php

interface numbers_backend_db_interface_ddl {
	public function is_schema_supported($table_name);
	public function is_column_type_supported($column, $table_object);
	public function load_schema($db_link);
	public function render_sql($type, $data, $options = array());
}