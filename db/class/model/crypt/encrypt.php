<?php

class numbers_backend_db_class_model_crypt_encrypt extends object_function {
	public $db_link;
	public $db_link_flag;
	public $function_name = "numbers_crypt_encrypt";
	public $function_sql = [
		'pgsql' => [
			'definition' => 'public.numbers_crypt_encrypt(bytea)',
			'header' => '',
			'body' =>
'CREATE OR REPLACE FUNCTION numbers_crypt_encrypt(data bytea)
  RETURNS bytea AS
$BODY$
DECLARE
	crypt_key bytea;
BEGIN
	crypt_key := current_setting(\'numbers.crypt.key\')::bytea;
	RETURN encrypt(data, crypt_key, \'aes\');
END;
$BODY$
  LANGUAGE plpgsql VOLATILE
  COST 100;
',
			'footer' => ''
		]
	];
}