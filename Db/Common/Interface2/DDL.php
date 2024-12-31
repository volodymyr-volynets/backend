<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common\Interface2;

interface DDL
{
    public function columnSqlType($column);
    public function loadSchema($db_link);
    public function renderSql($type, $data, $options = [], & $extra_comments = null);
}
