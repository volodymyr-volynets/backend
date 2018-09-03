<?php

namespace Numbers\Backend\Mail\Test\UnitTests;
class Base extends \PHPUnit\Framework\TestCase {

	/**
	 * Send mail
	 */
	public function testSend() {
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
	public function testSendSimple() {
		$result = \Mail::sendSimple(\Application::get('debug.email'), 'PHPUnit Mail::sendSimple', 'Test message');
		$this->assertEquals(true, $result['success'], 'Send simple failed!');
	}
}