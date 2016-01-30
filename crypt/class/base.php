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
	public $hash;

	/**
	 * Base64
	 *
	 * @var boolean
	 */
	public $base64 = false;

	/**
	 * Generate a hash of a value
	 *
	 * @param string $data
	 * @return string
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
	 * Generate has of a file
	 *
	 * @param string $path
	 * @return string
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
		return urlencode($this->encrypt($hash . $serilialized));
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
			$token_decrypted = $this->decrypt($token);
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