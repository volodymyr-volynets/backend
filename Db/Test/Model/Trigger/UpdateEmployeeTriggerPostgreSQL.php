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

class UpdateEmployeeTriggerPostgreSQL extends Trigger
{
    public $db_link;
    public $db_link_flag;
    public $schema;
    public $name = 'sm_test_employees_log_last_name_changes_trigger';
    public $backend = 'PostgreSQL';
    public $full_table_name = 'sm_test_employees';
    public $header = 'sm_employees_log_last_name_changes_trigger()';
    public $sql_version = '1.0.0';
    public $definition = 'CREATE TRIGGER sm_test_employees_log_last_name_changes_trigger BEFORE UPDATE ON sm_test_employees FOR EACH ROW EXECUTE PROCEDURE sm_employees_log_last_name_changes();';
}
