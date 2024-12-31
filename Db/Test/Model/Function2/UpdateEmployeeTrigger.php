<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Test\Model\Function2;

use Object\Function2;

class UpdateEmployeeTrigger extends Function2
{
    public $db_link;
    public $db_link_flag;
    public $schema;
    public $name = 'sm_employees_log_last_name_changes';
    public $backend = 'PostgreSQL';
    public $header = 'sm_employees_log_last_name_changes()';
    public $sql_version = '1.0.0';
    public $definition = 'CREATE OR REPLACE FUNCTION public.sm_employees_log_last_name_changes()
 RETURNS trigger
 LANGUAGE plpgsql
AS $function$
BEGIN
	IF NEW.last_name <> OLD.last_name THEN
		INSERT INTO sm_test_employee_audits(employee_id,last_name,changed_on)
		VALUES(OLD.id,OLD.last_name,now());
	END IF;
	RETURN NEW;
END;
$function$';
}
