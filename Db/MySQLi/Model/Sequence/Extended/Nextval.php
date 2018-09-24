<?php

namespace Numbers\Backend\Db\MySQLi\Model\Sequence\Extended;
class Nextval extends \Object\Function2 {
	public $db_link;
	public $db_link_flag;
	public $schema;
	public $name = 'nextval_extended';
	public $backend = 'MySQLi';
	public $header = 'nextval_extended(sequence_name varchar(255), tenant_id integer, module_id integer)';
	public $sql_version = '1.0.0';
	public $definition = 'CREATE FUNCTION nextval_extended(sequence_name varchar(255), tenant_id integer, module_id integer) RETURNS BIGINT
READS SQL DATA
DETERMINISTIC
BEGIN
	DECLARE result BIGINT;
	SELECT sm_sequence_counter INTO result FROM sm_sequence_extended WHERE sm_sequence_name = sequence_name AND sm_sequence_tenant_id = tenant_id AND sm_sequence_module_id = module_id;
	IF result IS NOT NULL THEN
		SET result = result + 1;
		UPDATE sm_sequence_extended SET sm_sequence_counter = result WHERE sm_sequence_name = sequence_name AND sm_sequence_tenant_id = tenant_id AND sm_sequence_module_id = module_id;
	ELSE
		INSERT INTO sm_sequence_extended (
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
			sm_sequence_name sm_sequence_name,
			tenant_id sm_sequence_tenant_id,
			module_id sm_sequence_module_id,
			sm_sequence_description sm_sequence_description,
			sm_sequence_type sm_sequence_type,
			sm_sequence_prefix sm_sequence_prefix,
			sm_sequence_length sm_sequence_length,
			sm_sequence_suffix sm_sequence_suffix,
			1 sm_sequence_counter
		FROM sm_sequences
		WHERE sm_sequence_name = sequence_name;
		SET result = 1;
	END IF;
	RETURN result;
END;';
}