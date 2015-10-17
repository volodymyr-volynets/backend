<?php

interface numbers_backend_db_interface_base {
	public function connect($options);
	public function begin();
	public function escape($value);
	public function escape_array($value);
	public function query($sql, $key = null, $options = []);
	public function commit();
	public function rollback();
	public function save($table, $data, $keys);
	public function insert($table, $rows);
	//public function remove($table, $where);
	public function close();
}