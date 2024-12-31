<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\Log\Mail\Email;

use Object\Form\Wrapper\Email;

class LogMailDeliverLogs extends Email
{
    public $form_link = 'sm_log_mail_deliver_logs_email';
    public $module_code = 'SM';
    public $title = 'S/M Log Mail Deliver Logs Email';
    public $options = [
        'segment' => [
            'type' => 'primary',
        ],
        'hide_module_id' => true,
        'all_static' => true,
        'skip_acl' => true,
    ];
    public $containers = [
        self::PANEL_LOGO => ['order' => 100, 'custom_renderer' => '\Numbers\Users\Users\Helper\Brand\Logo::renderTopLogo'],
        self::PANEL_MESSAGE => ['order' => 150, 'type' => 'panels', 'loc' => 'NF.Message.NewLogs', 'loc_options' => ['NF.Message.NewLogsExplanation']],
        'pivot_panel' => ['order' => 200, 'type' => 'panels'],
        'pivot_container' => ['order' => 200, 'custom_renderer' => 'self::renderPivot'],
        'errors_panel' => ['order' => 300, 'type' => 'panels'],
        'errors_container' => ['order' => 300, 'custom_renderer' => 'self::renderErrors'],
        self::PANEL_FOOTER => ['order' => PHP_INT_MAX]
    ];
    public $rows = [
        'pivot_panel' => [
            'center' => ['order' => 100, 'label_name' => 'Pivot Groupped By Type/Message', 'loc' => 'NF.Form.PivotGrouppedByTypeMessage', 'panel_type' => 'primary', 'percent' => 100],
        ],
        'errors_panel' => [
            'center' => ['order' => 100, 'label_name' => 'Errors Groupped By Type/Message', 'loc' => 'NF.Form.ErrorsGrouppedByTypeMessage', 'panel_type' => 'danger', 'percent' => 100],
        ],
    ];
    public $elements = [
        'pivot_panel' => [
            'center' => [
                'top' => ['container' => 'pivot_container', 'order' => 100],
            ],
        ],
        'errors_panel' => [
            'center' => [
                'top' => ['container' => 'errors_container', 'order' => 100],
            ],
        ],
    ];
    public $collection = [];
    public $loc = [
        'NF.Message.NewLogs' => 'New Logs!',
        'NF.Message.NewLogsExplanation' => 'You are receiving this New Logs Email because you are assigned admin.'
    ];

    public function renderPivot(& $form)
    {
        return $form->values['pivot'];
    }

    public function renderErrors(& $form)
    {
        return $form->values['errors'];
    }
}
