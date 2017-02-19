<?php

/**
 * Query builder
 */
class numbers_backend_db_class_query_builder {

	/**
	 * Db link
	 *
	 * @var string
	 */
	private $db_link;

	/**
	 * Db object
	 *
	 * @var object
	 */
	public $db_object;

	/**
	 * Options
	 *
	 * @var array
	 */
	public $options = [];

	/**
	 * Data
	 *
	 * @var array
	 */
	public $data = [
		'operator' => 'select'
	];

	/**
	 * Constructor
	 *
	 * @param string $db_link
	 * @param array $options
	 */
	public function __construct(string $db_link, array $options = []) {
		$this->db_link = $db_link;
		$this->options = $options;
		$this->db_object = new db($db_link);
	}

	/**
	 * Quick
	 *
	 * @param string $db_link
	 * @param array $options
	 * @return \numbers_backend_db_class_query_builder
	 */
	public static function quick(string $db_link, array $options = []) : numbers_backend_db_class_query_builder {
		$object = new numbers_backend_db_class_query_builder($db_link, $options);
		return $object;
	}

	/**
	 * Select
	 *
	 * @return \numbers_backend_db_class_query_builder
	 */
	public function select() : numbers_backend_db_class_query_builder {
		$this->data['operator'] = 'select';
		return $this;
	}

	/**
	 * Update
	 *
	 * @return \numbers_backend_db_class_query_builder
	 */
	public function update() : numbers_backend_db_class_query_builder {
		$this->data['operator'] = 'update';
		return $this;
	}

	/**
	 * Insert
	 *
	 * @return \numbers_backend_db_class_query_builder
	 */
	public function insert() : numbers_backend_db_class_query_builder {
		$this->data['operator'] = 'insert';
		return $this;
	}

	/**
	 * Delete
	 *
	 * @return \numbers_backend_db_class_query_builder
	 */
	public function delete() : numbers_backend_db_class_query_builder {
		$this->data['operator'] = 'delete';
		return $this;
	}

	/**
	 * Columns
	 *
	 * @param mixed $columns
	 * @return \numbers_backend_db_class_query_builder
	 */
	public function columns($columns) : numbers_backend_db_class_query_builder {
		// create empty array
		if (!isset($this->data['columns'])) $this->data['columns'] = [];
		// convert columns to array
		if (is_string($columns)) $columns = [$columns];
		// add columns
		foreach ($columns as $k => $v) {
			if (is_numeric($k)) {
				array_push($this->data['columns'], $v);
			} else {
				$this->data['columns'][$k] = $v;
			}
		}
		return $this;
	}

	/**
	 * Set
	 *
	 * @param mixed $columns
	 * @return \numbers_backend_db_class_query_builder
	 */
	public function set($columns) : numbers_backend_db_class_query_builder {
		// create empty array
		if (!isset($this->data['set'])) $this->data['set'] = [];
		// convert columns to array
		if (is_string($columns)) $columns = [$columns];
		// add columns
		foreach ($columns as $k => $v) {
			if (is_numeric($k)) {
				array_push($this->data['set'], $v);
			} else {
				$this->data['set'][$k] = $v;
			}
		}
		return $this;
	}

	/**
	 * From
	 *
	 * @param mixed $tables
	 * @return \numbers_backend_db_class_query_builder
	 */
	public function from($tables) : numbers_backend_db_class_query_builder {
		// create empty array
		if (!isset($this->data['from'])) $this->data['from'] = [];
		// convert tables to array
		if (is_string($tables)) $tables = [$tables];
		// add tables
		foreach ($tables as $k => $v) {
			if (is_numeric($k)) {
				array_push($this->data['from'], $v);
			} else {
				$this->data['from'][$k] = $v;
			}
		}
		return $this;
	}

