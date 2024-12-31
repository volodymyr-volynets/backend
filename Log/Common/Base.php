<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Log\Common;

class Base
{
    /**
     * Error types
     */
    public const ERROR_TYPES = [
        'Db Query (Slow)',
        'Error',
        'Exception (General)',
        'Exception (Middleware)',
        'Exception (Resourse Not Found)',
        'Request (Slow)',
        'Debug (Info)',
        'Debug (Warning)',
        'Debug (Error)',
    ];

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
    public function __construct(string $log_link, array $options = [])
    {
        $this->log_link = $log_link;
        $this->options = $options;
    }
}
