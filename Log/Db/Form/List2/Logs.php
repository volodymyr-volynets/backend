<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Log\Db\Form\List2;

use Object\Form\Wrapper\List2;

class Logs extends List2
{
    public $form_link = 'sm_logs_db_list';
    public $module_code = 'SM';
    public $title = 'S/M Logs (Database) List';
    public $options = [
        'segment' => self::SEGMENT_LIST,
        'actions' => [
            'refresh' => true,
            'new' => true,
            'filter_sort' => ['value' => 'Filter/Sort', 'sort' => 32000, 'icon' => 'fas fa-filter', 'onclick' => 'Numbers.Form.listFilterSortToggle(this);']
        ]
    ];
    public $containers = [
        'tabs' => ['default_row_type' => 'grid', 'order' => 1000, 'type' => 'tabs', 'class' => 'numbers_form_filter_sort_container'],
        'filter' => ['default_row_type' => 'grid', 'order' => 1500],
        'sort' => self::LIST_SORT_CONTAINER,
        self::LIST_CONTAINER => ['default_row_type' => 'grid', 'order' => PHP_INT_MAX],
    ];
    public $rows = [
        'tabs' => [
            'filter' => ['order' => 100, 'label_name' => 'Filter'],
            'sort' => ['order' => 200, 'label_name' => 'Sort'],
        ]
    ];
    public $elements = [
        'tabs' => [
            'filter' => [
                'filter' => ['container' => 'filter', 'order' => 100]
            ],
            'sort' => [
                'sort' => ['container' => 'sort', 'order' => 100]
            ]
        ],
        'filter' => [
            'sm_log_user_id' => [
                'sm_log_user_id1' => ['order' => 1, 'row_order' => 100, 'label_name' => 'User #', 'domain' => 'user_id', 'percent' => 25, 'null' => true, 'query_builder' => 'a.sm_log_user_id;>='],
                'sm_log_user_id2' => ['order' => 2, 'label_name' => 'User #', 'domain' => 'user_id', 'percent' => 25, 'null' => true, 'query_builder' => 'a.sm_log_user_id;<='],
                'sm_log_year1' => ['order' => 3, 'label_name' => 'Year', 'domain' => 'year', 'percent' => 50, 'null' => true, 'default' => NUMBERS_FLAG_CURRENT_YEAR, 'method' => 'select', 'no_choose' => true, 'options_model' => '\Numbers\Backend\Log\Db\Model\Logs::optionsYears', 'query_builder' => 'a.sm_log_year'],
            ],
            'dates' => [
                'sm_log_inserted_timestamp1' => ['order' => 1, 'row_order' => 150, 'label_name' => 'Start Date', 'type' => 'datetime', 'percent' => 50, 'null' => true, 'method' => 'calendar', 'calendar_icon' => 'right', 'query_builder' => 'a.sm_log_inserted_timestamp::date;>='],
                'sm_log_inserted_timestamp2' => ['order' => 2, 'label_name' => 'End Date', 'type' => 'datetime', 'percent' => 50, 'null' => true, 'method' => 'calendar', 'calendar_icon' => 'right', 'query_builder' => 'a.sm_log_inserted_timestamp::date;<='],
            ],
            'sm_log_type' => [
                'sm_log_type1'  => ['order' => 1, 'row_order' => 200, 'label_name' => 'Type', 'domain' => 'name', 'percent' => 50, 'null' => true, 'method' => 'multiselect', 'multiple_column' => 1, 'options_model' => '\Numbers\Backend\Log\Db\Model\Logs::optionsColumnSettings', 'options_depends' => ['sm_log_year' => 'sm_log_year'], 'options_params' => ['__column' => 'sm_log_type'], 'query_builder' => 'a.sm_log_type'],
                'sm_log_level1'  => ['order' => 2, 'label_name' => 'Level', 'domain' => 'name', 'percent' => 50, 'null' => true, 'method' => 'multiselect', 'multiple_column' => 1, 'options_model' => '\Numbers\Backend\Log\Db\Model\Logs::optionsColumnSettings', 'options_depends' => ['sm_log_year' => 'sm_log_year'], 'options_params' => ['__column' => 'sm_log_level'], 'query_builder' => 'a.sm_log_level'],
            ],
            'sm_log_status' => [
                'sm_log_status1'  => ['order' => 1, 'row_order' => 300, 'label_name' => 'Status', 'domain' => 'code', 'percent' => 50, 'null' => true, 'method' => 'multiselect', 'multiple_column' => 1, 'options_model' => '\Numbers\Backend\Log\Db\Model\Logs::optionsColumnSettings', 'options_depends' => ['sm_log_year' => 'sm_log_year'], 'options_params' => ['__column' => 'sm_log_status'], 'query_builder' => 'a.sm_log_status'],
                'sm_log_operation1'  => ['order' => 3, 'label_name' => 'Operation', 'domain' => 'code', 'percent' => 50, 'null' => true, 'method' => 'multiselect', 'multiple_column' => 1, 'options_model' => '\Numbers\Backend\Log\Db\Model\Logs::optionsColumnSettings', 'options_depends' => ['sm_log_year' => 'sm_log_year'], 'options_params' => ['__column' => 'sm_log_operation'], 'query_builder' => 'a.sm_log_operation'],
            ],
            'full_text_search' => [
                'full_text_search' => ['order' => 1, 'row_order' => 999999, 'label_name' => 'Text Search', 'full_text_search_columns' => ['a.sm_log_id', 'a.sm_log_group_id', 'a.sm_log_originated_id', 'a.sm_log_message', 'a.sm_log_user_ip'], 'placeholder' => true, 'domain' => 'name', 'percent' => 100, 'null' => true],
            ]
        ],
        'sort' => [
            '__sort' => [
                '__sort' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Sort', 'domain' => 'code', 'details_unique_select' => true, 'percent' => 50, 'null' => true, 'method' => 'select', 'options' => self::LIST_SORT_OPTIONS, 'onchange' => 'this.form.submit();'],
                '__order' => ['order' => 2, 'label_name' => 'Order', 'type' => 'smallint', 'default' => SORT_ASC, 'percent' => 50, 'null' => true, 'method' => 'select', 'no_choose' => true, 'options_model' => '\Object\Data\Model\Order', 'onchange' => 'this.form.submit();'],
            ]
        ],
        self::LIST_BUTTONS => self::LIST_BUTTONS_DATA,
        self::LIST_CONTAINER => [
            'row1' => [
                'sm_log_id' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Log #', 'domain' => 'uuid', 'percent' => 30],
                'sm_log_message' => ['order' => 2, 'label_name' => 'Message', 'domain' => 'message', 'percent' => 65, 'format' => 'strip', 'format_options' => ['length' => 100]],
                'sm_log_inactive' => ['order' => 3, 'label_name' => 'Inactive', 'type' => 'boolean', 'percent' => 5]
            ],
            'row2' => [
                'sm_log_group_id' => ['order' => 1, 'row_order' => 200, 'label_name' => 'Group #', 'domain' => 'uuid', 'percent' => 30],
                'sm_log_type' => ['order' => 2, 'label_name' => 'Type', 'domain' => 'name', 'percent' => 20],
                'sm_log_level' => ['order' => 3, 'label_name' => 'Level', 'domain' => 'name', 'percent' => 10],
                'sm_log_status' => ['order' => 4, 'label_name' => 'Status', 'domain' => 'code', 'percent' => 10],
                'sm_log_affected_rows' => ['order' => 5, 'label_name' => 'Affected', 'domain' => 'counter', 'percent' => 5],
                'sm_log_error_rows' => ['order' => 6, 'label_name' => 'Errors', 'domain' => 'counter', 'percent' => 5],
                'sm_log_operation' => ['order' => 7, 'label_name' => 'Operation', 'domain' => 'code', 'percent' => 10],
                'sm_log_duration' => ['order' => 8, 'label_name' => 'Duration', 'domain' => 'duration', 'percent' => 5, 'format' => 'niceDuration'],
                'sm_log_ajax' => ['order' => 9, 'label_name' => 'AJAX', 'type' => 'boolean', 'percent' => 5],
            ],
            'row3' => [
                'sm_log_originated_id' => ['order' => 1, 'row_order' => 300, 'label_name' => 'Originated #', 'domain' => 'uuid', 'percent' => 30],
                'sm_log_inserted_timestamp' => ['order' => 2, 'label_name' => 'Timestamp', 'domain' => 'timestamp_now', 'format' => 'datetime', 'percent' => 20],
                'sm_log_host' => ['order' => 3, 'label_name' => 'Host', 'domain' => 'host', 'default' => 'CLI', 'percent' => 20, 'custom_renderer' => 'self::renderHost'],
                'sm_log_request_url' => ['order' => 4, 'label_name' => 'Request URL', 'domain' => 'url', 'percent' => 30],
            ],
            'row4' => [
                '__columns' => ['order' => 1, 'row_order' => 400, 'label_name' => 'Columns', 'type' => 'text', 'percent' => 100, 'custom_renderer' => 'self::renderColumns'],
            ]
        ]
    ];
    public $query_primary_model = '\Numbers\Backend\Log\Db\Model\Logs';
    public $query_primary_parameters = [];
    public $list_options = [
        'pagination_top' => '\Numbers\Frontend\HTML\Form\Renderers\HTML\Pagination\Base',
        'pagination_bottom' => '\Numbers\Frontend\HTML\Form\Renderers\HTML\Pagination\Base',
        'default_limit' => 30,
        'default_sort' => [
            'sm_log_inserted_timestamp' => SORT_DESC
        ]
    ];
    public const LIST_SORT_OPTIONS = [
        'sm_log_inserted_timestamp' => ['name' => 'Inserted Datetime'],
    ];

