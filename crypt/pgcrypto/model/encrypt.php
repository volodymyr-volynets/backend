<?php

class numbers_backend_crypt_pgcrypto_model_encrypt extends object_function {
	public $db_link;
	public $db_link_flag;
	public $function_name = "sm.encrypt";
	public $function_sql = [
		'pgsql' => [
			'definition' => 'sm.encrypt(text)',
			'header' => '',
			'body' =>
'CREATE OR REPLACE FUNCTION sm.encrypt(data text)
 RETURNS bytea
 LANGUAGE plpgsql
AS $function$
BEGIN
	RETURN pgp_sym_encrypt(data, current_setting(\'sm.numbers.crypt.key\'), current_setting(\'sm.numbers.crypt.options\'));
END;
$function$
',
			'footer' => ''
		]
	];
}