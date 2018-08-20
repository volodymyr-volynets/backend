<?php

namespace Numbers\Backend\Db\Test\Model\Trigger;
class UpdateEmployeeTriggerPostgreSQL extends \Object\Trigger {
	public $db_link;
	public $db_link_flag;
	public $schema;
	public $name = 'sm_test_employees_log_last_name_changes_trigger';
	public $backend = 'PostgreSQL';
	public $full_table_name = 'sm_test_employees';
	public $header = 'sm_employees_log_last_name_changes_trigger()';
	public $definition = 'CREATE TRIGGER sm_employees_log_last_name_changes_trigger
  BEFORE UPDATE
  ON sm_test_employees
  FOR EACH ROW
  EXECUTE PROCEDURE sm_employees_log_last_name_changes();';
}