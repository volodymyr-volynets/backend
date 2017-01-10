<?php

class numbers_backend_cache_class_base {

	/**
	 * Cache link
	 *
	 * @var string
	 */
	public $cache_link;

	/**
	 * Cache options
	 *
	 * @var array
	 */
	public $options;

	/**
	 * Cache key, used for multi tenant/db systems
	 *
	 * @var string
	 */
	public $cache_key;
}