<?php

abstract class numbers_backend_ip_class_base {

	/**
	 * Link
	 *
	 * @var string
	 */
	public $ip_link;

	/**
	 * Options
	 *
	 * @var array
	 */
	public $options = [];

	/**
	 * Constructor
	 *
	 * @param string $db_link
	 * @param array $options
	 */
	public function __construct(string $ip_link, array $options = []) {
		$this->ip_link = $ip_link;
		$this->options = $options;
		if (!empty($this->options['cache_link'])) {
			// default expiration is 1 day
			$this->options['expire'] = $this->options['expire'] ?? 86400;
		}
	}

	/**
	 * Get
	 *
	 * @param string $ip
	 * @return array
	 */
	abstract public function get(string $ip) : array;
}