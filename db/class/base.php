<?php

class numbers_backend_db_class_base {

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
	 * Connection details
	 *
	 * @var type
	 */
	public $connect_options;

	/**
	 * Commit status of the transaction
	 *
	 * @var int
	 */
	public $commit_status = 0;

	/**
	 * SQL keyword
	 *
	 * @var string 
	 */
	public $sql_keywords = [
		'like' => 'LIKE'
	];

	/**
	 * Prepare keys
	 *
	 * @param mixed $keys
	 * @return array
	 */
	public function prepare_keys($keys) {
		return $this->prepare_expression($keys, ', ', ['return_raw_array' => true]);
	}

	/**
	 * Prepare values for insert query
	 *
	 * @param array $options
	 * @return string
	 */
	public function prepare_values($options) {
		$result = [];
		foreach ($options as $k => $v) {
			$temp = explode(',', $k);
			$key = $temp[0];
			$operator = !empty($temp[1]) ? $temp[1] : '=';
			if (is_string($v)) {
				$result[] = "'" . $this->escape($v) . "'";
			} else if ((isset($temp[2]) && $temp[2] == '~~') || is_numeric($v)) {
				$result[] = $v;
			} else if (is_null($v)) {
				$result[] = 'NULL';
			} else {
				Throw new Exception('Unknown data type');
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
	public function prepare_expression($options, $delimiter = ', ', $options2 = []) {
		if (is_array($options)) {
			$temp = [];
			foreach ($options as $v) {
				$par = explode(',', $v);
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
	 * Convert an array into sql string
	 *
	 * @param  array $options
	 * @param  string $delimiter
	 * @return string
	 */
	public function prepare_condition($options, $delimiter = 'AND') {
		$result = '';
		if (is_array($options)) {
			$temp = [];
			$string = '';
			foreach ($options as $k => $v) {
				$par = explode(',', $k);
				// we use [comma] instead of comma in statements
				$key = str_replace('[comma]', ',', $par[0]);
				// todo: handle type casts (::)
				$operator = $par[1] ?? '=';
				$as_is = (isset($par[2]) && $par[2] == '~~') ? true : false;
				$string = $key;
				// special handling if we got an array
				if (is_array($v) && $operator == '=') {
					$operator = 'in';
				}
				// processing per operator
				$operator = strtolower($operator);
				switch ($operator) {
					// todo: add ALL and ANY operators
					/*
					if ($operator == 'ANY' || $operator == 'ALL') {
						$string = $v . ' = ' . $operator . '(' . $key . ')';
					}
					*/
					case 'in':
						$string.= ' IN(' . implode(', ', $this->escape_array($v, ['quotes' => true])) . ')';
						break;
					case 'like%':
						$v = '%' . $v . '%';
					case 'like':
						$v = "'" . $this->escape($v) . "'";
						$string.= ' ' . $this->sql_keywords['like'] . ' ' . $v;
						break;
					case 'fts':
						$temp2 = $this->full_text_search_query($v['fields'], $v['str']);
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
							Throw new Exception('Unknown data type');
						}
						$string.= ' ' . $operator . ' ' . $v;
				}
				$temp[] = $string;
			}
			$delimiter = ' ' . $delimiter . ' ';
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
	public function escape_array($value, $options = []) {
		$result = [];
		foreach ($value as $k => $v) {
			if (is_array($v)) {
				$result[$k] = $this->escape_array($v, $options);
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
	protected function error_overrides(& $result, $errno, $error) {
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
}