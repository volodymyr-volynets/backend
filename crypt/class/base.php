<?php

class numbers_backend_crypt_class_base {

	/**
	 * Crypt link
	 *
	 * @var string
	 */
	public $crypt_link;

	/**
	 * Key (Encryption)
	 *
	 * @var string
	 */
	public $encryption_key;

	/**
	 * Key (Token)
	 *
	 * @var string
	 */
	public $token_key;

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
	 * Construct
	 *
	 * @param string $crypt_link
	 * @param array $options
	 */
	public function __construct(string $crypt_link, array $options = []) {
		$this->crypt_link = $crypt_link;
		$this->encryption_key = $options['encryption_key'] ?? sha1('key');
		$this->token_key = $options['token_key'] ?? $this->encryption_key;
		$this->salt = $options['salt'] ?? 'salt';
		$this->hash = $options['hash'] ?? 'sha1';
		$this->cipher = constant($options['cipher'] ?? 'MCRYPT_RIJNDAEL_256');
		$this->mode = constant($options['mode'] ?? 'MCRYPT_MODE_CBC');
		$this->base64 = !empty($options['base64']);
		$this->token_check_ip = !empty($options['token_check_ip']);
		$this->token_valid_hours = $options['token_valid_hours'] ?? 2;
		if (!empty($options['password'])) {
			$this->password = constant($options['password']);
		}
	}

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
	 *
	 * By default we provide AuthTkt implementation
	 */
	public function token_create($id, $token = null, $data = null, $options = []) {
		$time = $options['time'] ?? time();
		$ip = $options['ip'] ?? request::ip();
		if (empty($this->check_ip)) {
			$packed = pack('NN', 0, $time);
		} else {
			$packed = pack('NN', ip2long($ip), $time);
		}
		if ($data . '' != '') {
			$data = base64_encode(serialize($data));
		} else {
			$data = '';
		}
		$digest0 = md5($packed . $this->token_key . $id . "\0" . $token . "\0" . $data);
		$digest = md5($digest0 . $this->token_key);
		$result = sprintf('%s%08x%s!%s!%s', $digest, $time, $id, $token, $data);
		if ($this->base64) {
			return urlencode(base64_encode($result));
		} else {
			return urlencode($result);
		}
	}

	/**
	 * see crypt::token_validate();
	 */
	public function token_validate($token, $options = []) {
		$result = [
			'id' => null,
			'data' => null,
			'time' => null,
			'ip' => request::ip()
		];
		if ($this->base64) {
			$token2 = base64_decode($token);
		}  else {
			$token2 = $token;
		}
		$digest = substr($token2, 0, 32);
		$result['time'] = hexdec(substr($token2, 32, 8));
		$temp = explode('!', substr($token2, 40, strlen($token2)));
		$result['id'] = $temp[0];
		$result['token'] = $temp[1];
		if ($temp[2] . '' != '') {
			$result['data'] = unserialize(base64_decode($temp[2]));
		} else {
			$result['data'] = null;
		}
		$rebuilt = self::token_create($result['id'], $result['token'], $result['data'], ['time' => $result['time'], 'ip' => $result['ip']]);
		if (urldecode($rebuilt) != $token) {
			return false;
		} else {
			// todo: validate valid_hours
			return $result;
		}
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