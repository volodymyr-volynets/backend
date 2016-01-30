<?php

class numbers_backend_crypt_mcrypt_base extends numbers_backend_crypt_class_base implements numbers_backend_crypt_interface_base {

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
		$this->cipher = constant($options['cipher'] ?? 'MCRYPT_RIJNDAEL_256');
		$this->mode = constant($options['mode'] ?? 'MCRYPT_MODE_CBC');
		$this->base64 = !empty($options['base64']);
	}

	/**
	 * Encrypting data (URL safe)
	 *
	 * @param string $data
	 * @return string
	 */
	public function encrypt($data) {
		$iv_size = mcrypt_get_iv_size($this->cipher, $this->mode);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$encrypted = mcrypt_encrypt($this->cipher, $this->key, $data, $this->mode, $iv);
		// important to compute hash of encrypted value for validation purposes
		$hash = $this->hash($encrypted);
		if (!empty($this->base64)) {
			return base64_encode($iv . $hash . $encrypted);
		} else {
			return $iv . $hash . $encrypted;
		}
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
}