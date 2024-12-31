<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Oracle\Model\Schema;

use Object\Schema;

class Public2 extends Schema
{
    public $db_link;
    public $db_link_flag;
    public $title = 'Public';
    public $schema = 'public2';
    public $name = 'public2';
    public $backend = 'Oracle';
}