    public function renderHost(& $form, & $options, & $value, & $neighbouring_values)
    {
        return str_replace(['http://', 'https://', '/'], '', $neighbouring_values['sm_log_host']);
    }

    public function renderColumns(& $form, & $options, & $value, & $neighbouring_values)
    {
        $result = [];
        $result['Year'] = $neighbouring_values['sm_log_year'];
        if (!empty($neighbouring_values['sm_log_user_id'])) {
            $result['User #'] = $neighbouring_values['sm_log_user_id'];
        }
        $result['User IP'] = $neighbouring_values['sm_log_user_ip'];
        $result['Chanel'] = $neighbouring_values['sm_log_chanel'];
        $result['Content Type'] = $neighbouring_values['sm_log_content_type'];
        if (!empty($neighbouring_values['sm_log_controller_name'])) {
            $result['Controller Name'] = $neighbouring_values['sm_log_controller_name'];
        }
        if (!empty($neighbouring_values['sm_log_form_name'])) {
            $result['Form Name'] = $neighbouring_values['sm_log_form_name'];
        }
        if (!empty($neighbouring_values['sm_log_form_statistics'])) {
            $result['Form Statistics'] = print_r_nicely($neighbouring_values['sm_log_form_statistics'], ['width' => '65em']);
        }
        if (!empty($neighbouring_values['sm_log_notifications'])) {
            $result['Notifications'] = print_r_nicely($neighbouring_values['sm_log_notifications'], ['width' => '65em']);
        }
        if (!empty($neighbouring_values['sm_log_affected_users'])) {
            $result['Affected Users'] = print_r_nicely($neighbouring_values['sm_log_affected_users'], ['width' => '65em']);
        }
        if (!empty($neighbouring_values['sm_log_trace'])) {
            $result['Trace'] = print_r_nicely($neighbouring_values['sm_log_trace'], ['width' => '65em']);
        }
        if (!empty($neighbouring_values['sm_log_sql'])) {
            $result['SQL'] = $neighbouring_values['sm_log_sql'];
        }
        if (!empty($neighbouring_values['sm_log_other'])) {
            $result['Other'] = $neighbouring_values['sm_log_other'];
        }
        $list_id = 'list_table_id_temp_' . str_replace('-', '_', $neighbouring_values['sm_log_id']);
        $table = [
            'class' => '',
            'id' => $list_id,
            'style' => 'display: none;',
            'width' => '100%',
            'reponsive' => true,
            'header' => ['name' => ['value' => 'Name', 'width' => '20%'], 'value' => ['value' => 'Value', 'width' => '80%']],
            'options' => [],
        ];
        foreach ($result as $k => $v) {
            $table['options'][] = ['name' => ['value' => i18n(null, $k), 'width' => '20%', 'wrap' => true], 'value' => ['value' => $v, 'width' => '80%', 'wrap' => true]];
        }
        return \HTML::a(['href' => 'javascript:void(0);', 'onclick' => "$('#{$list_id}').toggle();", 'value' => i18n(null, 'Show/hide [counter] attributes.', ['replace' => ['[counter]' => count($result)]])]) . \HTML::br() . \HTML::table($table);
    }
}
