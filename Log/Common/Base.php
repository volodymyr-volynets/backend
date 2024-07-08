<?php

namespace Numbers\Backend\Log\Common;
class Base {

	/**
	 * Link to logging
	 *
	 * @var string
	 */
	public string $log_link;

	/**
	 * Options
	 *
	 * @var array
	 */
	public array $options = [];

	/**
	 * Constructor
	 *
	 * @param string $db_link
	 * @param array $options
	 */
	public function __construct(string $log_link, array $options = []) {
		$this->log_link = $log_link;
		$this->options = $options;
	}
}