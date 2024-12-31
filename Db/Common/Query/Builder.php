<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common\Query;

use Object\Table;

class Builder
{
    /**
     * Db link
     *
     * @var string
     */
    public $db_link;

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
            // select
            // update
            // insert
            // delete
            // check
            // truncate
            // with
        'columns' => [],
        'from' => [],
        'join' => [],
        'set' => [],
        'where' => [],
        'where_delete' => [],
        'orderby' => [],
        'groupby' => [],
        'having' => [],
        'union' => [],
        'union_orderby' => false, // indicator that previous
        'primary_key' => null,
        'comment' => '',
        'with' => [],
        'relation' => [],
        'relation_options' => [],
        'scope' => [],
        'cascade' => false,
        'dblink_as' => [],
        'pivot' => [],
        'view' => [],
    ];

    /**
     * Cache tags
     *
     * @var array
     */
    public $cache_tags = [];

    /**
     * Primary model
     *
     * @var object
     */
    public $primary_model;

    /**
     * Primary alias
     *
     * @var string
     */
    public $primary_alias;

    /**
     * Constructor
     *
     * @param string $db_link
     * @param array $options
     */
    public function __construct(string $db_link = 'default', array $options = [])
    {
        $this->db_link = $db_link;
        $this->options = $options;
        $this->options['parent_operator'] = $options['parent_operator'] ?? null;
        if (!empty($options['cache_tags'])) {
            $this->cache_tags = array_merge($this->cache_tags, $options['cache_tags']);
        }
        // special parameters we must collect
        $this->data['primary_key'] = $options['primary_key'] ?? null;
        // db object
        $this->db_object = new \Db($db_link);
    }

    /**
     * Quick
     *
     * @param string $db_link
     * @param array $options
     * @return Builder
     */
    public static function quick(string $db_link = '', array $options = []): Builder
    {
        if (empty($db_link)) {
            $db_link = \Application::get('flag.global.default_db_link');
        }
        $object = new Builder($db_link, $options);
        return $object;
    }

    /**
     * Select
     *
     * @return Builder
     */
    public function select(): Builder
    {
        $this->data['operator'] = 'select';
        return $this;
    }

    /**
     * Update
     *
     * @return Builder
     */
    public function update(): Builder
    {
        $this->data['operator'] = 'update';
        // exceptions
        if (empty($this->data['primary_key'])) {
            throw new \Exception('You must provide primary_key when constructing update query!');
        }
        return $this;
    }

    /**
     * Insert
     *
     * @return Builder
     */
    public function insert(): Builder
    {
        $this->data['operator'] = 'insert';
        return $this;
    }

    /**
     * Delete
     *
     * @return Builder
     */
    public function delete(): Builder
    {
        $this->data['operator'] = 'delete';
        // we need to convert where
        if (!empty($this->data['where_delete'])) {
            foreach ($this->data['where_delete'] as $k => $v) {
                $this->data['where'][$k] = $v;
            }
            $this->data['where_delete'] = [];
        }
        // exceptions
        if (empty($this->data['primary_key'])) {
            throw new \Exception('You must provide primary_key when constructing delete query!');
        }
        return $this;
    }

    /**
     * Check
     *
     * @return Builder
     */
    public function check(): Builder
    {
        $this->data['operator'] = 'check';
        return $this;
    }

    /**
     * Truncate
     *
     * @return Builder
     */
    public function truncate(): Builder
    {
        $this->data['operator'] = 'truncate';
        return $this;
    }

    /**
     * View, create new view from a query
     *
     * @param string $name
     * @return Builder
     */
    public function view(string $name, bool $temporary = false): Builder
    {
        $this->data['operator'] = 'select';
        $this->data['view'] = [
            'name' => $name,
            'temporary' => $temporary,
        ];
        return $this;
    }

    /**
     * Cascade
     *
     * @return Builder
     */
    public function cascade(): Builder
    {
        $this->data['cascade'] = true;
        return $this;
    }

    /**
     * With (relation)
     *
     * @param array|string $relations
     * @param array $options
     * @return Builder
     */
    public function withRelation(array|string $relations, array $options = []): Builder
    {
        if (is_string($relations)) {
            $relations = [$relations];
        }
        foreach ($relations as $k => $v) {
            if (is_numeric($k)) {
                $k = str_replace('.', '', $v);
            }
            $parts = explode('.', $v);
            $assembled = [];
            foreach ($parts as $part) {
                $assembled[] = $part;
                $this->data['relation'][$k . '::' . implode('.', $assembled)] = $assembled;
                $this->data['relation_options'][$k . '::' . implode('.', $assembled)] = $options;
            }
        }
        return $this;
    }

    public function withPivot(array $pivots): Builder
    {

        return $this;
    }

    /**
     * With (scope)
     *
     * @param array $scopes
     * @return Builder
     */
    public function withScope(array|string $scopes): Builder
    {
        $scopes = array_arguments(func_get_args());
        foreach ($scopes as $k => $v) {
            if (is_numeric($k)) {
                $skip = $v[0] == '!';
                if ($skip) {
                    $v = ltrim($v, '!');
                }
                $scope = ['name' => $v, 'skip' => $skip, 'options' => []];
            } else {
                $skip = $k[0] == '!';
                if ($skip) {
                    $k = ltrim($k, '!');
                }
                $scope = ['name' => $k, 'options' => $v];
            }
            if (method_exists($this->primary_model, 'scope' . $scope['name'] . 'Global')) {
                $scope['name'] .= 'Global';
            } elseif (!method_exists($this->primary_model, 'scope' . $scope['name'])) {
                throw new \Exception("Scope {$scope['name']} does not exists!");
            }
            $this->data['scope'][$scope['name']] = $scope;
        }
        return $this;
    }

    /**
     * With (recursive)
     *
     * @param string $name
     * @param array $columns
     * @param callable $function
     * @return Builder
     */
    public function withRecursive(string $name, array $columns, $function): Builder
    {
        $this->data['operator'] = 'with_recursive';
        $this->data['with'] = [
            'name' => $name,
            'columns' => $columns,
            'sql' => $this->subquery($function),
        ];
        $this->from($name, $name);
        return $this;
    }

    /**
     * Comment
     *
     * @param string $str
     * @return Builder
     */
    public function comment(string $str): Builder
    {
        $this->data['comment'] = $str;
        return $this;
    }

    /**
     * Columns
     *
     * @param mixed $columns
     * @param array $options
     * 		empty_existing - if we neeed to empty column list
     * 		prefix - as prefix for column
     * @return Builder
     */
    public function columns($columns, array $options = []): Builder
    {
        // empty existing columns
        if (!empty($options['empty_existing'])) {
            $this->data['columns'] = [];
        }
        // process only not null columns
        if (!is_null($columns)) {
            // convert columns to array
            if (is_scalar($columns)) {
                $columns = [$columns];
            }
            // add columns
            foreach ($columns as $k => $v) {
                if (is_numeric($k)) {
                    array_push($this->data['columns'], $v);
                } else {
                    if (isset($options['prefix']) && !str_starts_with($k, $options['prefix'])) {
                        $k = rtrim($options['prefix'], '_') . '_' . $k;
                    }
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
     * @return Builder
     */
    public function set($columns): Builder
    {
        // convert columns to array
        if (is_string($columns)) {
            $columns = [$columns];
        }
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
     * @return Builder
     */
    public function from($table, $alias = null): Builder
    {
        // add based on alias
        if (!empty($alias)) {
            $this->data['from'][$alias] = $this->singleFromClause($table, $alias);
        } else {
            array_push($this->data['from'], $this->singleFromClause($table));
        }
        // exceptions
        if (in_array($this->data['operator'], ['delete', 'truncate']) && count($this->data['from']) > 1) {
            throw new \Exception('Deletes/truncate from multiple tables are not allowed!');
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
     * @return Builder
     */
    public function join(string $type, $table, $alias, string $on = 'ON', $conditions = null): Builder
    {
        $join = [
            'type' => $type,
            'table' => null,
            'alias' => $alias,
            'on' => $on,
            'conditions' => []
        ];
        // cross join does not have parameters
        if (strtolower($type) == 'cross') {
            $join['on'] = null;
            $conditions = null;
        }
        // add based on table type
        $table_extra_conditions = [];
        $join['table'] = $this->singleFromClause($table, $alias, $table_extra_conditions);
        // condition
        if (!empty($conditions)) {
            if (is_scalar($conditions)) {
                $join['conditions'] = $conditions;
            } elseif (is_array($conditions)) { // array
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
     * Pivot
     *
     * @param string $type
     * @param mixed $table
     * @param mixed $alias
     * @param string $on
     * @param mixed $conditions
     * @param string $name
     * @param array $columns
     * @return Builder
     */
    public function pivot(string $type, $table, $alias, string $on = 'ON', $conditions = null, string $name = 'Pivot', array $columns = []): Builder
    {
        $this->join($type, $table, $alias, $on, $conditions);
        if (empty($columns) && is_object($table) && is_a($table, 'Object\Table')) {
            $columns = array_keys($table->columns);
        }
        if (is_numeric_key_array($columns)) {
            $columns = array_combine($columns, $columns);
        }
        $prefix = 'pivot_' . strtolower($name) . '_';
        array_key_prefix_and_suffix($columns, $prefix, null, false, true);
        $this->columns($columns);
        $this->data['pivot'][$name] = [
            'table' => $table,
            'prefix' => $prefix,
            'columns' => $columns,
        ];
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
    private function singleFromClause($table, $alias = null, & $conditions = []): string
    {
        // add based on table type
        if (is_string($table)) {
            // if table name does not contains space
            if (strpos($table, ' ') === false) {
                $this->cache_tags[] = $table;
            }
            return $table;
        } elseif (is_object($table) && is_a($table, 'Numbers\Backend\Db\Common\Query\Builder')) { // query builder object
            $this->cache_tags = array_merge($this->cache_tags, $table->cache_tags);
            return "(\n" . $this->wrapSqlIntoTabs($table->sql()) . "\n)";
        } elseif (is_object($table) && is_a($table, 'Object\DataSource')) { // datasource object
            return $table->sql($this->options['where'] ?? [], $this->cache_tags);
        } elseif (is_object($table) && is_a($table, 'Object\Table')) { // table object
            // set primary model first table
            if (!isset($this->primary_model)) {
                $this->primary_model = $table;
                $this->primary_alias = $alias;
            }
            // injecting tenant
            if ($table->tenant && empty($table->options['skip_tenant'])) {
                $conditions[] = ['AND', [ltrim($alias . '.' . $table->tenant_column), '=', \Tenant::id(), false], false];
            }
            // grab tags & pk
            $this->cache_tags = array_merge($this->cache_tags, $table->cache_tags);
            $this->data['primary_key'] = $table->pk; // a must
            return $table->full_table_name;
        } elseif (is_object($table) && is_a($table, 'Object\View')) { // view object
            $this->cache_tags = array_merge($this->cache_tags, $table->grant_tables);
            return $table->full_view_name;
        } elseif (is_callable($table)) {
            return "(\n" . $this->wrapSqlIntoTabs($this->subquery($table)) . "\n)";
        }
    }

    /**
     * Union
     *
     * @param string $type
     *		UNION
     *		UNION ALL
     *		INTERSECT
     *		EXCEPT
     * @param mixed $select
     */
    public function union(string $type, $select): Builder
    {
        // validate type
        if (!in_array($type, ['UNION', 'UNION ALL', 'INTERSECT', 'EXCEPT'])) {
            throw new \Exception('Unknown type: ' . $type);
        }
        // we render if query builder
        if (is_object($select) && is_a($select, 'Numbers\Backend\Db\Common\Query\Builder')) {
            // validation on addition clauses
            if (!empty($this->data['union_orderby'])) {
                throw new \Exception('Previous queries have extra parameters in UNION');
            }
            if (!empty($select->data['limit']) || !empty($select->data['offset']) || !empty($select->data['orderby'])) {
                $this->data['union_orderby'] = true;
            }
            // render
            $result = $select->render();
            // grab tags
            $this->cache_tags = array_merge($this->cache_tags, $select->cache_tags);
            $select = $result['sql'];
        } elseif (is_callable($select)) { // if its a function
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
    private function singleConditionClause(string $operator = 'AND', $condition = '', bool $exists = false)
    {
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
            // exceptions
            if ($this->data['operator'] == 'check' || $this->options['parent_operator'] == 'check') {
                throw new \Exception('String conditions are not allowed in check constraints!');
            }
            return [$operator, $exists, $condition, false];
        } elseif (is_array($condition)) {
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
        } elseif (is_callable($condition)) {
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
     * @param array $options
     * @return Builder
     */
    public function where(string $operator = 'AND', $condition = '', bool $exists = false, $options = []): Builder
    {
        // add condition
        if (!empty($options['for_delete'])) {
            end($this->data['where']);
            $key = key($this->data['where']);
            $this->data['where_delete'][$key] = $this->singleConditionClause($operator, $condition, $exists);
        } else {
            array_push($this->data['where'], $this->singleConditionClause($operator, $condition, $exists));
        }
        // exceptions
        if ($this->data['operator'] == 'delete' && is_array($condition)) {
            if (strpos($condition[0], '.') !== false) {
                throw new \Exception('Aliases are not allowed in delete where clauses!');
            }
        }
        return $this;
    }

    /**
     * Having
     *
     * @param string $operator
     * @param mixed $condition
     * @return Builder
     */
    public function having(string $operator = 'AND', $condition = ''): Builder
    {
        array_push($this->data['having'], $this->singleConditionClause($operator, $condition, false));
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
     * @return Builder
     */
    public function whereMultiple(string $operator, array $conditions): Builder
    {
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
     * @return Builder
     */
    public function values($values): Builder
    {
        if (is_string($values) || is_array($values)) {
            $this->data['values'] = $values;
        } elseif (is_callable($values)) {
            $this->data['values'] = $this->subquery($values);
        }
        // grab columns from first array
        if (is_array($values) && empty($this->data['columns'])) {
            $this->columns(array_keys(current($values)));
        }
        return $this;
    }

    /**
     * Full text search
     *
     * @param string $operator
     *		AND, OR
     * @param array $fields
     * @param string $str
     * @param bool $rank
     *		Whether to include rank column
     * @param int $orderby
     *		SORT_ASC or SORT_DESC
     * @return Builder
     */
    public function fullTextSearch(string $operator, array $fields, string $str, bool $rank = false, $orderby = null): Builder
    {
        $result = $this->db_object->object->fullTextSearchQuery($fields, $str);
        $this->where($operator, $result['where']);
        if ($rank || !empty($orderby)) {
            $this->columns($result['rank']);
        }
        if (!empty($orderby)) {
            $this->orderby([$result['orderby'] => $orderby]);
        }
        return $this;
    }

    /**
     * Distinct
     *
     * @return Builder
     */
    public function distinct(): Builder
    {
        $this->data['distinct'] = true;
        return $this;
    }

    /**
     * Create temporary table
     *
     * @return Builder
     */
    public function temporaryTable($name): Builder
    {
        $this->data['temporary_table'] = $name;
        // exceptions
        if (strpos($name, '.') !== false) {
            throw new \Exception('Schema is not allowed when creating temporary table!');
        }
        return $this;
    }

    /**
     * For update
     *
     * @return Builder
     */
    public function forUpdate(): Builder
    {
        $this->data['for_update'] = true;
        return $this;
    }

    /**
     * Dblink
     *
     * @return Builder
     */
    public function dblink(array $as): Builder
    {
        $this->data['dblink_as'] = $as;
        return $this;
    }

    /**
     * Returning
     *
     * @return Builder
     */
    public function returning(): Builder
    {
        $this->data['returning'] = true;
        return $this;
    }

    /**
     * Limit
     *
     * @param int $limit
     * @return Builder
     */
    public function limit(int $limit): Builder
    {
        $this->data['limit'] = $limit;
        $this->data['union_orderby'] = true;
        return $this;
    }

    /**
     * Offset
     *
     * @param int $offset
     * @return Builder
     */
    public function offset(int $offset): Builder
    {
        $this->data['offset'] = $offset;
        $this->data['union_orderby'] = true;
        return $this;
    }

    /**
     * Order by
     *
     * @param array $orderby
     * @return Builder
     */
    public function orderby(array $orderby): Builder
    {
        // convert to array
        if (is_string($orderby)) {
            $this->data['orderby'][$orderby] = null;
        } else {
            $this->data['orderby'] = array_merge_hard($this->data['orderby'], $orderby);
        }
        $this->data['union_orderby'] = true;
        return $this;
    }

    /**
     * Order in random
     *
     * @return Builder
     */
    public function orderInRandom(): Builder
    {
        $random = $this->db_object->object->sqlHelper('rand');
        return $this->orderby([$random => SORT_ASC]);
    }

    /**
     * Group by
     *
     * @param array $orderby
     * @return Builder
     */
    public function groupby(array $groupby): Builder
    {
        // convert to array
        if (is_string($groupby)) {
            $groupby = [$groupby];
        }
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
    private function render(): array
    {
        // we need to proceess scopes last because we can disable global scopes in queries
        if (count($this->data['scope'])) {
            foreach ($this->data['scope'] as $scope) {
                if ($scope['skip']) {
                    continue;
                }
                /** @var $this->primary_model \Object\Table */
                $this->primary_model->{'scope' . $scope['name']}($this, $scope['options']);
            }
        }
        return $this->db_object->object->queryBuilderRender($this);
    }

    /**
     * Render where clause
     *
     * @param array $where
     * @return string
     */
    public function renderWhere(array $where): string
    {
        $result = '';
        if (!empty($where)) {
            $first = true;
            foreach ($where as $v) {
                // todo $v[3] indicates that it is multiple
                // first condition goes without operator
                if ($first) {
                    $result .= $v[1] . ' ' . $v[2];
                    $first = false;
                } else {
                    $result .= "\n\t" . $v[0];
                    if (!empty($v[1])) {
                        $result .= ' ' . $v[1];
                    }
                    $result .= ' ' . $v[2];
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
    private function whereInner($function)
    {
        $subquery = new Builder($this->db_link, [
            'subquery' => true,
            'parent_operator' => $this->options['parent_operator'] ?? $this->data['operator']
        ]);
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
    private function subquery($function)
    {
        $subquery = new Builder($this->db_link, ['subquery' => true]);
        $function($subquery);
        // validation on addition clauses
        //		if (!empty($this->data['union_orderby'])) {
        //			Throw new \Exception('Previous queries have extra parameters in UNION');
        //		}
        if (!empty($select->data['limit']) || !empty($select->data['offset']) || !empty($select->data['orderby'])) {
            $this->data['union_orderby'] = true;
        }
        $result = $subquery->render();
        if (!$result['success']) {
            throw new \Exception('Subquery: ' . implode(', ', $result['error']));
        }
        // grab tags
        $this->cache_tags = array_merge($this->cache_tags, $subquery->cache_tags);
        return $result['sql'];
    }

    /**
     * SQL
     *
     * @param bbool $return
     * @return mixed
     */
    public function sql(bool $return = true)
    {
        $result = $this->render();
        if ($return) {
            return $result['sql'];
        } else {
            print_r2($result['sql']);
            return $this;
        }
    }

    /**
     * Query
     *
     * @param array $options
     * @param mixed $pk
     * @return array
     */
    public function query($pk = null, array $options = []): array
    {
        $result = $this->render();
        if ($result['success']) {
            $main_query = $this->db_object->query($result['sql'], $pk, $options);
            if (!empty($main_query['rows']) && !empty($this->data['relation'])) {
                foreach ($this->data['relation'] as $k => $v) {
                    $first_with = array_shift($v);
                    if (!method_exists($this->primary_model, 'relation' . $first_with)) {
                        throw new \Exception("Relation $first_with does not exists!");
                    }
                    $relation_options = [
                        'alias' => 'relation_' . str_replace('.', '_', strtolower($first_with)),
                        'relation_name' => $first_with,
                        'relation_key' => explode('::', $k)[0],
                        'relation_children' => implode('.', $v),
                    ];
                    $relation_options = array_merge($relation_options, $this->data['relation_options'][$k]);
                    /** @var Table */
                    $this->primary_model->{'relation' . $first_with}($main_query['rows'], $relation_options);
                }
            }
            return $main_query;
        } else {
            throw new \Exception(implode(', ', $result['error']));
        }
    }

    /**
     * Array2
     *
     * @param array $options
     * @param mixed $pk
     * @return \Array2
     */
    public function array2($pk = null, array $options = []): \Array2
    {
        $result = $this->query($pk, $options);
        return new \Array2($result['rows']);
    }

    /**
     * Wrap SQL into tabs
     *
     * @param string $sql
     * @param int $tab_number
     * @return string
     */
    public function wrapSqlIntoTabs($sql, $tab_number = 1)
    {
        $temp = explode("\n", $sql);
        $tab = '';
        for ($i = 0; $i < $tab_number; $i++) {
            $tab .= "\t";
        }
        foreach ($temp as $k => $v) {
            $temp[$k] = $tab . $v;
        }
        return implode("\n", $temp);
    }

    /**
     * Override columns
     *
     * @param array $columns
     * @return Builder
     */
    public function columnOverrides(array $columns): Builder
    {
        foreach ($columns as $k => $v) {
            if (!empty($this->db_object->object->sql_column_overrides[$v['type']])) {
                $this->columns([
                    $k . '__' . $v['type'] => $this->db_object->object->sql_column_overrides[$v['type']] . '(' . $k . ')'
                ]);
            }
        }
        return $this;
    }
}
