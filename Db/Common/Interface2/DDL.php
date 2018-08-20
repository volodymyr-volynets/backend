<?php

namespace Numbers\Backend\Db\Common\Interface2;
interface DDL {
	public function columnSqlType($column);
	public function loadSchema($db_link);
	public function renderSql($type, $data, $options = [], & $extra_comments = null);
}