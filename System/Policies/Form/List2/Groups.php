<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\System\Policies\Form\List2;

use Object\Form\Wrapper\List2;

class Groups extends List2
{
    public $form_link = 'sm_system_policy_groups_list';
    public $module_code = 'SM';
    public $title = 'S/M System Policy Groups List';
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
            'full_text_search' => [
                'full_text_search' => ['order' => 1, 'row_order' => 300, 'label_name' => 'Text Search', 'full_text_search_columns' => ['a.sm_polgroup_code', 'a.sm_polgroup_name', 'a.sm_polgroup_description'], 'placeholder' => true, 'domain' => 'name', 'percent' => 100, 'null' => true],
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
                'sm_polgroup_id' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Group #', 'domain' => 'group_id', 'percent' => 15, 'url_edit' => true],
                'sm_polgroup_code' => ['order' => 2, 'label_name' => 'Code', 'domain' => 'group_code', 'percent' => 25],
                'sm_polgroup_name' => ['order' => 3, 'label_name' => 'Name', 'domain' => 'token', 'percent' => 55],
                'sm_polgroup_inactive' => ['order' => 4, 'label_name' => 'Inactive', 'type' => 'boolean', 'percent' => 5]
            ],
            'row2' => [
                '__black' => ['order' => 1, 'row_order' => 200, 'label_name' => '', 'percent' => 15],
                'sm_polgroup_description' => ['order' => 2, 'label_name' => 'Description', 'domain' => 'token', 'percent' => 85],
            ]
        ]
    ];
    public $query_primary_model = '\Numbers\Backend\System\Policies\Model\Groups';
    public $list_options = [
        'pagination_top' => '\Numbers\Frontend\HTML\Form\Renderers\HTML\Pagination\Base',
        'pagination_bottom' => '\Numbers\Frontend\HTML\Form\Renderers\HTML\Pagination\Base',
        'default_limit' => 30,
        'default_sort' => [
            'sm_polgroup_id' => SORT_DESC
        ]
    ];
    public const LIST_SORT_OPTIONS = [
        'sm_polgroup_id' => ['name' => 'Group #'],
        'sm_polgroup_code' => ['name' => 'Code'],
        'sm_polgroup_name' => ['name' => 'Name'],
        'sm_polgroup_inserted_timestamp' => ['name' => 'Inserted']
    ];
}
