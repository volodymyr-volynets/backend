<?php

class numbers_backend_i18n_class_base implements numbers_backend_i18n_interface_base {

	/**
	 * Initializing i18n
	 *
	 * @param array $options
	 */
	public static function init($options = []) {
		return ['success' => 1, 'error' => []];
	}

	/**
	 * Get translation
	 *
	 * @param string $i18n
	 * @param string $text
	 * @param array $options
	 * @return string
	 */
	public static function get($i18n, $text, $options = []) {
		$result = $text;
		// if we need to handle replaces, for example:
		// "Error occured on line [line_number]"
		if (!empty($options['replace'])) {
			foreach ($options['replace'] as $k => $v) {
				$result = str_replace($k, $v, $result);
			}
		}
		// todo: add debug mode, maybe append i18n
		if (debug::$debug && application::get('flag.global.__content_type') == 'text/html') {
			$result.= ' <span style="color:blue">i</span>';
		}
		return $result;
	}

	/**
	 * Set variable into i18n
	 *
	 * @param string $variable
	 * @param mixed $value
	 */
	public static function set($variable, $value) {
		application::set('flag.global.i18n.' . $variable, $value);
	}
}