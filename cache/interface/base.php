<?php

interface numbers_backend_cache_interface_base {
	public function __construct($cache_link);
	public function connect($options);
	public function close();
	public function get($id);
	public function set($id, $data, $tags = [], $expire = null);
	public function gc($mode = 1, $tags = []);
}