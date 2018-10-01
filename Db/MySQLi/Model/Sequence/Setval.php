<?php

namespace Numbers\Backend\Db\MySQLi\Model\Sequence;
class Setval extends \Object\Function2 {
	public $db_link;
	public $db_link_flag;
	public $schema;
	public $name = 'setval';
	public $backend = 'MySQLi';
	public $header = 'setval(sequence_name varchar(255), sequence_counter bigint)';
	public $sql_version = '1.0.0';
	public $definition = 'CREATE FUNCTION setval(sequence_name varchar(255), sequence_counter bigint) RETURNS BIGINT
READS SQL DATA
DETERMINISTIC
BEGIN
	UPDATE sm_sequences SET sm_sequence_counter = sequence_counter WHERE sm_sequence_name = sequence_name;
	RETURN sequence_counter;
END;';
}