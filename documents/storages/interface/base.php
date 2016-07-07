<?php

interface numbers_backend_documents_storages_interface_base {
	public function __construct();
	public function connect($options);
	public function close();
}