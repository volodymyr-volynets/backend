<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Extension\PostgreSQL\PostGIS\Model\Geo;

use Object\Extension;

class PostGIS extends Extension
{
    public $db_link;
    public $db_link_flag;
    public $module_code = 'SM';
    public $title = 'Postgis';
    public $schema = 'extensions';
    public $name = 'postgis';
    public $backend = 'PostgreSQL';
}
