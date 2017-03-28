<?php

namespace Numbers\Backend\Mail\Common;
class Base {

	/**
	 * Validate
	 *
	 * @param array $options
	 * @return array
	 */
	public function validate($options) {
		$result = [
			'success' => false,
			'error' => [],
			'data' => [
				'from' => '',
				'subject' => '',
				'to' => [],
				'message' => [],
				'attachments' => [],
				'requires_fetching' => false
			]
		];
		do {
			// processing from
			if (isset($options['from'])) {
				$from = $options['from'];
			} else {
				$from = \Application::get('flag.global.mail.from');
			}
			if (empty($from['email'])) {
				$result['error'][] = 'You need to specify from email address!';
			} else {
				$result['data']['from'] = $from;
			}
			// processing subject
			if (isset($options['subject'])) {
				$result['data']['subject'] = $options['subject'];
			}
			// processing message
			if (is_string($options['message'])) {
				$result['data']['message'][] = [
					'data' => $options['message']
				];
			} else if (isset($options['message']['data'])) {
				$result['data']['message'][] = $options['message'];
			} else {
				$result['data']['message'] = $options['message'];
			}

			// detecting type for each message part
			$flags = [];
			foreach ($result['data']['message'] as $k => $v) {
				// we need to determine mesage type
				if (!isset($v['type'])) {
					if ($v['data'] == html_entity_decode(strip_tags($v['data']))) {
						$result['data']['message'][$k]['type'] = 'text/plain';
						$flags['text'] = 1;
					} else {
						$result['data']['message'][$k]['type'] = 'text/html';
						$flags['html'] = $k;
					}
				} else {
					if (!in_array($v['type'], object_type_mail_messages::$data)) {
						$result['error'][] = 'Unknown type: ' . $v['type'];
					}
				}
				// charset
				if (!isset($v['charset'])) {
					$result['data']['message'][$k]['charset'] = 'utf-8';
				}
				// encoding
				if (!isset($v['encoding'])) {
					$result['data']['message'][$k]['encoding'] = '7bit';
				}
			}
			// if we have html version but does not that text version we autogenerate
			if (isset($flags['html']) && empty($flags['text'])) {
				$temp = str_replace(['<br/>', '<br />', '<br>', '<hr/>', '<hr />', '<hr>'], "\n", $result['data']['message'][$flags['html']]['data']);
				array_unshift($result['data']['message'], [
					'type' => 'text/plain',
					'data' => html_entity_decode(strip_tags($temp)),
					'charset' => 'utf-8',
					'encoding' => '7bit'
				]);
			}
			// validating receipients
			foreach (['to', 'cc', 'bcc'] as $r) {
				$result['data'][$r] = [];
				if (!isset($options[$r])) {
					continue;
				}
				// validating
				$temp = $this->validateRecipient($options[$r]);
				if (!$temp['success']) {
					array_merge3($result['error'], $temp['error']);
				} else {
					$result['data'][$r] = $temp['data'];
					if (!empty($temp['requires_fetching'])) {
						$result['data']['requires_fetching'] = true;
					}
				}
			}
			// processing attachments
			if (!empty($options['attachments'])) {
				// if we have one attachment
				if (isset($options['attachments']['path']) || isset($options['attachments']['data'])) {
					$temp = [$options['attachments']];
				} else {
					$temp = $options['attachments'];
				}
				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				foreach ($temp as $v) {
					if (isset($v['path'])) {
						$type = finfo_file($finfo, $v['path']);
						if ($type === false) {
							$result['error'][] = 'Unknown attachment type!';
						} else {
							$result['data']['attachments'][] = [
								'type' => $type,
								'data' => file_get_contents($v['path']),
								'name' => basename($v['path'])
							];
						}
					} else if (isset($v['data'])) {
						if (empty($v['type'])) {
							$result['error'][] = 'Unknown attachment type!';
						} else {
							$result['data']['attachments'][] = [
								'type' => $v['type'],
								'data' => $v['data'],
								'name' => isset($v['name']) ? $v['name'] : ''
							];
						}
					}
				}
				finfo_close($finfo);
			}
			// if we have errors we break
			if ($result['error']) {
				break;
			}

			// if we got here, means we are ok
			$result['success'] = 1;
		} while(0);
		// we need to unset data key if we have an error
		if (!$result['success']) {
			unset($result['data']);
		}
		return $result;
	}

	/**
	 * Validate recipient
	 *
	 * @param mixed $to
	 * @return array
	 */
	public function validateRecipient($to) {
		$result = [
			'success' => false,
			'error' => [],
			'data' => [],
			'requires_fetching' => false
		];
		if (empty($to)) {
			$result['error'][] = 'Empty recepient!';
		} else if (is_string($to)) {
			if (strpos($to, '@') === false) {
				$result['data'][] = [
					'id' => 0,
					'code' => $to,
					'email' => null
				];
				$result['requires_fetching'] = true;
			} else {
				$result['data'][] = [
					'id' => 0,
					'code' => null,
					'email' => $to
				];
			}
		} else if (is_numeric($to)) {
			$result['data'][] = [
				'id' => $to,
				'code' => null,
				'email' => null
			];
			$result['requires_fetching'] = true;
		} else if (is_array($to)) {
			if (isset($v['email']) || isset($v['id']) || isset($v['code'])) {
				$result['data'][] = [
					'id' => isset($v['id']) ? $v['id'] : 0,
					'code' => isset($v['code']) ? $v['code'] : null,
					'email' => isset($v['email']) ? $v['email'] : null
				];
				if (empty($v['email'])) {
					$result['requires_fetching'] = true;
				}
			} else {
				foreach ($to as $k => $v) {
					$temp = $this->validate_recepient($v);
					if ($temp['success']) {
						$result['data'] = array_merge($result['data'], $temp['data']);
						if (!empty($temp['requires_fetching'])) {
							$result['requires_fetching'] = true;
						}
					} else {
						$result['error'] = array_merge($result['error'], $temp['error']);
					}
				}
			}
		}
		if (empty($result['error'])) {
			if (!empty($result['data'])) {
				$result['success'] = true;
			} else {
				$result['error'][] = 'Empty recepient!';
			}
		}
		return $result;
	}
}