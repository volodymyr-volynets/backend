<?php

class numbers_backend_cache_file_unit_tests_base extends PHPUnit_Framework_TestCase {

	/**
	 * test all
	 */
	public function test_all() {
		$cache_dir = '/tmp/unit_tests_' . time() . '_' . rand(1000, 9999);
		$object = new numbers_backend_cache_file_base('PHPUnit');
		$result = $object->connect(['dir' => $cache_dir]);
		// validate if object returned success
		$this->assertEquals(true, $result['success']);
		// validate if we have actual directory
		$this->assertEquals(true, file_exists($cache_dir));
		// testing not existing cache
		$result = $object->get('cache-' . rand(1000, 9999));
		$this->assertEquals(false, $result);
		// testing creting new cache and then get it before and after it expires
		$result = $object->set('cache-1', 'data', ['tags'], time() + 1);
		$this->assertEquals(true, $result);
		$result = $object->get('cache-1');
		$this->assertEquals('data', $result);
		sleep(2);
		$result = $object->get('cache-1');
		$this->assertEquals(false, $result);
		// test garbage collector
		$result = $object->set('cache-2', 'data', ['tags2'], time() + 15);
		$result = $object->gc();
		$this->assertEquals(true, $result);
		// close the object
		$result = $object->close();
		$this->assertEquals(true, $result['success']);
		// clean up
		helper_file::rmdir($cache_dir);
	}
}