<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Oracle\Model\Function2;

use Object\Function2;

class Now extends Function2
{
    public $db_link;
    public $db_link_flag;
    public $schema;
    public $name = 'now';
    public $backend = 'Oracle';
    public $header = 'public2.now() RETURN timestamp(6)';
    public $definition = "CREATE OR REPLACE NONEDITIONABLE FUNCTION public2.now RETURN timestamp IS
   result timestamp(6);
BEGIN
	SELECT LOCALTIMESTAMP INTO result FROM dual;
    RETURN result;
END;";
}
