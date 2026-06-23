<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Configuration\Environment\UnitTests;

use PHPUnit\Framework\TestCase;

class Base extends TestCase
{
    /**
     * Test \Numbers\Backend\Configuration\JSON\Base class
     */
    public function testLoadEnv()
    {
        $model = new \Numbers\Backend\Configuration\Environment\Base();
        $env = $model->readFile('/Numbers/Backend/Configuration/Environment/UnitTests/.env', 'development');
        $this->assertEquals($env['TEST']['ORIGINATOR'], '.env.development', 'Environment?');
        $this->assertEquals($env['TEST']['TEST1'], 'Test');
        $this->assertEquals($env['TEST']['TEST2'], 123);
        $this->assertEquals($env['TEST']['TEST3'], true);
    }
}
