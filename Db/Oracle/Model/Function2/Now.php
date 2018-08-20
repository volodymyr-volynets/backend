<?php

namespace Numbers\Backend\Db\Oracle\Model\Function2;
class Now extends \Object\Function2 {
	public $db_link;
	public $db_link_flag;
	public $schema;
	public $name = 'now';
	public $backend = 'Oracle';
	public $header = 'public2.now() RETURN timestamp(6)';
	public $definition = "CREATE OR REPLACE NONEDITIONABLE FUNCTION public2.now RETURN timestamp IS
   result timestamp(6);
BEGIN
	SELECT LOCALTIMESTAMP INTO result FROM dual;
    RETURN result;
END;";
}