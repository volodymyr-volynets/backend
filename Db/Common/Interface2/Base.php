<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Db\Common\Interface2;

use Numbers\Backend\Db\Common\Query\Builder;

interface Base
{
    public function connect(array $options): array;
    public function begin();
    public function escape($value);
    public function escapeArray($value);
    public function query(string $sql, $key = null, array $options = []): array;
    public function commit();
    public function rollback();
    public function sequence($sequence_name, $type = 'nextval', $tenant = null, $module = null);
    public function close();
    public function fullTextSearchQuery($fields, $str);
    public function createTempTable($table, $columns, $pk = null, $options = []);
    public function sqlHelper($statement, $options = []);
    public function cast(string $column, string $type): string;
    public function queryBuilderRender(Builder $object): array;
    public function copy(string $table, array $rows): array;
}
