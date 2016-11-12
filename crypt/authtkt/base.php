<?php

class numbers_backend_crypt_authtkt_base extends numbers_backend_crypt_class_base implements numbers_backend_crypt_interface_base {

	/**
	 * Constructing
	 *
	 * @param string $key
	 */
	public function __construct($crypt_link, $options = []) {
		$this->crypt_link = $crypt_link;
		$this->key = $options['key'] ?? sha1('key');
		$this->salt = $options['salt'] ?? 'salt';
		$this->hash = $options['hash'] ?? 'sha1';
		$this->cipher = 0;
		$this->mode = 0;
		$this->base64 = !empty($options['base64']);
		$this->check_ip = !empty($options['check_ip']);
		$this->valid_hours = $options['valid_hours'] ?? 2;
	}

	/**
	 * see crypt::token_create();
	 */
	public function token_create($id, $data = null, $options = []) {
		$time = $options['time'] ?? time();
		$ip = $options['ip'] ?? request::ip();
		if (empty($this->check_ip)) {
			$packed = pack('NN', 0, $time);
		} else {
			$packed = pack('NN', ip2long($ip), $time);
		}
		$data = base64_encode(serialize($data));
		$digest0 = md5($packed . $this->key . $id . "\0" . 'numbers' . "\0" . $data);
		$digest = md5($digest0 . $this->key);
		$result = sprintf('%s%08x%s!%s!%s', $digest, $time, $id, 'numbers', $data);
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
		$result['data'] = unserialize(base64_decode($temp[2]));
		$rebuilt = self::token_create($result['id'], $result['data'], ['time' => $result['time'], 'ip' => $result['ip']]);
		if (urldecode($rebuilt) != $token) {
			return false;
		} else {
			// todo: validate valid_hours
			return $result;
		}
	}

	/**
	 * see crypt::encrypt();
	 */
	public function encrypt($data) {
		return $data;
	}

	/**
	 * see crypt::decrypt();
	 */
	public function decrypt($data) {
		return $data;
	}
}