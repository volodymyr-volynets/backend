<?php

class numbers_backend_i18n_basic_base implements numbers_backend_i18n_interface_base {

	/**
	 * Data
	 *
	 * @var array
	 */
	public static $data = [
		'ids' => [],
		'hashes' => []
	];

	/**
	 * Missing translations
	 *
	 * @var array
	 */
	public static $missing = [];

	/**
	 * Language code
	 *
	 * @var string
	 */
	public static $language_code;

	/**
	 * Initializing i18n
	 *
	 * @param array $options
	 */
	public static function init($options = []) {
		do {
			self::$language_code = $options['language_code'];
			// retrive data from cache
			$cache_id = 'numbers_backend_i18n_basic_base_' . $options['language_code'];
			$cache = new cache();
			$data = $cache->get($cache_id);
			if ($data !== false) {
				self::$data = !empty($data) ? $data : ['ids' => [], 'hashes' => []];
				break;
			}
			// load data from database
			$sql = <<<TTT
				SELECT
					lc_translation_id id,
					lc_translation_text_sys sys, 
					lc_translation_text_new new
				FROM lc.translations
				WHERE 1=1
					AND lc_translation_language_code = '{$options['language_code']}'
TTT;
			$db = factory::model('numbers_backend_i18n_basic_model_translations')->db_object();
			$query_result = $db->query($sql);
			foreach ($query_result['rows'] as $k => $v) {
				if (strlen($v['sys']) > 40) {
					$v['sys'] = sha1($v['sys']);
				}
				self::$data['hashes'][$v['sys']] = $v['new'];
				self::$data['ids'][$v['id']] = $v['sys'];
			}
			// set the cache
			$cache->set($cache_id, self::$data, ['translations']);
		} while(0);
		// load data into cache
		return ['success' => 1, 'error' => []];
	}

	/**
	 * Destroy
	 */
	public static function destroy() {
		if (empty(self::$missing)) return;
		// we would create temp table
		$model = factory::model('numbers_backend_i18n_basic_model_missing');
		$db = $model->db_object();
		$db->query("CREATE TEMPORARY TABLE temp_translations (sys text, counter integer, lang text)");
		// insert data
		$data = [];
		foreach (self::$missing as $k => $v) {
			$data[] = ['sys' => $k, 'counter' => $v, 'lang' => self::$language_code];
		}
		$db->insert('temp_translations', $data);
		// merge data
		$sql = <<<TTT
			INSERT INTO {$model->name} (
				lc_missing_language_code,
				lc_missing_text_sys, 
				lc_missing_counter
			)
			SELECT
				lang lc_missing_language_code,
				sys lc_missing_text_sys, 
				0 lc_missing_counter
			FROM temp_translations a
			LEFT JOIN {$model->name} b ON a.sys = b.lc_missing_text_sys AND a.lang = b.lc_missing_language_code
			WHERE b.lc_missing_language_code IS NULL
TTT;
		$db->query($sql);
		// last step perform update
		$sql = "UPDATE lc.missing a SET lc_missing_counter = lc_missing_counter + coalesce((SELECT counter FROM temp_translations b WHERE b.sys = a.lc_missing_text_sys AND b.lang = a.lc_missing_language_code), 0)";
		$db->query($sql);
	}

	/**
	 * List of available languages
	 *
	 * @return array
	 */
	public static function languages() {
		return factory::model('numbers_backend_i18n_basic_model_languages')->get();
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
		// try to ge it by i18n
		$i18n = intval($i18n);
		$translated = false;
		// some texts we skip
		if (in_array($text . '', [' ', '&nbsp;', '-', '', '  '])) {
			$result = $text;
			$skip_translation_symbol = true;
			goto finish;
		}
		// we translate if language is not system
		if (self::$language_code != 'sys') {
			if ($i18n > 0 && isset(self::$data['ids'][$i18n])) {
				$result = self::$data['hashes'][self::$data['ids'][$i18n]];
			} else {
				// continue logic
				$hash = $text;
				if (strlen($hash) > 40) {
					$hash = sha1($hash);
				}
				if (array_key_exists($hash, self::$data['hashes'])) {
					$result = self::$data['hashes'][$hash];
					$translated = true;
				} else {
					$result = $text;
					// put data into missing
					if (self::$language_code != 'eng') {
						if (!isset(self::$missing[$result])) {
							self::$missing[$result] = 1;
						} else {
							self::$missing[$result]+= 1;
						}
					}
				}
			}
		} else {
			$result = $text;
		}
finish:
		// if we need to handle replaces, for example:
		// "Error occured on line [line_number]"
		if (!empty($options['replace'])) {
			foreach ($options['replace'] as $k => $v) {
				$result = str_replace($k, $v, $result);
			}
		}
		// todo: add debug mode, maybe append i18n
		if (debug::$debug && application::get('flag.global.__content_type') == 'text/html' && empty($skip_translation_symbol) && self::$language_code == 'sys') {
			$color = $translated ? 'blue' : 'red';
			$result.= ' <span style="color: ' . $color . '; font-weight: bold:">i</span>';
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