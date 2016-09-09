<?php

class numbers_backend_db_pgsql_model_plpgsql extends object_extension {
	public $db_link;
	public $db_link_flag;
	public $name = 'plpgsql';
	public $schema = 'pg_catalog';
	public $extension_submodule = 'pgsql';
}