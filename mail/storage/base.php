<?php

class numbers_backend_mail_storage_base implements numbers_backend_mail_interface_storage {

	/**
	 * Store an email
	 *
	 * @param array $options
	 * @return array
	 */
	public function store($options) {
		$result = [
			'success' => false,
			'error' => []
		];
		return $result;
	}
}