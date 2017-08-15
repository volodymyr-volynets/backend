<?php

namespace Numbers\Backend\Db\Common\Query;
class Builder {

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
		'groupby' => [],
		'union' => []
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
		if (!empty($options['cache_tags'])) {
			$this->cache_tags = array_merge($this->cache_tags, $options['cache_tags']);
		}
		$this->db_object = new \Db($db_link);
	}

	/**
	 * Quick
	 *
	 * @param string $db_link
	 * @param array $options
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public static function quick(string $db_link, array $options = []) : \Numbers\Backend\Db\Common\Query\Builder {
		$object = new Builder($db_link, $options);
		return $object;
	}

	/**
	 * Select
	 *
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function select() : \Numbers\Backend\Db\Common\Query\Builder {
		$this->data['operator'] = 'select';
		return $this;
	}

	/**
	 * Update
	 *
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function update() : \Numbers\Backend\Db\Common\Query\Builder {
		$this->data['operator'] = 'update';
		return $this;
	}

	/**
	 * Insert
	 *
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function insert() : \Numbers\Backend\Db\Common\Query\Builder {
		$this->data['operator'] = 'insert';
		return $this;
	}

	/**
	 * Delete
	 *
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function delete() : \Numbers\Backend\Db\Common\Query\Builder {
		$this->data['operator'] = 'delete';
		return $this;
	}

	/**
	 * Columns
	 *
	 * @param mixed $columns
	 * @param array $options
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function columns($columns, array $options = []) : \Numbers\Backend\Db\Common\Query\Builder {
		// empty existing columns
		if (!empty($options['empty_existing'])) $this->data['columns'] = [];
		// process only not null columns
		if (!is_null($columns)) {
			// convert columns to array
			if (is_scalar($columns)) $columns = [$columns];
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
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function set($columns) : \Numbers\Backend\Db\Common\Query\Builder {
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
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function from($table, $alias = null) : \Numbers\Backend\Db\Common\Query\Builder {
		// add based on alias
		if (!empty($alias)) {
			$this->data['from'][$alias] = $this->singleFromClause($table);
		} else {
			array_push($this->data['from'], $this->singleFromClause($table));
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
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function join(string $type, $table, $alias, string $on = 'ON', $conditions) : \Numbers\Backend\Db\Common\Query\Builder {
		$join = [
			'type' => $type,
			'table' => null,
			'alias' => $alias,
			'on' => $on,
			'conditions' => []
		];
		// add based on table type
		$table_extra_conditions = [];
		$join['table'] = $this->singleFromClause($table, $alias, $table_extra_conditions);
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
					array_push($join['conditions'], $this->singleConditionClause($v[0], $v[1], $v[2] ?? false));
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
	private function singleFromClause($table, $alias = null, & $conditions = []) : string {
		// add based on table type
		if (is_string($table)) {
			// if table name does not contains space
			if (strpos($table, ' ') === false) {
				$this->cache_tags[] = $table;
			}
			return $table;
		} else if (is_object($table) && is_a($table, 'Numbers\Backend\Db\Common\Query\Builder')) { // query builder object
			$this->cache_tags = array_merge($this->cache_tags, $table->cache_tags);
			return "(\n" . $this->wrapSqlIntoTabs($table->sql()) . "\n)";
		} else if (is_object($table) && is_a($table, '\Object\Table')) { // table object
			// injecting tenant
			if ($table->tenant) {
				$conditions[] = ['AND', [ltrim($alias . '.' . $table->tenant_column), '=', \Tenant::id(), false], false];
			}
			// grab tags
			$this->cache_tags = array_merge($this->cache_tags, $table->cache_tags);
			return $table->full_table_name;
		} else if (is_callable($table)) {
			return "(\n" . $this->wrapSqlIntoTabs($this->subquery($table)) . "\n)";
		}
	}

	/**
	 * Union
	 *
	 * @param string $type
	 * @param mixed $select
	 */
	public function union(string $type, $select) : \Numbers\Backend\Db\Common\Query\Builder {
		if (is_callable($select)) {
			$select = $this->subquery($select);
		}
		array_push($this->data['union'], [
			'type' => $type,
			'select' => $select
		]);
		return $this;
	}

	/**
	 * Single condition clause
	 *
	 * @param string $operator
	 * @param mixed $condition
	 * @param boolean $exists
	 * @return array
	 */
	private function singleConditionClause(string $operator = 'AND', $condition, bool $exists = false) {
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
			if (is_object($condition[2]) && is_a($condition[2], '\Numbers\Backend\Db\Common\Query\Builder')) {
				$condition[2] = '(' . trim($this->wrapSqlIntoTabs($condition[2]->sql())) . ')';
				$condition[3] = true;
			}
			// todo: normilize
			$key = [$condition[0], $condition[1]];
			if (!empty($condition[3])) {
				$key[] = '~~';
			}
			$key = implode(';', $key);
			return [$operator, $exists, $this->db_object->prepareCondition([$key => $condition[2] ?? null]), false];
		} else if (is_callable($condition)) {
			if (!empty($exists)) {
				return [$operator, $exists, '(' . trim($this->wrapSqlIntoTabs($this->subquery($condition), 2)) . ')', false];
			} else {
				return [$operator, $exists, $this->whereInner($condition), false];
			}
		}
	}

	/**
	 * Where
	 *
	 * @param string $operator
	 * @param mixed $condition
	 * @param boolean $exists
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function where(string $operator = 'AND', $condition, bool $exists = false) : \Numbers\Backend\Db\Common\Query\Builder {
		// add condition
		array_push($this->data['where'], $this->singleConditionClause($operator, $condition, $exists));
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
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function whereMultiple(string $operator, array $conditions) : \Numbers\Backend\Db\Common\Query\Builder {
		foreach ($conditions as $k => $v) {
			// notation field;=;~~ => [value]
			if (is_string($k)) {
				$this->where($operator, $this->db_object->prepareCondition([$k => $v]));
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
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function values($values) : \Numbers\Backend\Db\Common\Query\Builder {
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
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function distinct() : \Numbers\Backend\Db\Common\Query\Builder {
		$this->data['distinct'] = true;
		return $this;
	}

	/**
	 * For update
	 *
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function forUpdate() : \Numbers\Backend\Db\Common\Query\Builder {
		$this->data['for_update'] = true;
		return $this;
	}

	/**
	 * Returning
	 *
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function returning() : \Numbers\Backend\Db\Common\Query\Builder {
		$this->data['returning'] = true;
		return $this;
	}

	/**
	 * Limit
	 *
	 * @param int $limit
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function limit(int $limit) : \Numbers\Backend\Db\Common\Query\Builder {
		$this->data['limit'] = $limit;
		return $this;
	}

	/**
	 * Offset
	 *
	 * @param int $offset
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function offset(int $offset) : \Numbers\Backend\Db\Common\Query\Builder {
		$this->data['offset'] = $offset;
		return $this;
	}

	/**
	 * Order by
	 *
	 * @param array $orderby
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function orderby(array $orderby) : \Numbers\Backend\Db\Common\Query\Builder {
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
	 * @return \Numbers\Backend\Db\Common\Query\Builder
	 */
	public function groupby(array $groupby) : \Numbers\Backend\Db\Common\Query\Builder {
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
		return $this->db_object->object->queryBuilderRender($this);
	}

	/**
	 * Render where clause
	 *
	 * @param array $where
	 * @return string
	 */
	public function renderWhere(array $where) : string {
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
	private function whereInner($function) {
		$subquery = new \Numbers\Backend\Db\Common\Query\Builder($this->db_link, ['subquery' => true]);
		$function($subquery);
		$this->cache_tags = array_merge($this->cache_tags, $subquery->cache_tags);
		return "( " . trim($this->wrapSqlIntoTabs($subquery->renderWhere($subquery->data['where']) . "\n)"));
	}

	/**
	 * Sub-query
	 *
	 * @param callable $function
	 * @return array
	 */
	private function subquery($function) {
		$subquery = new \Numbers\Backend\Db\Common\Query\Builder($this->db_link, ['subquery' => true]);
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
	 * @param int $tab_number
	 * @return string
	 */
	public function wrapSqlIntoTabs($sql, $tab_number = 1) {
		$temp = explode("\n", $sql);
		$tab = '';
		for ($i = 0; $i < $tab_number; $i++) $tab.= "\t";
		foreach ($temp as $k => $v) {
			$temp[$k] = $tab . $v;
		}
		return implode("\n", $temp);
	}
}