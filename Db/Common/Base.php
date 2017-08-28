<?php

namespace Numbers\Backend\Db\Common;
class Base {

	/**
	 * Link to database
	 *
	 * @var string
	 */
	public $db_link;

	/**
	 * Database resource
	 *
	 * @var resource
	 */
	private $db_resource;

	/**
	 * Options
	 *
	 * @var array
	 */
	public $options = [];

	/**
	 * Commit status of the transaction
	 *
	 * @var int
	 */
	public $commit_status = 0;

	/**
	 * SQL keywords
	 *
	 * @var array
	 */
	public $sql_keywords = [
		'like' => 'LIKE'
	];

	/**
	 * SQL keywords overrides
	 *
	 * @var array
	 */
	public $sql_keywords_overrides = [];

	/**
	 * Constructor
	 *
	 * @param string $db_link
	 * @param array $options
	 */
	public function __construct(string $db_link, array $options = []) {
		$this->db_link = $db_link;
		$this->options = $options;
		// override keyworkds
		foreach ($this->sql_keywords_overrides as $k => $v) {
			$this->sql_keywords[$k] = $v;
		}
	}

	/**
	 * Error Overrides
	 *
	 * @var array
	 */
	public $error_overrides = [];

	/**
	 * Error Overrides
	 *
	 * @param arary $result
	 * @param string $errno
	 * @param string $error
	 */
	protected function errorOverrides(& $result, $errno, $error) {
		$result['errno'] = trim($errno . '');
		if (isset($this->error_overrides[$result['errno']])) {
			$result['error'][] = $this->error_overrides[$result['errno']];
			$result['error_original'][] = $error;
		} else {
			$temp = 'Db Link ' . $this->db_link . ': Errno: ' . $result['errno'] . ': ' . $error;
			$result['error'][] = $temp;
			// we need to trown en error if this happens
			trigger_error($temp);
			error_log('Query error: ' . implode(' ', $result['error']) . ' [' . $result['sql'] . ']');
		}
	}

	/**
	 * Prepare keys
	 *
	 * @param mixed $keys
	 * @return array
	 */
	public function prepareKeys($keys) {
		return $this->prepareExpression($keys, ', ', ['return_raw_array' => true]);
	}

	/**
	 * Prepare values for insert query
	 *
	 * @param array $options
	 * @return string
	 */
	public function prepareValues($options) {
		$result = [];
		foreach ($options as $k => $v) {
			$temp = explode(';', $k);
			$key = $temp[0];
			$operator = !empty($temp[1]) ? $temp[1] : '=';
			if (is_string($v)) {
				$result[] = "'" . $this->escape($v) . "'";
			} else if ((isset($temp[2]) && $temp[2] == '~~') || is_numeric($v)) {
				$result[] = $v;
			} else if (is_null($v)) {
				$result[] = 'NULL';
			} else {
				Throw new \Exception("Unknown data type: {$k}");
			}
		}
		return implode(', ', $result);
	}

	/**
	 * Prepare expression for insert query
	 *
	 * @param mixed $options
	 * @param mixed $delimiter
	 * @param array $options2
	 * @return mixed
	 */
	public function prepareExpression($options, $delimiter = ', ', $options2 = []) {
		if (is_array($options)) {
			$temp = [];
			foreach ($options as $v) {
				$par = explode(';', $v);
				$temp[] = $par[0];
			}
			// if we need raw array
			if (!empty($options2['return_raw_array'])) {
				return $temp;
			}
			$options = implode($delimiter, $temp);
		}
		return $options;
	}

	/**
	 * Convert an array into SQL string
	 *
	 * @param  array $options
	 * @param  string $delimiter
	 * @return string
	 */
	public function prepareCondition($options, $delimiter = 'AND') {
		$result = '';
		if (is_array($options)) {
			$temp = [];
			$string = '';
			foreach ($options as $k => $v) {
				$par = explode(';', $k);
				// todo: handle type casts (::)
				$operator = $par[1] ?? '=';
				$as_is = (isset($par[2]) && $par[2] == '~~') ? true : false;
				$string = $par[0];
				// special handling for array and nulls
				if ($operator == '=') {
					if (is_array($v)) {
						$operator = 'IN';
					} else if (is_null($v) && strpos($delimiter, ',') === false) {
						$operator = 'IS';
					}
				}
				// processing per operator
				$operator = strtoupper($operator);
				switch ($operator) {
					// todo: add ALL and ANY operators
					/*
					if ($operator == 'ANY' || $operator == 'ALL') {
						$string = $v . ' = ' . $operator . '(' . $key . ')';
					}
					*/
					case 'IN':
						// we can pass SQL queries into IN
						if (is_string($v)) {
							$string.= ' IN(' . $v . ')';
						} else {
							$string.= ' IN(' . implode(', ', $this->escapeArray($v, ['quotes' => true])) . ')';
						}
						break;
					case 'LIKE%':
						$v = '%' . $v . '%';
					case 'LIKE':
						$v = "'" . $this->escape($v) . "'";
						$string.= ' ' . $this->sql_keywords['like'] . ' ' . $v;
						break;
					case 'FTS':
						$temp2 = $this->fullTextSearchQuery($v['fields'], $v['str']);
						if (empty($temp2['where'])) {
							continue;
						}
						$string = $temp2['where'];
						break;
					default:
						if ($as_is) {
							// do not remove it !!!
						} else if (is_string($v)) {
							$v = "'" . $this->escape($v) . "'";
						} else if (is_numeric($v)) {
							// no changes
						} else if (is_null($v)) {
							$v = 'NULL';
						} else {
							Throw new \Exception('Unknown data type');
						}
						$string.= ' ' . $operator . ' ' . $v;
				}
				$temp[] = $string;
			}
			// fix delimiter
			$delimiter = strtoupper($delimiter);
			if (in_array($delimiter, ['AND', 'OR'])) {
				$delimiter = ' ' . $delimiter . ' ';
			}
			$result = implode($delimiter, $temp);
		} else if (!empty($options)) {
			$result = $options;
		}
		return $result;
	}

