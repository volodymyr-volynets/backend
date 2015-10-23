<?php

class numbers_backend_crypt_class_base {

	/**
	 * Crypt link
	 *
	 * @var string
	 */
	public $crypt_link;

	/**
	 * Key
	 *
	 * @var string
	 */
	public $key;

	/**
	 * Salt
	 *
	 * @var string
	 */
	public $salt;

	/**
	 * Hash method
	 *
	 * @var string
	 */
	public $hash;

	/**
	 * Constructing
	 *
	 * @param string $key
	 */
	public function __construct($crypt_link, $options = []) {
		$this->crypt_link = $crypt_link;
		$this->key = isset($options['key']) ? $options['key'] : md5(getenv('numbers_env'));
		$this->salt = isset($options['salt']) ? $options['salt'] : 'a1b2c3';
		$this->hash = isset($options['hash']) ? $options['hash'] : 'sha1';
	}

	/**
	 * This would create encrypted token
	 *
	 * @param mixed $id
	 * @param mixed $data
	 */
	public function token_create($id, $data = null) {
		$result = array(
			'id' => $id,
			'data' => $data,
			'time' => time(),
			'ip' => request::ip()
		);
		$serilialized = base64_encode(serialize($result));
		$hash = $this->hash($serilialized . $this->salt);
		return $this->encrypt($hash . $serilialized);
	}

	/**
	 * Validate encrypted token
	 *
	 * @param string $token
	 * @param int $valid
	 * @param boolean $check_ip
	 * @return array|boolean
	 */
	public function token_validate($token, $valid_hours = 2, $check_ip = false) {
		do {
			$token_decrypted = trim($this->decrypt($token));
			$hash_length = mb_strlen($this->hash(1));
			$hash = mb_substr($token_decrypted, 0, $hash_length, 'latin1');
			$serilialized = mb_substr($token_decrypted, $hash_length, mb_strlen($token_decrypted, 'latin1'), 'latin1');
			if (empty($hash) || $hash != $this->hash($serilialized . $this->salt)) {
				break;
			}
			$result = unserialize(base64_decode($serilialized));
			if (empty($result['id'])) {
				break;
			}
			// validating valid hours
			if ($result['time'] + ($valid_hours * 60 * 60) <= time()) {
				break;
			}
			// ip verification
			if ($check_ip && $result['ip'] != request::ip()) {
				break;
			}
			return $result;
		} while(0);
		return false;
	}
}