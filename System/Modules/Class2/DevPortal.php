<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Class2;

use Object\Override\Data;

class DevPortal extends Data
{
    /**
     * Data
     *
     * @var array
     */
    public $data = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        // we need to handle overrrides
        parent::overrideHandle($this);
    }
}
