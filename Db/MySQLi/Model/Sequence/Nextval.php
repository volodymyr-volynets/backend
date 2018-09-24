<?php

namespace Numbers\Backend\Db\MySQLi\Model\Sequence;
class Nextval extends \Object\Function2 {
	public $db_link;
	public $db_link_flag;
	public $schema;
	public $name = 'nextval';
	public $backend = 'MySQLi';
	public $header = 'nextval(sequence_name varchar(255))';
	public $sql_version = '1.0.0';
	public $definition = 'CREATE FUNCTION nextval(sequence_name varchar(255)) RETURNS BIGINT
READS SQL DATA
DETERMINISTIC
BEGIN
	UPDATE sm_sequences SET sm_sequence_counter = last_insert_id(sm_sequence_counter + 1) WHERE sm_sequence_name = sequence_name;
	RETURN last_insert_id();
END;';
}