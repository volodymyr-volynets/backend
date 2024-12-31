<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Mail\Test\UnitTests;

use PHPUnit\Framework\TestCase;

class Base extends TestCase
{
    /**
     * Send mail
     */
    public function testSend()
    {
        $result = \Mail::send([
            'to' => \Application::get('debug.email'),
            'cc' => \Application::get('developer.email') ?? null,
            'subject' => 'PHPUnit Mail::send',
            'message' => '<b>Test message</b>',
            'attachments' => [
                ['path' => __DIR__ . DIRECTORY_SEPARATOR . 'readme.txt', 'readme.txt'],
                ['data' => '!!!data!!!', 'name' => 'test.txt', 'type' => 'plain/text']
            ]
        ]);
        $this->assertEquals(true, $result['success'], 'Send failed!');
    }

    /**
     * Send mail (simple)
     */
    public function testSendSimple()
    {
        $result = \Mail::sendSimple(\Application::get('debug.email'), 'PHPUnit Mail::sendSimple', 'Test message');
        $this->assertEquals(true, $result['success'], 'Send simple failed!');
    }
}
