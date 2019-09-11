<?php

namespace Numbers\Backend\Db\PostgreSQL\Model\Sequence;
class Nextval extends \Object\Function2 {
	public $db_link;
	public $db_link_flag;
	public $schema;
	public $module_code = 'SM';
	public $title = 'Get next value';
	public $name = 'nextval_extended';
	public $backend = 'PostgreSQL';
	public $header = 'nextval_extended(sequence_name character varying, tenant_id integer, module_id integer)';
	public $sql_version = '1.0.1';
	public $definition = 'CREATE OR REPLACE FUNCTION public.nextval_extended(sequence_name character varying, tenant_id integer, module_id integer)
 RETURNS bigint
 LANGUAGE plpgsql
 STRICT
AS $function$
DECLARE
	result bigint;
BEGIN
	SELECT sm_sequence_counter INTO result FROM sm_sequence_extended WHERE sm_sequence_name = sequence_name AND sm_sequence_tenant_id = tenant_id AND sm_sequence_module_id = module_id FOR UPDATE;
	IF FOUND THEN
		result:= result + 1;
		UPDATE sm_sequence_extended SET sm_sequence_counter = result WHERE sm_sequence_name = sequence_name AND sm_sequence_tenant_id = tenant_id AND sm_sequence_module_id = module_id;
	ELSE
		INSERT INTO sm_sequence_extended (
			sm_sequence_name,
			sm_sequence_tenant_id,
			sm_sequence_module_id,
			sm_sequence_description,
			sm_sequence_type,
			sm_sequence_prefix,
			sm_sequence_length,
			sm_sequence_suffix,
			sm_sequence_counter
		)
		SELECT
			sm_sequence_name sm_sequence_name,
			tenant_id sm_sequence_tenant_id,
			module_id sm_sequence_module_id,
			sm_sequence_description sm_sequence_description,
			sm_sequence_type sm_sequence_type,
			sm_sequence_prefix sm_sequence_prefix,
			sm_sequence_length sm_sequence_length,
			sm_sequence_suffix sm_sequence_suffix,
			1 sm_sequence_counter
		FROM sm_sequences
		WHERE sm_sequence_name = sequence_name;
		result:= 1;
	END IF;
	RETURN result;
END;
$function$';
}