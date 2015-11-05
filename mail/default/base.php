<?php

class numbers_backend_mail_default_base extends numbers_backend_mail_class_base implements numbers_backend_mail_interface_base {

	/**
	 * Send an email
	 *
	 * @param array $options
	 * @return array
	 */
	public function send($options) {
		$result = [
			'success' => false,
			'error' => []
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
				$recepients[$r][] = $v['email'];
			}
			$recepients[$r] = implode(',', $recepients[$r]);
		}
		// crypt object
		$crypt = new crypt();
		$x_email_notification = $crypt->hash([$options['to'], $options['subject']]) . microtime();
		// generating header
		if (isset($options['header'])) {
			$header = $options['header'];
		} else {
			$header = '';
		}
		if (isset($options['from']['name'])) {
			$header.= "From: {$options['from']['name']} <{$options['from']['email']}>\n";
			$header.= "Organization: {$options['from']['name']}\n";
		} else {
			$header.= "From: {$options['from']['email']}\n";
		}
		if (!empty($recepients['bcc'])) {
			$header.= "Bcc: " . implode(', ', $recepients['bcc']) . "\n";
		}
		if (!empty($recepients['cc'])) {
			$header.= "Cc: " . implode(', ', $recepients['cc']) . "\n";
		}
		$header.= "Reply-To: {$options['from']['email']}\n";
		$header.= "Errors-To: {$options['from']['email']}\n";
		$header.= "MIME-Version: 1.0\n";
		$header.= "X-Email-Notification: {$x_email_notification}\n";
		// generating body for no attachment and a single message
		if (empty($options['attachments']) && count($options['message']) == 1) {
			$part = reset($options['message']);
			$header.= "Content-Type: {$part['type']};\n charset=\"{$part['charset']}\"\n";
			$header.= "Content-Transfer-Encoding: {$part['encoding']}\n";
			$body = $part['data'];
		} else {
			// has attachments or multiple messages
			$text = "\nThis is a multi-part message in MIME format.\n\n";
			// body variables
			$body_text = "";
			$body_boundary = '3>---boundary-{' . $crypt->hash(mt_rand()) . '}---<3';
			$body_header = "";
			$body_header.= "Content-Type: multipart/alternative;\n  boundary=\"$body_boundary\"\n";
			$body_header.= "Content-Transfer-Encoding: 7bit\n";
			$body_header.= "Content-Disposition: inline\n";
			// going though messages
			foreach ($options['message'] as $part) {
				$body_text.= '--' . $body_boundary . "\n";
				$body_text.= "Content-Type: {$part['type']};\n  charset=\"{$part['charset']}\"\n";
				$body_text.= "Content-Transfer-Encoding: {$part['encoding']}\n\n";
				$body_text.= $this->encode_part($part) . "\n\n";
			}
			$body_text.= "\n--{$body_boundary}--\n";
			// if we have attachments
			if (!empty($options['attachments'])) {
				$attachment_boundary = '3>---boundary-{' . $crypt->hash(mt_rand()) . '}---<3';
				$header.= "Content-Type: multipart/mixed;\n  boundary=\"{$attachment_boundary}\"";
				$text.= "--{$attachment_boundary}\n{$body_header}\n";
				$text.= $body_text;
				// going though them
				foreach ($options['attachments'] as $v) {
					$text.= "--{$attachment_boundary}\n";
					$text.= "Content-Type: {$v['type']};\n  name=\"{$v['name']}\"\n";
					$text.= "Content-Transfer-Encoding: base64\n";
					$text.= "Content-Disposition: attachment;\n  filename=\"{$v['name']}\"\n\n";
					$text.= $this->encode_part(['data' => $v['data'], 'encoding' => 'base64']);
				}
				$text .= "\n--{$attachment_boundary}--\n";
			} else {
				$header.= $body_header;
				$text.= $body_text;
			}
			$body = $text;
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
	 * @param boolean $wrap
	 * @return string
	 * @throws Exception
	 */
	private function encode_part($data, $wrap = true) {
		switch ($data['encoding']) {
			case 'base64':
				$data['data'] = base64_encode($data['data']);
				if ($wrap) {
					$data['data'] = chunk_split($data['data'], 68, "\n");
				}
				break;
			case 'quoted-printable':
				$data['data'] = quoted_printable_encode($data['data']);
				break;
			case '7bit':
			case '8bit':
			case 'binary':
				break;
			default:
				throw new Exception('Unsupported content-transfer-encoding: ' . $data['encoding']);
		}
		return $data['data'];
	}
}