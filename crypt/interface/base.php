<?php

interface numbers_backend_crypt_interface_base {
	public function encrypt($data);
	public function decrypt($data);
	public function hash($data);
	public function hash_file($path);
	public function token_create($id, $data = null);
	public function token_validate($token, $valid_hours = 2, $check_ip = false);
}