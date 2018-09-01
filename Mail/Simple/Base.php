<?php

namespace Numbers\Backend\Mail\Simple;
class Base extends \Numbers\Backend\Mail\Common\Base implements \Numbers\Backend\Mail\Common\Interface2\Base {

	/**
	 * Send an email
	 *
	 * @param array $options
	 * @return array
	 */
	public function send(array $options) : array {
		$result = [
			'success' => false,
			'error' => [],
			'unique_id' => null
		];
		// see if we need to validate
		if (empty($options['validated'])) {
			$temp = $this->validate($options);
			if (!$temp['success']) {
				return $temp;
			} else if (!empty($temp['data']['requires_fetching'])) {
				// we error if we require fetching from database
				$result['error'][] = 'Fetching of email addresses is required!';
				return $result;
			} else {
				$options = $temp['data'];
			}
		}
		// to, cc, bcc
		$recepients = [];
		foreach (['to', 'cc', 'bcc'] as $r) {
			$recepients[$r] = [];
			foreach ($options[$r] as $v) {
				// todo: add recepient name here
				$recepients[$r][] = $v['email'];
			}
			$recepients[$r] = implode(',', $recepients[$r]);
		}
		// overrides for non production environments
		$environment = \Application::get('environment');
		$debug = \Application::get('debug.debug');
		if (!empty($debug)) {
			unset($recepients);
			$recepients['to'] = \Application::get('debug.email');
			$options['subject'] = '[' . $environment . '] ' . $options['subject'];
		}
		// crypt object
		$result['unique_id'] = sha1(serialize([$recepients, $options['subject'], microtime()]));
		// generating header
		if (isset($options['header'])) {
			$header = $options['header'];
		} else {
			$header = '';
		}
		// fetch mail settings
		$options = array_merge_hard(\Application::get('flag.global.mail') ?? [], $options);
		// process from
		if (isset($options['from']['name'])) {
			$header.= "From: {$options['from']['name']} <{$options['from']['email']}>\n";
			$header.= "Organization: {$options['from']['name']}\n";
		} else {
			$header.= "From: {$options['from']['email']}\n";
		}
		if (!empty($recepients['bcc'])) {
			$header.= "Bcc: " . $recepients['bcc'] . "\n";
		}
		if (!empty($recepients['cc'])) {
			$header.= "Cc: " . $recepients['cc'] . "\n";
		}
		$header.= "Reply-To: {$options['from']['email']}\n";
		$header.= "Errors-To: {$options['from']['email']}\n";
		$header.= "MIME-Version: 1.0\n";
		$header.= "X-Mailer: PHP/" . phpversion() . "\n";
		// important
		if (!empty($options['important'])) {
			$header.= "X-Priority: 1 (Highest)\n";
			$header.= "X-MSMail-Priority: High\n";
			$header.= "Importance: High\n";
		}
		// generating body for no attachment and a single message
		if (empty($options['attachments']) && count($options['message']) == 1) {
			$part = reset($options['message']);
			$header.= "Content-Type: {$part['type']};\n charset=\"{$part['charset']}\"\n";
			$header.= "Content-Transfer-Encoding: {$part['encoding']}\n";
			$body = $part['data'];
		} else {
			// has attachments or multiple messages
			$body_text = "";
			$unique_hash = sha1(mt_rand());
			$body_boundary = "boundary." . $unique_hash;
			$body_header = "";
			$body_header.= "Content-Type: multipart/alternative; boundary=\"{$body_boundary}\"\n";
			$body_header.= "Content-Transfer-Encoding: 7bit\n";
			$body_header.= "Content-Disposition: inline\n";
			// going though messages
			foreach ($options['message'] as $part) {
				$body_text.= "--{$body_boundary}\n";
				$body_text.= "Content-Type: {$part['type']}; charset=\"{$part['charset']}\"\n";
				$body_text.= "Content-Transfer-Encoding: {$part['encoding']}\n\n";
				$body_text.= $this->encodePart($part) . "\n\n";
			}
			$body_text.= "\n--{$body_boundary}--\n";
			// if we have attachments
			$text_part = "\nThis is a multi-part message in MIME format.\n\n";
			if (!empty($options['attachments'])) {
				$attachment_boundary = "boundary." . $unique_hash . ".attachments";
				$header.= "Content-Type: multipart/mixed; boundary=\"{$attachment_boundary}\"";
				$text_part.= "--{$attachment_boundary}\n";
				$text_part.= "{$body_header}\n";
				$text_part.= $body_text;
				// going though them
				foreach ($options['attachments'] as $v) {
					$text_part.= "--{$attachment_boundary}\n";
					$text_part.= "Content-Type: {$v['type']}; name=\"{$v['name']}\"\n";
					$text_part.= "Content-Transfer-Encoding: base64\n";
					$text_part.= "Content-Disposition: attachment; filename=\"{$v['name']}\"\n\n";
					$text_part.= $this->encodePart(['data' => $v['data'], 'encoding' => 'base64']);
				}
				$text_part .= "\n--{$attachment_boundary}--\n";
			} else {
				$header.= $body_header;
				$text_part.= $body_text;
			}
			$body = $text_part;
		}
		// trying to deliver
		if (mail($recepients['to'], $options['subject'], $body, $header)) {
			$result['success'] = true;
		} else {
			$result['error'][] = 'Could not deliver mail!';
		}
		return $result;
	}

	/**
	 * Encode part
	 *
	 * @param array $data
	 * @return string
	 * @throws Exception
	 */
	private function encodePart($data) {
		switch ($data['encoding']) {
			case 'base64':
				$data['data'] = chunk_split(base64_encode($data['data']), 76, "\n");
				break;
			case '7bit':
				break;
			default:
				throw new Exception('Unknown encoding: ' . $data['encoding']);
		}
		return $data['data'];
	}
}