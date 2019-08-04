<?php

namespace Numbers\Backend\Crypt\Common;
abstract class Base {

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
	abstract public function __construct(string $crypt_link, array $options = []);

	/**
	 * @see Crypt::encrypt();
	 */
	abstract public function encrypt(string $data) : string;

	/**
	 * @see Crypt::decrypt();
	 */
	abstract public function decrypt(string $data) : string;

	/**
	 * @see Crypt::compress();
	 */
	abstract public function compress(string $data);

	/**
	 * @see Crypt::uncompress();
	 */
	abstract public function uncompress(string $data);

	/**
	 * @see Crypt::hash();
	 */
	public function hash($data) {
		// serilializing array or object
		if (is_array($data) || is_object($data)) {
			// for array we need to sort by key
			if (is_array($data)) {
				ksort($data);
			}
			// serialize
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
	 * @see Crypt::hashFile();
	 */
	public function hashFile($path) {
		if ($this->hash == 'md5' || $this->hash == 'sha1') {
			$method = $this->hash . '_file';
			return $method($path);
		} else {
			return hash_file($this->hash, $data);
		}
	}

	/**
	 * @see Crypt::tokenCreate();
	 *
	 * By default we provide AuthTkt implementation
	 */
	public function tokenCreate($id, $token = null, $data = null, $options = []) {
		$time = $options['time'] ?? time();
		$ip = $options['ip'] ?? \Request::ip();
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
	 * @see Crypt::tokenValidate();
	 */
	public function tokenValidate($token, $options = []) {
		$result = [
			'id' => null,
			'data' => null,
			'time' => null,
			'ip' => \Request::ip()
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
		$rebuilt = self::tokenCreate($result['id'], $result['token'], $result['data'], ['time' => $result['time'], 'ip' => $result['ip']]);
		if (urldecode($rebuilt) != $token) {
			return false;
		} else if (empty($options['skip_time_validation'])) {
			// expiration
			if ($this->valid_hours > 0) {
				$hours = (time() - $result['time']) / 60 / 60;
				if ($hours > $this->valid_hours) {
					return false;
				}
			}
		}
		return $result;
	}

	/**
	 * @see Crypt::tokenVerify();
	 */
	public function tokenVerify($token, $tokens, $options = []) {
		if (empty($token)) {
			Throw new \Object\Error\UserException(\Object\Content\Messages::TOKEN_EXPIRED);
		} else {
			$token_data = $this->tokenValidate($token, $options);
			if ($token_data === false || !in_array($token_data['token'], $tokens)) {
				Throw new \Object\Error\UserException(\Object\Content\Messages::TOKEN_EXPIRED);
			}
			return $token_data;
		}
	}

	/**
	 * Hash password
	 *
	 * @param string $password
	 * @return string
	 */
	public function passwordHash($password) {
		return password_hash($password, $this->password);
	}

	/**
	 * Verify password
	 *
	 * @param string $password
	 * @param string $hash
	 * @return boolean
	 */
	public function passwordVerify($password, $hash) {
		return password_verify($password, $hash);
	}
}