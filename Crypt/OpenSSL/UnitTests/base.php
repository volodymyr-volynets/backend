<?php

namespace Numbers\Backend\Crypt\OpenSSL\UnitTests;
class Base extends PHPUnit_Framework_TestCase {

	/**
	 * test all
	 */
	public function testAll() {
		// create crypt object
		$object = new \Numbers\Backend\Crypt\OpenSSL\Base('PHPUnit', [
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
		$this->assertEquals(true, $object->passwordVerify('data', $object->passwordHash('data')));
		$this->assertEquals(false, $object->passwordVerify('data2', $object->passwordHash('data')));
		// test token
		$token = $object->tokenCreate('id', 'data');
		$data = $object->tokenValidate(urldecode($token));
		$this->assertEquals('data', $data['data']);
	}
}