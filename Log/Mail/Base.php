<?php

namespace Numbers\Backend\Log\Mail;
class Base extends \Numbers\Backend\Log\Common\Base {

/**
	 * Save
	 *
	 * @param array $data
	 * @return bool
	 */
	public function save(string $log_link, array $data) : array {
		return RESULT_SUCCESS;
	}
}