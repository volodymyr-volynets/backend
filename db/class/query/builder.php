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
		'operator' => 'select',
		'columns' => [],
		'from' => [],
		'join' => [],
		'set' => [],
		'where' => [],
		'orderby' => [],
		'groupby' => []
	];

	/**
	 * Cache tags
	 *
	 * @var array
	 */
	public $cache_tags = [];

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
	 * @return numbers_backend_db_class_query_builder
	 */
	public static function quick(string $db_link, array $options = []) : numbers_backend_db_class_query_builder {
		$object = new numbers_backend_db_class_query_builder($db_link, $options);
		return $object;
	}

	/**
	 * Select
	 *
	 * @return numbers_backend_db_class_query_builder
	 */
	public function select() : numbers_backend_db_class_query_builder {
		$this->data['operator'] = 'select';
		return $this;
	}

	/**
	 * Update
	 *
	 * @return numbers_backend_db_class_query_builder
	 */
	public function update() : numbers_backend_db_class_query_builder {
		$this->data['operator'] = 'update';
		return $this;
	}

	/**
	 * Insert
	 *
	 * @return numbers_backend_db_class_query_builder
	 */
	public function insert() : numbers_backend_db_class_query_builder {
		$this->data['operator'] = 'insert';
		return $this;
	}

	/**
	 * Delete
	 *
	 * @return numbers_backend_db_class_query_builder
	 */
	public function delete() : numbers_backend_db_class_query_builder {
		$this->data['operator'] = 'delete';
		return $this;
	}

	/**
	 * Columns
	 *
	 * @param mixed $columns
	 * @param array $options
	 * @return numbers_backend_db_class_query_builder
	 */
	public function columns($columns, array $options = []) : numbers_backend_db_class_query_builder {
		// empty existing columns
		if (!empty($options['empty_existing'])) $this->data['columns'] = [];
		// process only not null columns
		if (!is_null($columns)) {
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
		}
		return $this;
	}

	/**
	 * Set
	 *
	 * @param mixed $columns
	 * @return numbers_backend_db_class_query_builder
	 */
	public function set($columns) : numbers_backend_db_class_query_builder {
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
	 * @param mixed $table
	 * @param string $alias
	 * @return numbers_backend_db_class_query_builder
	 */
	public function from($table, $alias = null) : numbers_backend_db_class_query_builder {
		// add based on alias
		if (!empty($alias)) {
			$this->data['from'][$alias] = $this->single_from_clause($table);
		} else {
			array_push($this->data['from'], $this->single_from_clause($table));
		}
		return $this;
	}

	/**
	 * Join
	 *
	 * @param string $type
	 * @param mixed $table
	 * @param mixed $alias
	 * @param string $on
	 * @param mixed $conditions
	 * @return numbers_backend_db_class_query_builder
	 */
	public function join(string $type, $table, $alias, string $on = 'ON', $conditions) : numbers_backend_db_class_query_builder {
		$join = [
			'type' => $type,
			'table' => null,
			'alias' => $alias,
			'on' => $on,
			'conditions' => []
		];
		// add based on table type
		$table_extra_conditions = [];
		$join['table'] = $this->single_from_clause($table, $alias, $table_extra_conditions);
		// condition
		if (!empty($conditions)) {
			if (is_scalar($conditions)) {
				$join['conditions'] = $conditions;
			} else if (is_array($conditions)) { // array
				// append extra conditions
				if (!empty($table_extra_conditions)) {
					$conditions = array_merge($table_extra_conditions, $conditions);
				}
				foreach ($conditions as $k => $v) {
					// notation: ['AND', ['a.sm_module_code', '=', 'b.tm_module_module_code'], false]
					array_push($join['conditions'], $this->single_condition_clause($v[0], $v[1], $v[2] ?? false));
				}
			}
		}
		// add
		if (!empty($alias)) {
			$this->data['join'][$alias] = $join;
		} else {
			array_push($this->data['join'], $join);
		}
		return $this;
	}

	/**
	 * Single from clause
	 *
	 * @param mixed $table
	 * @param string $alias
	 * @param array $conditions
	 * @return string
	 */
	private function single_from_clause($table, $alias = null, & $conditions = []) : string {
		// add based on table type
		if (is_string($table)) {
			// if table name does not contains space
			if (strpos($table, ' ') === false) {
				$this->cache_tags[] = $table;
			}
			return $table;
		} else if (is_object($table) && is_a($table, 'numbers_backend_db_class_query_builder')) { // query builder object
			$this->cache_tags = array_merge($this->cache_tags, $table->cache_tags);
			return "(\n" . $this->wrap_sql_into_tabs($table->sql()) . "\n)";
		} else if (is_object($table) && is_a($table, 'object_table')) { // table object
			// injecting tenant
			if ($table->tenant) {
				$conditions[] = ['AND', [ltrim($alias . '.' . $table->tenant_column), '=', tenant::tenant_id(), false], false];
			}
			// grab tags
			$this->cache_tags = array_merge($this->cache_tags, $table->cache_tags);
			return $table->full_table_name;
		} else if (is_callable($table)) {
			return "(\n" . $this->wrap_sql_into_tabs($this->subquery($table)) . "\n)";
		}
	}

	/**
	 * Single condition clause
	 *
	 * @param string $operator
	 * @param mixed $condition
	 * @param boolean $exists
	 * @return array
	 */
	private function single_condition_clause(string $operator = 'AND', $condition, bool $exists = false) {
		$result = null;
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
			return [$operator, $exists, $condition, false];
		} else if (is_array($condition)) {
			// see if we have an object
			if (is_object($condition[2]) && is_a($condition[2], 'numbers_backend_db_class_query_builder')) {
				$condition[2] = '(' . trim($this->wrap_sql_into_tabs($condition[2]->sql())) . ')';
				$condition[3] = true;
			}
			// todo: normilize
			$key = [$condition[0], $condition[1]];
			if (!empty($condition[3])) {
				$key[] = '~~';
			}
			$key = implode(';', $key);
			return [$operator, $exists, $this->db_object->prepare_condition([$key => $condition[2] ?? null]), false];
		} else if (is_callable($condition)) {
			return [$operator, $exists, $this->where_inner($condition), false];
		}
	}

	/**
	 * Where
	 *
	 * @param string $operator
	 * @param mixed $condition
	 * @param boolean $exists
	 * @return numbers_backend_db_class_query_builder
	 */
	public function where(string $operator = 'AND', $condition, bool $exists = false) : numbers_backend_db_class_query_builder {
		// add condition
		array_push($this->data['where'], $this->single_condition_clause($operator, $condition, $exists));
		return $this;
	}

	/**
	 * Where (multiple)
	 *
	 *	Notation: 'field;=;~~' => 'value'
	 *	Notation: ['field', '=', 'value', true]
	 *
	 * @param type $operator
	 * @param array $conditions
	 * @return numbers_backend_db_class_query_builder
	 */
	public function where_multiple(string $operator, array $conditions) : numbers_backend_db_class_query_builder {
		foreach ($conditions as $k => $v) {
			// notation field;=;~~ => [value]
			if (is_string($k)) {
				$this->where($operator, $this->db_object->prepare_condition([$k => $v]));
			} else { // notation: ['field', '=', 'value', true]
				$this->where($operator, $v);
			}
		}
		return $this;
	}

	/**
	 * Values
	 *
	 * @param mixed $values
	 * @return numbers_backend_db_class_query_builder
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
	 * @return numbers_backend_db_class_query_builder
	 */
	public function distinct() : numbers_backend_db_class_query_builder {
		$this->data['distinct'] = true;
		return $this;
	}

	/**
	 * For update
	 *
	 * @return numbers_backend_db_class_query_builder
	 */
	public function for_update() : numbers_backend_db_class_query_builder {
		$this->data['for_update'] = true;
		return $this;
	}

	/**
	 * Returning
	 *
	 * @return numbers_backend_db_class_query_builder
	 */
	public function returning() : numbers_backend_db_class_query_builder {
		$this->data['returning'] = true;
		return $this;
	}

	/**
	 * Limit
	 *
	 * @param int $limit
	 * @return numbers_backend_db_class_query_builder
	 */
	public function limit(int $limit) : numbers_backend_db_class_query_builder {
		$this->data['limit'] = $limit;
		return $this;
	}

	/**
	 * Offset
	 *
	 * @param int $offset
	 * @return numbers_backend_db_class_query_builder
	 */
	public function offset(int $offset) : numbers_backend_db_class_query_builder {
		$this->data['offset'] = $offset;
		return $this;
	}

	/**
	 * Order by
	 *
	 * @param array $orderby
	 * @return numbers_backend_db_class_query_builder
	 */
	public function orderby(array $orderby) : numbers_backend_db_class_query_builder {
		// convert to array
		if (is_string($orderby)) {
			$this->data['orderby'][$orderby] = null;
		} else {
			$this->data['orderby'] = array_merge_hard($this->data['orderby'], $orderby);
		}
		return $this;
	}

	/**
	 * Group by
	 *
	 * @param array $orderby
	 * @return numbers_backend_db_class_query_builder
	 */
	public function groupby(array $groupby) : numbers_backend_db_class_query_builder {
		// convert to array
		if (is_string($groupby)) $groupby = [$groupby];
		// add groupby
		foreach ($groupby as $k => $v) {
			array_push($this->data['groupby'], $v);
		}
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
	 * Inner where clauses
	 *
	 * @param callable $function
	 * @return string
	 */
	private function where_inner($function) {
		$subquery = new numbers_backend_db_class_query_builder($this->db_link, ['subquery' => true]);
		$function($subquery);
		return "( " . trim($this->wrap_sql_into_tabs($subquery->render_where($subquery->data['where']) . "\n)"));
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
		// grab tags
		$this->cache_tags = array_merge($this->cache_tags, $subquery->cache_tags);
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

	/**
	 * Wrap SQL into tabs
	 *
	 * @param string $sql
	 * @return string
	 */
	private function wrap_sql_into_tabs($sql) {
		$temp = explode("\n", $sql);
		foreach ($temp as $k => $v) {
			$temp[$k] = "\t" . $v;
		}
		return implode("\n", $temp);
	}
}