<?php

namespace Numbers\Backend\Db\PostgreSQL\Model\Sequence;
class Currval extends \Object\Function2 {
	public $db_link;
	public $db_link_flag;
	public $schema;
	public $module_code = 'SM';
	public $title = 'Get current value';
	public $name = 'currval_extended';
	public $backend = 'PostgreSQL';
	public $header = 'currval_extended(sequence_name character varying, tenant_id integer, module_id integer)';
	public $sql_version = '1.0.0';
	public $definition = 'CREATE OR REPLACE FUNCTION public.currval_extended(sequence_name character varying, tenant_id integer, module_id integer)
 RETURNS bigint
 LANGUAGE plpgsql
 STRICT
AS $function$
DECLARE
    result bigint;
BEGIN
	SELECT sm_sequence_counter INTO result FROM sm_sequence_extended WHERE sm_sequence_name = sequence_name AND sm_sequence_tenant_id = tenant_id AND sm_sequence_module_id = module_id;
	RETURN result;
END;
$function$';
}