	/**
	 * Escape array
	 *
	 * @param array $value
	 * @param array $options
	 *		boolean quotes
	 * @return array
	 */
	public function escapeArray($value, $options = []) {
		$result = [];
		foreach ($value as $k => $v) {
			if (is_array($v)) {
				$result[$k] = $this->escapeArray($v, $options);
			} else if (is_string($v) && !empty($options['quotes'])) {
				$result[$k] = "'" . $this->escape($v) . "'";
			} else if (is_string($v) && empty($options['quotes'])) {
				$result[$k] = $this->escape($v);
			} else if (is_numeric($v)) {
				$result[$k] = $v;
			} else if (is_null($v)) {
				$result[$k] = 'NULL';
			}
		}
		return $result;
	}

	/**
	 * Delete
	 *
	 * @param string $table
	 * @param array $data
	 * @param mixed $keys
	 * @param array $options
	 *		where - primary key
	 * @return array
	 */
	public function delete($table, $data, $keys, $options = []) {
		$keys = array_fix($keys);
		// where clause
		if (!empty($options['where'])) {
			$where = $options['where'];
		} else {
			$where = pk($keys, $data, true);
		}
		// build a query
		$query = \Numbers\Backend\Db\Common\Query\Builder::quick($this->db_link)
			->delete()
			->from($table)
			->where('AND', $this->prepareCondition($where, 'AND'));
		/*
		if (!empty($options['returning'])) {
			$query->returning();
		}
		*/
		return $query->query($this->prepareKeys($keys), $options);
	}

	/**
	 * Update
	 *
	 * @param string $table
	 * @param array $data
	 * @param mixed $keys
	 * @param array $options
	 *		where - primary key
	 * @return array
	 */
	public function update($table, $data, $keys, $options = []) {
		$keys = array_fix($keys);
		// where clause
		if (!empty($options['where'])) {
			$where = $options['where'];
		} else {
			$where = pk($keys, $data, true);
		}
		// build a query
		$query = \Numbers\Backend\Db\Common\Query\Builder::quick($this->db_link)
			->update()
			->from($table)
			->set($data)
			->where('AND', $this->prepareCondition($where, 'AND'));
		/*
		if (!empty($options['returning'])) {
			$query->returning();
		}
		*/
		return $query->query($this->prepareKeys($keys), $options);
	}

	/**
	 * Insert
	 *
	 * @param string $table
	 * @param array $data
	 * @param mixed $keys
	 * @return array
	 */
	public function insert($table, $data, $keys = null, $options = []) {
		// build a query
		$query = \Numbers\Backend\Db\Common\Query\Builder::quick($this->db_link)
			->insert()
			->from($table)
			->columns(array_keys(current($data)))
			->values($data);
		/*
		if (!empty($options['returning'])) {
			$query->returning();
		}
		*/
		return $query->query($this->prepareKeys($keys), $options);
	}

	/**
	 * Save row to database
	 *
	 * @param string $table
	 * @param array $data
	 * @param mixed $keys
	 * @param array $options
	 *		where - primary key
	 * @return boolean
	 */
	public function save($table, $data, $keys, $options = []) {
		$keys = array_fix($keys);
		// where clause
		if (!empty($options['where'])) {
			$where = $options['where'];
		} else {
			$temp = $data;
			$where = pk($keys, $temp, true);
		}
		// start transaction
		$this->begin();
		// if we have full primary key
		$full_pk = true;
		$row_found = false;
		foreach ($keys as $v) if (!array_key_exists($v, $where)) $full_pk = false;
		if ($full_pk) {
			$result = \Numbers\Backend\Db\Common\Query\Builder::quick($this->db_link)
				->select()
				->from($table)
				->where('AND', $this->prepareCondition($where, 'AND'))
				->query();
			if (!$result['success']) {
				$this->rollback();
				return $result;
			} else if ($result['num_rows']) {
				$row_found = true;
			}
		}
		// update the record
		if ($row_found) {
			$result = $this->update($table, $data, $keys, $options);
			$result['inserted'] = false;
		} else { // insert
			$result = $this->insert($table, [$data], $keys, $options);
			if ($result['success']) $result['inserted'] = true;
		}
		if ($result['success']) {
			$this->commit();
		} else {
			$this->rollback();
		}
		return $result;
	}
}