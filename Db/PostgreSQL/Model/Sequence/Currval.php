<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\PostgreSQL\Model\Sequence;

use Object\Function2;

class Currval extends Function2
{
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
