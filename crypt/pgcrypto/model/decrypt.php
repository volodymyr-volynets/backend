<?php

class numbers_backend_crypt_pgcrypto_model_decrypt extends object_function {
	public $db_link;
	public $db_link_flag;
	public $function_name = "sm.decrypt";
	public $function_sql = [
		'pgsql' => [
			'definition' => 'sm.encrypt(text)',
			'header' => '',
			'body' =>
'CREATE OR REPLACE FUNCTION sm.decrypt(data bytea)
 RETURNS text
 LANGUAGE plpgsql
AS $function$
BEGIN
	RETURN pgp_sym_decrypt(data, current_setting(\'sm.numbers.crypt.key\'), current_setting(\'sm.numbers.crypt.options\'));
END;
$function$
',
			'footer' => ''
		]
	];
}