	/**
	 * Where
	 *
	 * @param string $operator
	 * @param mixed $condition
	 * @param boolean $exists
	 * @return \numbers_backend_db_class_query_builder
	 */
	public function where($operator = 'AND', $condition, $exists = false) : numbers_backend_db_class_query_builder {
		// create empty array
		if (!isset($this->data['where'])) $this->data['where'] = [];
		// operator
		$operator = strtoupper($operator);
		// exists
		if (!empty($exists)) {
			$exists = ' EXISTS';
		} else {
			$exists = '';
		}
		// process conditions
		if (is_string($condition)) {
			array_push($this->data['where'], [$operator, $exists, $condition, false]);
		} else if (is_array($condition)) {
			// todo: normilize
			$key = [$condition[0], $condition[1]];
			if (!empty($condition[3])) {
				$key[] = '~~';
			}
			$key = implode(';', $key);
			array_push($this->data['where'], [$operator, $exists, $this->db_object->prepare_condition([$key => $condition[2] ?? null]), false]);
		} else if (is_callable($condition)) {
			// todo
		}
		return $this;
	}

	/**
	 * Values
	 *
	 * @param mixed $values
	 * @return \numbers_backend_db_class_query_builder
	 */
	public function values($values) : numbers_backend_db_class_query_builder {
		if (is_string($values) || is_array($values)) {
			$this->data['values'] = $values;
		} else if (is_callable($values)) {
			$this->data['values'] = $this->subquery($values);
		}
		// grab columns from first array
		if (is_array($values) && empty($this->data['columns'])) {
			$this->columns(array_keys(current($values)));
		}
		return $this;
	}

	/**
	 * Distinct
	 *
	 * @return \numbers_backend_db_class_query_builder
	 */
	public function distinct() : numbers_backend_db_class_query_builder {
		$this->data['distinct'] = true;
		return $this;
	}

	/**
	 * Returning
	 *
	 * @return \numbers_backend_db_class_query_builder
	 */
	public function returning() : numbers_backend_db_class_query_builder {
		$this->data['returning'] = true;
		return $this;
	}

	/**
	 * Limit
	 *
	 * @param int $limit
	 * @return \numbers_backend_db_class_query_builder
	 */
	public function limit(int $limit) : numbers_backend_db_class_query_builder {
		$this->data['limit'] = $limit;
		return $this;
	}

	/**
	 * Offset
	 *
	 * @param int $offset
	 * @return \numbers_backend_db_class_query_builder
	 */
	public function offset(int $offset) : numbers_backend_db_class_query_builder {
		$this->data['offset'] = $offset;
		return $this;
	}

	/**
	 * Orderby
	 *
	 * @param array $orderby
	 * @return \numbers_backend_db_class_query_builder
	 */
	public function orderby(array $orderby) : numbers_backend_db_class_query_builder {
		$this->data['orderby'] = $orderby;
		return $this;
	}

	/**
	 * Render
	 *
	 * @return array
	 */
	private function render() : array {
		return $this->db_object->object->query_builder_render($this);
	}

	/**
	 * Render where clause
	 *
	 * @param array $where
	 * @return string
	 */
	public function render_where(array $where) : string {
		$result = '';
		if (!empty($where)) {
			$first = true;
			foreach ($where as $v) {
				
				// todo $v[3] indicates that it is multiple
				
				// first condition goes without operator
				if ($first) {
					$result.= $v[1] . ' ' . $v[2];
					$first = false;
				} else {
					$result.= "\n\t" . $v[0];
					if (!empty($v[1])) {
						$result.= ' ' . $v[1];
					}
					$result.= ' ' . $v[2];
				}
			}
		}
		return $result;
	}

	/**
	 * Sub-query
	 *
	 * @param callable $function
	 * @return array
	 */
	private function subquery($function) {
		$subquery = new numbers_backend_db_class_query_builder($this->db_link, ['subquery' => true]);
		$function($subquery);
		$result = $subquery->render();
		if (!$result['success']) {
			Throw new Exception('Subquery: ' . implode(', ', $result['error']));
		}
		return $result['sql'];
	}

	/**
	 * SQL
	 *
	 * @return string
	 */
	public function sql() : string {
		$result = $this->render();
		return $result['sql'];
	}

	/**
	 * Query
	 *
	 * @param array $options
	 * @param mixed $pk
	 * @return array
	 */
	public function query($pk = null, array $options = []) : array {
		$result = $this->render();
		if ($result['success']) {
			return $this->db_object->query($result['sql'], $pk, $options);
		} else {
			Throw new Exception(implode(', ', $result['error']));
		}
	}
}