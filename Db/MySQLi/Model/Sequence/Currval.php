<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\MySQLi\Model\Sequence;

use Object\Function2;

class Currval extends Function2
{
    public $db_link;
    public $db_link_flag;
    public $schema;
    public $name = 'currval';
    public $backend = 'MySQLi';
    public $header = 'currval(sequence_name varchar(255))';
    public $sql_version = '1.0.0';
    public $definition = 'CREATE FUNCTION currval(sequence_name varchar(255)) RETURNS BIGINT
READS SQL DATA
DETERMINISTIC
BEGIN
	DECLARE result BIGINT;
	SELECT sm_sequence_counter INTO result FROM sm_sequences WHERE sm_sequence_name = sequence_name;
	RETURN result;
END;';
}
