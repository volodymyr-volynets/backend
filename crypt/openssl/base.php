<?php

class numbers_backend_crypt_openssl_base extends numbers_backend_crypt_class_base implements numbers_backend_crypt_interface_base {

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
		$this->cipher = $options['cipher'] ?? 'aes256'; // its a string encryption method for openssl
		$this->mode = null; // not applicable
		$this->base64 = !empty($options['base64']);
		$this->check_ip = !empty($options['check_ip']);
		$this->valid_hours = $options['valid_hours'] ?? 2;
		if (!empty($options['password'])) {
			$this->password = constant($options['password']);
		}
	}

	/**
	 * see crypt::encrypt();
	 */
	public function encrypt($data) {
		$encrypted = openssl_encrypt($data, $this->cipher, $this->key);
		if ($this->base64) {
			return base64_encode($encrypted);
		} else {
			return $encrypted;
		}
	}

	/**
	 * see crypt::decrypt();
	 */
	public function decrypt($data) {
		if ($this->base64) {
			$decoded = base64_decode($data);
		} else {
			$decoded = $data;
		}
		return openssl_decrypt($decoded, $this->cipher, $this->key);
	}
}