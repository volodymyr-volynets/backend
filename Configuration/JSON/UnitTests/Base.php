<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Configuration\JSON\UnitTests;

use PHPUnit\Framework\TestCase;

class Base extends TestCase
{
    /**
     * Test \Numbers\Backend\Configuration\JSON\Base class
     */
    public function testLoadJson()
    {
        $model = new \Numbers\Backend\Configuration\JSON\Base();
        $json = $model->readFile('/Numbers/Backend/Configuration/JSON/UnitTests/test.json', 'development');
        $this->assertEquals($json['configs']['test_env'], 'development', 'Environment?');
        $this->assertNotEquals($json['configs']['test_path'], '$(PATH)', 'Path?');
    }
}
