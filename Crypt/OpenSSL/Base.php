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
		$this->token_key = $options['token_key'] ?? sha1('key');
		$this->encryption_key = $options['encryption_key'] ?? sha1('key');
		$this->bearer_key = $options['bearer_key'] ?? sha1('bearer');
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
		$ivlen = openssl_cipher_iv_length($this->cipher);
		$iv = openssl_random_pseudo_bytes($ivlen);
		$encrypted = $iv . openssl_encrypt($data, $this->cipher, $this->encryption_key, OPENSSL_RAW_DATA, $iv);
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
		$ivlen = openssl_cipher_iv_length($this->cipher);
		$iv = substr($decoded, 0, $ivlen);
		$decoded = substr($decoded, $ivlen);
		return openssl_decrypt($decoded, $this->cipher, $this->encryption_key, OPENSSL_RAW_DATA, $iv);
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