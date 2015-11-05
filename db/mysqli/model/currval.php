<?php

class numbers_backend_db_mysqli_model_currval extends object_function {
	public $db_link;
	public $db_link_flag;
	public $function_name = "currval";
	public $function_sql = [
		'mysqli' => [
			'header' =>
'CREATE FUNCTION currval(sequence_name varchar(50))
RETURNS bigint
CONTAINS SQL
',
			'body' =>
'BEGIN
	DECLARE value bigint;
	SET value = 0;
	SELECT sm_sequence_count INTO value FROM sm_sequences WHERE sm_sequence_name = sequence_name;
	RETURN value;
END',
			'footer' => ';'
		]
	];
}