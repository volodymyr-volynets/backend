<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\IO\Renderers\List2\CSV;

use Object\Form\Builder\Report;

class Base
{
    /**
     * Render
     *
     * @param \Object\Form\Base $object
     * @return string
     */
    public function render(\Object\Form\Base & $object): string
    {
        // use report builder
        $report = new Report();
        $report->addReport(DEF, $object);
        // add header
        $header = [];
        $temp = [];
        foreach ($object->misc_settings['list']['columns'] as $k => $v) {
            foreach ($v['elements'] as $k2 => $v2) {
                if (empty($v2['options']['label_name'])) {
                    continue;
                }
                $temp[$k2] = $v2['options'];
            }
            $header[$k] = $k;
        }
        $report->addHeader(DEF, 'test', $temp);
        // add data
        foreach ($object->misc_settings['list']['rows'] as $k => $v) {
            $v_original = $v;
            foreach ($header as $v2) {
                foreach ($object->misc_settings['list']['columns'][$v2]['elements'] as $k3 => $v3) {
                    // format
                    if (!empty($v3['options']['format']) && empty($v3['options']['options_model'])) {
                        $method = \Factory::method($v3['options']['format'], 'Format');
                        $v[$k3] = call_user_func_array([$method[0], $method[1]], [$v[$k3] ?? null, $v3['options']['format_options'] ?? []]);
                    }
                    // custom renderer
                    if (!empty($v3['options']['custom_renderer'])) {
                        $method = \Factory::method($v3['options']['custom_renderer'], $object->form_parent, true);
                        $v[$k3] = call_user_func_array($method, [& $object, & $v3, & $v[$k3], & $v]);
                        $v[$k3] = strip_tags2($v[$k3]);
                    } else {
                        // process options
                        if (!empty($v3['options']['options_model'])) {
                            $v[$k3] = $object->renderListContainerDefaultOptions($v3['options'], $v[$k3], $v_original);
                        }
                    }
                }
            }
            $report->addData(DEF, 'test', 0, $v);
            // gc
            unset($object->misc_settings['list']['rows'][$k]);
        }
        // add number of rows
        $report->addNumberOfRows(DEF, $object->misc_settings['list']['num_rows']);
        // render CSV through report renderer
        $renderer = new \Numbers\Backend\IO\Renderers\Report\CSV\Base();
        return $renderer->render($report);
    }
}
