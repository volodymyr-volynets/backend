<?php

class numbers_backend_crypt_pgcrypto_base extends numbers_backend_crypt_class_base implements numbers_backend_crypt_interface_base {

	/**
	 * Link to database
	 *
	 * @var string
	 */
	private $db_link;

	/**
	 * Indicator whether key is set to db
	 *
	 * @var boolean
	 */
	public static $flag_key_sent_to_db = false;

	/**
	 * Constructing
	 *
	 * @param string $crypt_link
	 * @param array $options
	 */
	public function __construct($crypt_link, $options = []) {
		$this->crypt_link = $crypt_link;
		$this->key = $options['key'] ?? sha1('key');
		$this->salt = $options['salt'] ?? 'salt';
		$this->hash = $options['hash'] ?? 'sha1';
		$this->cipher = 'compress-algo=1, cipher-algo=aes256';
		$this->mode = null;
		if (empty($options['db_link'])) {
			Throw new Exception('You must indicate db link!');
		}
		$this->db_link = $options['db_link'];
	}

	/**
	 * see crypt::encrypt();
	 */
	public function encrypt($data) {
		$this->send_key_to_db();
		$db = new db($this->db_link);
		$result = $db->query("SELECT sm.encrypt('" . $db->escape($data) . "') AS encrypted;");
		return $result['rows'][0]['encrypted'] ?? false;
	}

	/**
	 * see crypt::decrypt();
	 */
	public function decrypt($data) {
		$this->send_key_to_db();
		$db = new db($this->db_link);
		$result = $db->query("SELECT sm.decrypt('" . pg_escape_bytea($data) . "') AS decrypted;");
		return $result['rows'][0]['decrypted'] ?? false;
	}

	/**
	 * Send key to db
	 */
	public function send_key_to_db() {
		if (!self::$flag_key_sent_to_db) {
			$db = new db($this->db_link);
			// todo: disable logging in db
			$db->query("SELECT set_config('sm.numbers.crypt.key', '" . $db->escape($this->key) . "', false)");
			$db->query("SELECT set_config('sm.numbers.crypt.options', '" . $db->escape($this->cipher) . "', false)");
			// todo: enable logging in db
			self::$flag_key_sent_to_db = true;
		}
		return true;
	}
}