<?php

class numbers_backend_db_class_model_crypt_encrypt extends object_function {
	public $db_link;
	public $db_link_flag;
	public $function_name = "sm.encrypt";
	public $function_sql = [
		'pgsql' => [
			'definition' => 'sm.encrypt(text)',
			'header' => '',
			'body' =>
'CREATE OR REPLACE FUNCTION sm.encrypt(data text)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
	RETURN encrypt(data::bytea, current_setting(\'sm.numbers.crypt.key\')::bytea, \'aes\')::text;
END;
$function$
',
			'footer' => ''
		]
	];
}