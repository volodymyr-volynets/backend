<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Oracle\Model\Sequence;

use Object\Function2;

class Nextval extends Function2
{
    public $db_link;
    public $db_link_flag;
    public $schema;
    public $name = 'nextval_extended';
    public $backend = 'Oracle';
    public $header = 'public2.nextval_extended(sequence_name in varchar2, tenant_id in number, module_id in number)';
    public $definition = 'CREATE OR REPLACE NONEDITIONABLE FUNCTION public2.nextval_extended(
    sequence_name in varchar2,
    tenant_id in number,
    module_id in number
) RETURN number IS
    PRAGMA AUTONOMOUS_TRANSACTION;
    result number := 0;
BEGIN
    SELECT a.sm_sequence_counter INTO result FROM public2.sm_sequence_extended a WHERE a.sm_sequence_name = sequence_name AND a.sm_sequence_tenant_id = tenant_id AND a.sm_sequence_module_id = module_id;
    result:= result + 1;
    UPDATE public2.sm_sequence_extended a SET a.sm_sequence_counter = result WHERE a.sm_sequence_name = sequence_name AND a.sm_sequence_tenant_id = tenant_id AND a.sm_sequence_module_id = module_id;
    COMMIT;
	RETURN result;
    EXCEPTION WHEN NO_DATA_FOUND THEN BEGIN
        INSERT INTO public2.sm_sequence_extended (
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
			a.sm_sequence_name sm_sequence_name,
			tenant_id sm_sequence_tenant_id,
			module_id sm_sequence_module_id,
			a.sm_sequence_description sm_sequence_description,
			a.sm_sequence_type sm_sequence_type,
			a.sm_sequence_prefix sm_sequence_prefix,
			a.sm_sequence_length sm_sequence_length,
			a.sm_sequence_suffix sm_sequence_suffix,
			1 sm_sequence_counter
		FROM public2.sm_sequences a
		WHERE a.sm_sequence_name = sequence_name;
        COMMIT;
		RETURN 1;
    END;
END;';
}
