<?php

interface numbers_backend_db_interface_base {
	public function connect($options);
	public function begin();
	public function escape($value);
	public function escape_array($value);
	public function query($sql, $key = null, $options = []);
	public function commit();
	public function rollback();
	public function save($table, $data, $keys, $options = []);
	public function insert($table, $rows, $keys = null, $options = []);
	public function update($table, $data, $keys, $options = []);
	public function delete($table, $data, $keys, $options = []);
	public function sequence($sequence_name, $sequence_table, $type);
	public function close();
}