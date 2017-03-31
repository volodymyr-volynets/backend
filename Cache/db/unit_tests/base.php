<?php

class numbers_backend_cache_db_unit_tests_base extends PHPUnit_Framework_TestCase {

	/**
	 * test all
	 */
	public function test_all() {
		$object = new numbers_backend_cache_db_base('PHPUnit');
		$result = $object->connect();
		// validate if object returned success
		$this->assertEquals(true, $result['success']);
		// testing not existing cache
		$result = $object->get('PHPUnit-cache-' . rand(1000, 9999));
		$this->assertEquals(false, $result);
		// testing creting new cache and then get it before and after it expires
		$result = $object->set('PHPUnit-cache-1', 'data', ['tags'], time() + 1);
		$this->assertEquals(true, $result);
		$result = $object->get('PHPUnit-cache-1');
		$this->assertEquals('data', $result);
		sleep(2);
		$result = $object->get('PHPUnit-cache-1');
		$this->assertEquals(false, $result);
		// test garbage collector
		$result = $object->set('PHPUnit-cache-2', 'data', ['tags2'], time() + 15);
		$result = $object->gc(1, ['tags2']);
		$this->assertEquals(true, $result);
		$this->assertEquals(false, $object->get('PHPUnit-cache-2'));
		// close the object
		$result = $object->close();
		$this->assertEquals(true, $result['success']);
	}
}