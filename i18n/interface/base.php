<?php

interface numbers_backend_i18n_interface_base {
	public static function init($options = []);
	public static function get($i18n, $text, $options = []);
	public static function set($variable, $value);
}