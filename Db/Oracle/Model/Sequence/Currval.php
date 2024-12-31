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

class Currval extends Function2
{
    public $db_link;
    public $db_link_flag;
    public $schema;
    public $name = 'currval_extended';
    public $backend = 'Oracle';
    public $header = 'public2.currval_extended(sequence_name in varchar2, tenant_id in number, module_id in number)';
    public $definition = 'CREATE OR REPLACE NONEDITIONABLE FUNCTION public2.currval_extended(
    sequence_name in varchar2,
    tenant_id in number,
    module_id in number
) RETURN number IS
    result number;
BEGIN
    SELECT sm_sequence_counter INTO result FROM sm_sequence_extended WHERE sm_sequence_name = sequence_name AND sm_sequence_tenant_id = tenant_id AND sm_sequence_module_id = module_id;
    RETURN result;
	EXCEPTION WHEN NO_DATA_FOUND THEN BEGIN
		RETURN NULL;
	END;
END;';
}
