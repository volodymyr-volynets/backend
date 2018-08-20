<?php

namespace Numbers\Backend\Cache\File\UnitTests;
class Base extends \PHPUnit\Framework\TestCase {

	/**
	 * test all cache operations
	 */
	public function testAll() {
		$time = time();
		$cache_dir = sys_get_temp_dir() . '/Unit_Tests_' . $time . '_' . rand(1000, 9999);
		$object = new \Numbers\Backend\Cache\File\Base('PHPUnit', [
			'cache_key' => 'test_key',
			'storage' => 'json',
			'expire' => 7200
		]);
		$result = $object->connect(['dir' => $cache_dir]);
		// validate if object returned success
		$this->assertEquals(true, $result['success']);
		// validate if we have actual directory
		$this->assertEquals(true, file_exists($object->options['dir']));
		// generate 25 caches, test get and set
		$caches = [];
		for ($i = 0; $i < 25; $i++) {
			$cache_id = 'key-' . $i;
			$data = 'Some test data ' . rand(1000, 9999);
			$tags = ['+' . $cache_id, $cache_id];
			$caches[$cache_id] = [
				'data' => $data,
				'tags' => $tags
			];
			// make sure that cache does not exists
			$result = $object->get($cache_id);
			$this->assertEquals(false, $result['success']);
			// set cache
			$result = $object->set($cache_id, $data, 25, $tags);
			$this->assertEquals(true, $result['success']);
			// see if cache exists
			$result = $object->get($cache_id);
			$this->assertEquals(true, $result['success']);
			$this->assertEquals($data, $result['data']);
		}
		// test garbage collector with tags
		$caches_left = [];
		foreach ($caches as $cache_id => $v) {
			if (chance(50)) {
				$result = $object->gc(3, [$v['tags']]);
				$this->assertEquals(true, $result['success']);
				// in this case we must not have a cache
				$result = $object->get($cache_id);
				$this->assertEquals(false, $result['success']);
			} else {
				$result = $object->gc(3, [[$v['tags'][1]]]);
				$this->assertEquals(true, $result['success']);
				// in this case we must have a cache
				$result = $object->get($cache_id);
				$this->assertEquals(true, $result['success']);
				$caches_left[] = $cache_id;
			}
		}
		// test garbage collector with old/all caches
		if (!empty($caches_left)) {
			$cache_id = current($caches_left);
			$result = $object->gc(1);
			$this->assertEquals(true, $result['success']);
			// in this case we must have a cache
			$result = $object->get($cache_id);
			$this->assertEquals(true, $result['success']);
			// reset all caches
			$result = $object->gc(2);
			$this->assertEquals(true, $result['success']);
			// in this case we must not have a cache
			$result = $object->get($cache_id);
			$this->assertEquals(false, $result['success']);
		}
		// close the object
		$result = $object->close();
		$this->assertEquals(true, $result['success']);
		// clean up
		\Helper\File::delete($cache_dir);
	}
}