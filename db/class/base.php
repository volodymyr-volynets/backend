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
			} else if (is_array($v)) {
				$result[] = "'" . $this->prepare_array($v) . "'";
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
	 * Accepts an array of values and then returns delimited and comma separated list of
	 * value for use in an sql statement.
	 *
	 * @param array $options
	 * @return string
	 */
	public function prepare_array($options) {
		$result = [];
		if (empty($options))
			$options = [];
		foreach ($options as $v) {
			if (is_array($v)) {
				$result[] = $this->prepare_array($v);
			} else {
				$str = $this->escape($v);
				$str = str_replace('"', '\\\"', $str);
				if (strpos($str, ',') !== false || strpos($str, ' ') !== false || strpos($str, '{') !== false || strpos($str, '}') !== false || strpos($str, '"') !== false) {
					$result[] = '"' . $str . '"';
				} else {
					$result[] = $str;
				}
			}
		}
		return '{' . implode(',', $result) . '}';
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
				$key = $par[0];
				$operator = !empty($par[1]) ? $par[1] : '=';
				$as_is = (isset($par[2]) && $par[2] == '~~') ? true : false;
				$string = $key;
				switch ($operator) {
					case 'LIKE%':
					case 'ILIKE%':
						$v = '%' . $v . '%';
					case 'LIKE':
					case 'ILIKE':
						$string.= ' ILIKE ';
						break;
					default:
						$string.= ' ' . $operator . ' ';
				}

				// value
				if ($as_is) {
					// no changes
				} else if (is_string($v)) {
					$v = "'" . $this->escape($v) . "'";
				} else if (is_numeric($v)) {
					// no changes
				} else if (is_array($v)) {
					$v = "'" . $this->prepare_array($v) . "'";
				} else if (is_null($v)) {
					$v = 'NULL';
				} else {
					Throw new Exception('Unknown data type');
				}
				$string .= $v;
				// special for array operators: ANY, ALL
				if ($operator == 'ANY' || $operator == 'ALL') {
					$string = $v . ' = ' . $operator . '(' . $key . ')';
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
	 * @param string $link
	 * @return array
	 */
	public function escape_array($value) {
		$result = [];
		foreach ($value as $k => $v) {
			$result[$k] = $this->escape($v);
		}
		return $result;
	}
}