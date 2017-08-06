<?php

namespace Numbers\Backend\Db\Common\Interface2;
interface Base {
	public function connect(array $options) : array;
	public function begin();
	public function escape($value);
	public function escapeArray($value);
	public function query(string $sql, $key = null, array $options = []) : array;
	public function commit();
	public function rollback();
	public function sequence($sequence_name, $type);
	public function close();
	public function fullTextSearchQuery($fields, $str, $operator = '&', $options = []);
	public function createTempTable($table, $columns, $pk = null, $options = []);
	public function sqlHelper($statement, $options = []);
	public function cast(string $column, string $type) : string;
	public function queryBuilderRender(\Numbers\Backend\Db\Common\Query\Builder $object) : array;
}