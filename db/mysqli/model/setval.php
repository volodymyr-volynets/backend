<?php

class numbers_backend_db_mysqli_model_setval extends object_function {
	public $db_link;
	public $db_link_flag;
	public $name = "setval";
	public $function_sql = [
		'mysqli' => [
			'header' =>
'CREATE FUNCTION setval(sequence_name varchar(255), value int, nextval tinyint)
RETURNS bigint
CONTAINS SQL
',
			'body' =>
'BEGIN
	UPDATE sm_sequences SET sm_sequence_count = value WHERE sm_sequence_name = sequence_name;
	IF(nextval IS NULL OR nextval = 0) THEN
		RETURN value;
	ELSE
		RETURN nextval(sequence_name);
	END IF;
END',
			'footer' => ';'
		]
	];
}