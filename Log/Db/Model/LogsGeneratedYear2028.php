<?php

namespace Numbers\Backend\Log\Db\Model;
class LogsGeneratedYear2028 extends \Numbers\Backend\Log\Db\Model\Logs {
	/**
	 * Name
	 *
	 * @var string
	 */
	public $name = 'sm_logs_generated_year_2028';

	/**
	 * Constraints
	 *
	 * @var array
	 */
	public $constraints = array (
  'sm_logs_generated_year_2028_pk' => 
  array (
    'type' => 'pk',
    'columns' => 
    array (
      0 => 'sm_log_id',
    ),
  ),
);

	/**
	 * Is period table
	 *
	 * @var bool
	 */
	public bool $is_period_table = true;

	/**
	 * Filter
	 *
	 * @var array
	 */
	public array $filter = array (
  'sm_log_year' => 2028,
);
}