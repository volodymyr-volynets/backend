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
	 * Cipher
	 *
	 * @var string
	 */
	public $cipher;

	/**
	 * Mode
	 *
	 * @var string
	 */
	public $mode;

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
	public $hash = 'sha1';

	/**
	 * Base64
	 *
	 * @var boolean
	 */
	public $base64 = false;

	/**
	 * Check ip
	 *
	 * @var boolean
	 */
	public $check_ip = false;

	/**
	 * Valid hours
	 *
	 * @var int
	 */
	public $valid_hours = 2;

	/**
	 * Password hash algorithm
	 *
	 * @var int
	 */
	public $password = PASSWORD_DEFAULT;

	/**
	 * see crypt::hash();
	 */
	public function hash($data) {
		// serilializing array or object
		if (is_array($data) || is_object($data)) {
			$data = serialize($data);
		}
		if ($this->hash == 'md5' || $this->hash == 'sha1') {
			$method = $this->hash;
			return $method($data);
		} else {
			return hash($this->hash, $data);
		}
	}

	/**
	 * see crypt::hash_file();
	 */
	public function hash_file($path) {
		if ($this->hash == 'md5' || $this->hash == 'sha1') {
			$method = $this->hash . '_file';
			return $method($path);
		} else {
			return hash_file($this->hash, $data);
		}
	}

	/**
	 * see crypt::token_create();
	 */
	public function token_create($id, $data = null) {
		$result = [
			'id' => $id,
			'data' => $data,
			'time' => time(),
			'ip' => request::ip()
		];
		$encrypted = $this->encrypt(serialize($result));
		if (empty($this->base64)) {
			return urlencode(base64_encode($encrypted));
		} else {
			return urlencode($encrypted);
		}
	}

	/**
	 * see crypt::token_validate();
	 */
	public function token_validate($token) {
		do {
			if (empty($this->base64)) {
				$token = base64_decode($token);
			}
			$decrypted = $this->decrypt($token);
			if ($decrypted === false) {
				break;
			}
			$result = unserialize($decrypted);
			if (empty($result['id'])) {
				break;
			}
			// validating valid hours
			if (($result['time'] + ($this->valid_hours * 60 * 60)) <= time()) {
				break;
			}
			// ip verification
			if ($this->check_ip && $result['ip'] != request::ip()) {
				break;
			}
			return $result;
		} while(0);
		return false;
	}

	/**
	 * Hash password
	 *
	 * @param string $password
	 * @return string
	 */
	public function password_hash($password) {
		return password_hash($password, $this->password);
	}

	/**
	 * Verify password
	 *
	 * @param string $password
	 * @param string $hash
	 * @return boolean
	 */
	public function password_verify($password, $hash) {
		return password_verify($password, $hash);
	}
}