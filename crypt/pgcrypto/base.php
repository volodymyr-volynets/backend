<?php

class numbers_backend_crypt_pgcrypto_base extends numbers_backend_crypt_class_base implements numbers_backend_crypt_interface_base {

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
		$this->cipher = constant($options['cipher'] ?? 'MCRYPT_RIJNDAEL_256');
		$this->mode = constant($options['mode'] ?? 'MCRYPT_MODE_CBC');
		$this->base64 = !empty($options['base64']);
		$this->check_ip = !empty($options['check_ip']);
		$this->valid_hours = !empty($options['valid_hours']);
	}

	/**
	 * see crypt::encrypt();
	 */
	public function encrypt($data) {
		$iv_size = mcrypt_get_iv_size($this->cipher, $this->mode);
		$iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
		$encrypted = mcrypt_encrypt($this->cipher, $this->key, $data, $this->mode, $iv);
		// important to compute hash of encrypted value for validation purposes
		$hash = $this->hash($encrypted);
		if ($this->base64) {
			return base64_encode($iv . $hash . $encrypted);
		} else {
			return $iv . $hash . $encrypted;
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
		// extract values out of data
		$iv_size = mcrypt_get_iv_size($this->cipher, $this->mode);
		$iv = mb_substr($decoded, 0, $iv_size, 'latin1');
		$hash_size = mb_strlen($this->hash(1), 'latin1');
		$hash = mb_substr($decoded, $iv_size, $hash_size, 'latin1');
		$cipher = mb_substr($decoded, $iv_size + $hash_size, mb_strlen($decoded, 'latin1'), 'latin1');
		// comparing ciper with hash
		if ($this->hash($cipher) != $hash) {
			return false;
		}
		// decrypting
		$decrypted = mcrypt_decrypt($this->cipher, $this->key, $cipher, $this->mode, $iv);
		if ($decrypted !== false) {
			return rtrim($decrypted, "\0");
		} else {
			return false;
		}
	}
}