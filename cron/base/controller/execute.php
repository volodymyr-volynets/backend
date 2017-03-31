<?php

class numbers_backend_cron_base_controller_execute {

	public function actionIndex() {
		// clear buffer
		\Helper\Ob::cleanAll();
		// validating
		do {
			$options = Application::get('flag.numbers.backend.cron.base');
			// token
			if (!empty($options['token']) && \Request::input('token') != $options['token']) {
				break;
			}
			// ip
			if (!empty($options['ip']) && !in_array(\Request::ip(), $options['ip'])) {
				break;
			}
			// get date parts
			$date_parts = Format::now('parts');
			print_r($date_parts);
			echo "GOOD\n";
		} while(0);
		// we need to validate token
		//$token = \Request::input('token');
		echo "OK\n";
		// exit
		exit;
	}
}