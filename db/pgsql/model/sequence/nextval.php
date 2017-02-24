<?php

class numbers_backend_db_pgsql_model_sequence_nextval extends object_function {
	public $db_link;
	public $db_link_flag;
	public $schema;
	public $name = 'nextval_extended';
	public $backend = 'pgsql';
	public $header = 'nextval_extended(sequence_name character varying, tenant_id integer, module_id integer)';
	public $definition = 'CREATE OR REPLACE FUNCTION public.nextval_extended(sequence_name character varying, tenant_id integer, module_id integer)
 RETURNS bigint
 LANGUAGE plpgsql
 STRICT
AS $function$
DECLARE
	result bigint;
BEGIN
	SELECT sm_seqextend_counter INTO result FROM sm_sequence_extended WHERE sm_seqextend_name = sequence_name AND sm_seqextend_tenant_id = tenant_id AND sm_seqextend_module_id = module_id;
	IF FOUND THEN
		result:= result + 1;
		UPDATE sm_sequence_extended SET sm_seqextend_counter = result WHERE sm_seqextend_name = sequence_name AND sm_seqextend_tenant_id = tenant_id AND sm_seqextend_module_id = module_id;
	ELSE
		INSERT INTO sm_sequence_extended (
			sm_seqextend_name,
			sm_seqextend_tenant_id,
			sm_seqextend_module_id,
			sm_seqextend_description,
			sm_seqextend_type,
			sm_seqextend_prefix,
			sm_seqextend_length,
			sm_seqextend_suffix,
			sm_seqextend_counter
		)
		SELECT
			sm_sequence_name sm_seqextend_name,
			tenant_id sm_seqextend_tenant_id,
			module_id sm_seqextend_module_id,
			sm_sequence_description sm_seqextend_description,
			sm_sequence_type sm_seqextend_type,
			sm_sequence_prefix sm_seqextend_prefix,
			sm_sequence_length sm_seqextend_length,
			sm_sequence_suffix sm_seqextend_suffix,
			1 sm_seqextend_counter
		FROM sm_sequences
		WHERE sm_sequence_name = sequence_name;
		result:= 1;
	END IF;
	RETURN result;
END;
$function$';
}