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
			$where = " AND lc_translation_language_code = '{$options['language_code']}'";
			// retrive data from cache
			$cache_id_file = $cache_id = 'numbers_backend_i18n_basic_base_' . $options['language_code'];
			// if we are including js translations
			if (strpos($_SERVER['REQUEST_URI'] ?? '', $cache_id_file . '.js') !== false) {
				$where.= " AND lc_translation_javascript = 1";
				$cache_id.= '_js';
			}
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
				FROM lc_translations
				WHERE 1=1
					{$where}
TTT;
			$query_result = factory::model('numbers_backend_i18n_basic_model_translations')->db_object->query($sql);
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
		// load js
		numbers_frontend_media_libraries_jssha_base::add();
		layout::add_js('/numbers/media_submodules/numbers_backend_i18n_basic_media_js_i18n.js', 5001);
		layout::add_js('/numbers/backend/i18n/basic/controller/javascript/_js/' . $cache_id_file . '.js', 50000);
		// load data into cache
		return ['success' => 1, 'error' => []];
	}

	/**
	 * Destroy
	 */
	public static function destroy() {
		if (empty(self::$missing)) return;
		//if (!chance(10)) return;
		// we would create temp table
		$db = factory::model('numbers_backend_i18n_basic_model_missing')->db_object();
		$db->query("CREATE TEMPORARY TABLE temp_translations (sys text, counter integer, lang text)");
		// insert data
		$data = [];
		foreach (self::$missing as $k => $v) {
			$data[] = ['sys' => $k, 'counter' => $v, 'lang' => self::$language_code];
		}
		$db->insert('temp_translations', $data);
		// merge data
		$sql = <<<TTT
			INSERT INTO lc_missing (
				lc_missing_id,
				lc_missing_language_code,
				lc_missing_text_sys, 
				lc_missing_counter
			)
			SELECT
				nextval('lc_missing_lc_missing_id_seq'),
				lang lc_missing_language_code,
				sys lc_missing_text_sys,
				0 lc_missing_counter
			FROM temp_translations a
			LEFT JOIN lc_translations b ON a.sys = b.lc_translation_text_sys AND a.lang = b.lc_translation_language_code
			LEFT JOIN lc_missing c ON a.sys = c.lc_missing_text_sys AND a.lang = c.lc_missing_language_code
			WHERE b.lc_translation_language_code IS NULL AND c.lc_missing_language_code IS NULL
TTT;
		$db->query($sql);
		// last step perform update
		$sql = "UPDATE lc_missing a SET lc_missing_counter = lc_missing_counter + coalesce((SELECT counter FROM temp_translations b WHERE b.sys = a.lc_missing_text_sys AND b.lang = a.lc_missing_language_code), 0)";
		$db->query($sql);
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
		$i18n = abs(intval($i18n));
		$translated = false;
		if (empty($i18n)) {
			// some texts we skip
			if (in_array($text . '', [' ', '&nbsp;', '-', '', '  '])) {
				$result = $text;
				$skip_translation_symbol = true;
				goto finish;
			}
			// integers and floats
			if (is_numeric($text)) {
				$result = format::id($text);
				$skip_translation_symbol = true;
				goto finish;
			}
		}
		// if we have i18n
		if (!empty($i18n) && isset(self::$data['ids'][$i18n])) {
			$result = self::$data['hashes'][self::$data['ids'][$i18n]];
			goto finish;
		}
		// we translate if language is not system
		if (self::$language_code != 'sys') {
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
		} else {
			$result = $text;
		}
finish:
		// append translation symbol for system language
		if (debug::$debug && application::get('flag.global.__content_type') == 'text/html' && empty($skip_translation_symbol) && empty($options['skip_translation_symbol']) && self::$language_code == 'sys') {
			$color = $translated ? 'blue' : 'red';
			$result.= '<span style="color: ' . $color . '; font-weight: bold:"><span style="display:none"> (</span>i<span style="display:none">)</span></span>';
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