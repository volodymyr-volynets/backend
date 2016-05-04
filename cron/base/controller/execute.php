<?php

class numbers_backend_cron_base_controller_execute {

	public function action_index() {
		// clear buffer
		helper_ob::clean_all();
		// validating
		do {
			$options = application::get('flag.numbers.backend.cron.base');
			// token
			if (!empty($options['token']) && request::input('token') != $options['token']) {
				break;
			}
			// ip
			if (!empty($options['ip']) && !in_array(request::ip(), $options['ip'])) {
				break;
			}
			// get date parts
			$date_parts = format::now('parts');
			print_r($date_parts);
			echo "GOOD\n";
		} while(0);
		// we need to validate token
		//$token = request::input('token');
		echo "OK\n";
		// exit
		exit;
	}
}