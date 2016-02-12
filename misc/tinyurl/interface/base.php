<?php

interface numbers_backend_misc_tinyurl_interface_base {
	public static function get($hash);
	public static function set($url, $options = []);
}