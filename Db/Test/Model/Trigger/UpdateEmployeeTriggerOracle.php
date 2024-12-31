<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Test\Model\Trigger;

use Object\Trigger;

class UpdateEmployeeTriggerOracle extends Trigger
{
    public $db_link;
    public $db_link_flag;
    public $schema;
    public $name = 'sm_employees_log_last_name_changes_trigger';
    public $backend = 'Oracle';
    public $full_table_name = 'sm_test_employees';
    public $header = 'public2.sm_employees_log_last_name_changes_trigger()';
    public $sql_version = '1.0.0';
    public $definition = 'CREATE OR REPLACE NONEDITIONABLE TRIGGER public2.sm_employees_log_last_name_changes_trigger BEFORE UPDATE ON public2.sm_test_employees
FOR EACH ROW
BEGIN
	IF :NEW.last_name <> :OLD.last_name THEN
		INSERT INTO public2.sm_test_employee_audits(employee_id,last_name,changed_on)
		VALUES(:NEW.id,:OLD.last_name,public2.now());
	END IF;
END;';
}
