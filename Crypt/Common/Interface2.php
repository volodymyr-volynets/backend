<?php

namespace Numbers\Backend\Crypt\Common;
interface Interface2 {
	public function encrypt($data);
	public function decrypt($data);
	public function hash($data);
	public function hashFile($path);
	public function tokenCreate($id, $data = null);
	public function tokenValidate($token, $options = []);
	public function passwordHash($password);
	public function passwordVerify($password, $hash);
}