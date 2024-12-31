<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Cache\Memcached\UnitTests;

use PHPUnit\Framework\TestCase;

class Base extends TestCase
{
    /**
     * test all cache operations
     */
    public function testAll()
    {
        // db and cache objects
        \Bootstrap::init();
        $object = new \Cache('default');
        // flush all caches
        $result = $object->gc(2);
        // generate 25 caches, test get and set
        $caches = [];
        for ($i = 0; $i < 25; $i++) {
            $cache_id = 'key-' . $i;
            $data = 'Some test data ' . rand(1000, 9999);
            $tags = ['+' . $cache_id, $cache_id, 'tag-all-caches'];
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
                $result = $object->gc(3, [[$v['tags'][0], $v['tags'][1]]]);
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
    }
}
