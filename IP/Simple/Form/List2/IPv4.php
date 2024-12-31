<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\IP\Simple\Form\List2;

use Object\Form\Wrapper\List2;

class IPv4 extends List2
{
    public $form_link = 'sm_ipv4_list';
    public $module_code = 'SM';
    public $title = 'S/M IPv4 List';
    public $options = [
        'segment' => self::SEGMENT_LIST,
        'actions' => [
            'refresh' => true,
            'new' => ['onclick' => null],
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
            'sort' => ['order' => 200, 'label_name' => 'Sort'],
        ]
    ];
    public $elements = [
        'tabs' => [
            'sort' => [
                'sort' => ['container' => 'sort', 'order' => 100]
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
                'sm_ipver4_start' => ['order' => 1, 'row_order' => 100, 'label_name' => 'IP Start', 'type' => 'bigint', 'percent' => 20, 'url_edit' => true],
                'sm_ipver4_end' => ['order' => 2, 'label_name' => 'IP End', 'type' => 'bigint', 'percent' => 20, 'url_edit' => true],
                'sm_ipver4_country_code' => ['order' => 3, 'label_name' => 'Country', 'domain' => 'country_code', 'null' => true, 'percent' => 20],
                'sm_ipver4_province' => ['order' => 4, 'label_name' => 'Province', 'domain' => 'name', 'null' => true, 'percent' => 20],
                'sm_ipver4_city' => ['order' => 5, 'label_name' => 'City', 'domain' => 'name', 'null' => true, 'percent' => 20],
            ]
        ]
    ];
    public $query_primary_model = '\Numbers\Backend\IP\Simple\Model\IPv4';
    public $list_options = [
        'pagination_top' => '\Numbers\Frontend\HTML\Form\Renderers\HTML\Pagination\Base',
        'pagination_bottom' => '\Numbers\Frontend\HTML\Form\Renderers\HTML\Pagination\Base',
        'default_limit' => 30,
        'default_sort' => [
            'sm_ipver4_start' => SORT_ASC
        ]
    ];
    public const LIST_SORT_OPTIONS = [
        'sm_ipver4_start' => ['name' => 'IP Start'],
    ];

    public function renderIPv4(& $form, & $options, & $value, & $neighbouring_values)
    {
        return long2ip($value);
    }
}
