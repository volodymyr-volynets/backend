<?php

namespace Numbers\Backend\System\Modules\Model\Notification;
class Sender {

	/**
	 * Send notification
	 *
	 * @param string $feature_code
	 * @param array $options
	 * @return array
	 */
	public function send(string $feature_code, array $options = []) : array {
		$model = new \Numbers\Backend\System\Modules\Model\Notifications();
		$data = $model->get([
			'where' => [
				'sm_notification_code' => $feature_code
			],
			'single_row' => true
		]);
		$subject = i18n($feature_code . '::subject', $data['sm_notification_subject'], ['replace' => $options['replace']['subject'] ?? null]);
		$body = i18n($feature_code . '::body', $data['sm_notification_body'], ['replace' => $options['replace']['body'] ?? null]);
		// todo log notifications
		return \Mail::sendSimple($options['email'], $subject, $body);
	}
}