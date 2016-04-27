<?php

class numbers_backend_crypt_openssl_unit_tests_base extends PHPUnit_Framework_TestCase {

	/**
	 * test all
	 */
	public function test_all() {
		// create crypt object
		$object = new numbers_backend_crypt_openssl_base('PHPUnit', [
			'cipher' => 'aes256',
			'key' => '1234567890123456',
			'salt' => '--salt--',
			'hash' => 'sha1',
			'password' => 'PASSWORD_DEFAULT'
		]);
		// testing encrypting functions
		$this->assertEquals('data', $object->decrypt($object->encrypt('data')));
		// test hash
		$this->assertEquals($object->hash('data'), sha1('data'));
		// test password
		$this->assertEquals(true, $object->password_verify('data', $object->password_hash('data')));
		$this->assertEquals(false, $object->password_verify('data2', $object->password_hash('data')));
		// test token
		$token = $object->token_create('id', 'data');
		$data = $object->token_validate(urldecode($token));
		$this->assertEquals('data', $data['data']);
	}
}