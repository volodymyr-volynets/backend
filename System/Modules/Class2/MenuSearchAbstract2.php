<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Modules\Class2;

use Object\Data\Common;

abstract class MenuSearchAbstract2
{
    /**
     * Menu Search code
     *
     * @var string
     */
    public $menu_search_code = '';

    /**
     * Search columns
     *
     * @var array
     */
    public $search_columns = [];

    /**
     * List columns
     *
     * @var array
     */
    public $list_columns = [];

    /**
     * Db model
     *
     * @var string
     */
    public $sm_model_code = '';

    /**
     * Parameters
     *
     * @var array
     */
    public $parameters = [];

    /**
     * Render
     *
     * @param array $options
     * @return array
     */
    abstract public function render(array $options = []): array;

    /**
     * Constructor
     */
    public function __construct()
    {
        if (empty($this->list_columns)) {
            $this->list_columns = $this->search_columns;
        }
    }

    /**
     * Process search text
     *
     * @param string $search_text
     * @return array{column: null, value: string|array{column: string, value: string}}
     */
    public function processSearchText(string $search_text): array
    {
        $result = [
            'success' => false,
            'error' => [],
            'column' => null,
            'value' => null,
        ];
        $temp = explode('=', $search_text, 2);
        if (count($temp) == 2) {
            $column = trim($temp[0], ' []');
            $filtered = array_filter($this->search_columns, function ($v) use ($column) {
                return $v['name'] == $column;
            });
            $value = $this->processDomains(key($filtered), $filtered[key($filtered)], trim($temp[1]));
            return [
                'success' => true,
                'error' => [],
                'column' => key($filtered),
                'column_clean' => $filtered[key($filtered)]['name'],
                'value' => $value,
            ];
        }
        $filtered = array_filter($this->search_columns, function ($v) {
            return $v['menu_search_default'] == true;
        });
        $value = $this->processDomains(key($filtered), $filtered[key($filtered)], trim($temp[0]));
        return [
            'success' => true,
            'error' => [],
            'column' => key($filtered),
            'column_clean' => $filtered[key($filtered)]['name'],
            'value' => $value,
        ];
    }

    /**
     * Process domains
     *
     * @param string $column_name
     * @param array $column_options
     * @param mixed $value
     * @return int|string
     */
    protected function processDomains(string $column_name, array $column_options, mixed $value): mixed
    {
        $temp = [$column_name => $column_options];
        $temp = Common::processDomainsAndTypes($temp);
        $column_options = $temp[$column_name];
        // by type
        if ($value === '') {
            return null;
        } elseif ($column_options['php_type'] == 'integer') {
            return intval($value);
        } else {
            return (string) $value;
        }
    }

    /**
     * Process query builder
     *
     * @param array $search_values
     * @return array
     */
    public function processQueryBuilder(array $search_values): array
    {
        $query = \Factory::model($this->sm_model_code)->queryBuilder()->select();
        $query->columns(array_keys($this->list_columns));
        $query->where('AND', [$search_values['column'], '=', $search_values['value']]);
        $query->limit(100);
        return $query->query()['rows'];
    }

    /**
     * Render tables
     *
     * @param array $rows
     * @return string
     */
    public function renderTables(array $rows): string
    {
        if (count($rows) == 0) {
            return '<div class="alert alert-danger">' . loc('NF.Error.NoRowsFound', 'No rows found!') . '</div>';
        } else {
            // generate html
            $html = '<table width="100%" class="table table-striped nf_table_no_td_padding">';
            $index = 1;
            foreach ($rows as $v) {
                $html .= '<tr>';
                $html .= '<td width="1%">&nbsp;' . \Format::id($index) . '.&nbsp;</td>';
                $html .= '<td width="99%">';
                $html .= '<table width="100%" class="table nf_table_no_td_padding">';
                foreach ($this->list_columns as $k2 => $v2) {
                    $html .= '<tr>';
                    $html .= '<th width="30%">' . \I18n::textToLoc('NF.Form', $v2['name'], ['translate' => true]) . ':</th>';
                    $html .= '<td width="70%">';
                    if (isset($v2['menu_search_url'])) {
                        $html .= \HTML::a(['href' => $v2['menu_search_url'] . $v[$k2], 'value' => $v[$k2], 'target' => '_blank']);
                    } else {
                        $html .= ($v[$k2] ?? '');
                    }
                    $html .= '</td>';
                    $html .= '</tr>';
                }
                $html .= '</table>';
                $html .= '</td>';
                $html .= '</tr>';
                $index++;
            }
            $html .= '</table>';
            return $html;
        }
    }
}
