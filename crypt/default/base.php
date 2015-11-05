<?php

class numbers_backend_crypt_default_base extends numbers_backend_crypt_class_base implements numbers_backend_crypt_interface_base {

	/**
	 * Encrypting data (URL safe)
	 *
	 * @param string $data
	 * @return string
	 */
	public function encrypt($data) {
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$encrypted = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $this->key, $data, MCRYPT_MODE_CBC, $iv);
		// make it URL safe
		return base64_encode($iv . $encrypted);
	}

	/**
	 * Decrypting data (URL safe)
	 *
	 * @param string $data
	 * @return string
	 */
	public function decrypt($data) {
		$iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_CBC);
		$decoded = base64_decode($data);
		$iv = mb_substr($decoded, 0, $iv_size, 'latin1');
		$cipher = mb_substr($decoded, $iv_size, mb_strlen($decoded, 'latin1'), 'latin1');
		$decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $this->key, $cipher, MCRYPT_MODE_CBC, $iv);
		return rtrim($decrypted, "\0");
	}

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
}