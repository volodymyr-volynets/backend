<?php

class numbers_backend_db_mysqli_model_nextval extends object_function {
	public $db_link;
	public $db_link_flag;
	public $name = "nextval";
	public $function_sql = [
		'mysqli' => [
			'header' =>
'CREATE FUNCTION nextval(sequence_name varchar(255))
RETURNS bigint
CONTAINS SQL
',
			'body' =>
'BEGIN
	UPDATE sm_sequences SET sm_sequence_count = last_insert_id(sm_sequence_count + 1) WHERE sm_sequence_name = sequence_name;
	RETURN last_insert_id();
END',
			'footer' => ';'
		]
	];
}