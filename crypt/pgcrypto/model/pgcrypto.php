<?php

class numbers_backend_crypt_pgcrypto_model_pgcrypto extends object_extension {
	public $db_link;
	public $db_link_flag;
	public $extension_name = 'pg_catalog.pgcrypto';
	public $extension_submodule = 'pgsql';
}