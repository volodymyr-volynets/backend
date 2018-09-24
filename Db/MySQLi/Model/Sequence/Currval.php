<?php

namespace Numbers\Backend\Db\MySQLi\Model\Sequence;
class Currval extends \Object\Function2 {
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