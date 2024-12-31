<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Log\Db\Model;

class LogsGeneratedYear2027 extends Logs
{
    /**
     * Name
     *
     * @var string
     */
    public $name = 'sm_logs_generated_year_2027';

    /**
     * Constraints
     *
     * @var array
     */
    public $constraints = array(
  'sm_logs_generated_year_2027_pk' =>
  array(
    'type' => 'pk',
    'columns' =>
    array(
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
    public array $filter = array(
  'sm_log_year' => 2027,
);
}
