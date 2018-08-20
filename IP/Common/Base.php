<?php

namespace Numbers\Backend\IP\Common;
abstract class Base {

	/**
	 * Options
	 *
	 * @var array
	 */
	public $options = [];

	/**
	 * Constructor
	 *
	 * @param array $options
	 */
	public function __construct(array $options = []) {
		$this->options = $options;
	}

	/**
	 * Get
	 *
	 * @param string $ip
	 * @return array
	 */
	abstract public function get(string $ip) : array;
}