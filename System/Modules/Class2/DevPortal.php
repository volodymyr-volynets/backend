<?php

namespace Numbers\Backend\System\Modules\Class2;
class DevPortal extends \Object\Override\Data {

	/**
	 * Data
	 *
	 * @var array
	 */
	public $data = [];

	/**
	 * Constructor
	 */
	public function __construct() {
		// we need to handle overrrides
		parent::overrideHandle($this);
	}
}