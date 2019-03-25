<?php

namespace Numbers\Backend\Crypt\OpenSSL;
class Base extends \Numbers\Backend\Crypt\Common\Base {

	/**
	 * Constructing
	 *
	 * @param string $crypt_link
	 * @param array $options
	 */
	public function __construct(string $crypt_link, array $options = []) {
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
	 * @see Crypt::encrypt();
	 */
	public function encrypt(string $data) : string {
		$encrypted = openssl_encrypt($data, $this->cipher, $this->key);
		if ($this->base64) {
			return base64_encode($encrypted);
		} else {
			return $encrypted;
		}
	}

	/**
	 * @see Crypt::decrypt();
	 */
	public function decrypt(string $data) : string {
		if ($this->base64) {
			$decoded = base64_decode($data);
		} else {
			$decoded = $data;
		}
		return openssl_decrypt($decoded, $this->cipher, $this->key);
	}

	/**
	 * @see Crypt::compress();
	 */
	public function compress(string $data) {
		return gzcompress($data, 9);
	}

	/**
	 * @see Crypt::uncompress();
	 */
	public function uncompress(string $data) {
		return gzuncompress($data);
	}
}