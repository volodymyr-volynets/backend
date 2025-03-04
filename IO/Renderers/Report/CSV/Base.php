<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\IO\Renderers\Report\CSV;

use Object\Form\Builder\Report;

class Base
{
    /**
     * Render
     *
     * @param Report $object
     * @return string
     */
    public function render(Report & $object): string
    {
        $result = [];
        $result[] = [i18n(null, \Application::$controller->title) . ' (#' . \Format::id(\Application::$controller->controller_id) . ')'];
        $result[] = [i18n(null, 'By:') . ' ' . \User::get('name'), i18n(null, ' On:') . ' ' . \Format::id(\Format::datetime(\Format::now('datetime')))];
        $report_counter = 1;
        foreach (array_keys($object->data) as $report_name) {
            // chart
            if ($object->data[$report_name]['options']['type'] == CHART) {
                continue;
            }
            // render filter
            if (!empty($object->data[$report_name]['filter'])) {
                $result[] = [' '];
                foreach ($object->data[$report_name]['filter'] as $k => $v) {
                    $result[] = [$k, $v];
                }
                $result[] = [' '];
            }
            // render headers
            $new_headers = [];
            foreach ($object->data[$report_name]['header'] as $header_name => $header_data) {
                if (!empty($object->data[$report_name]['header_options'][$header_name]['skip_rendering'])) {
                    continue;
                }
                $new_headers[$header_name] = $header_data;
            }
            // loop though headers
            foreach ($new_headers as $header_name => $header_data) {
                $row = [];
                foreach ($header_data as $k2 => $v2) {
                    $row[] = strip_tags2($v2['label_name']);
                }
                $result[] = $row;
            }
            // summary
            if (!empty($object->data[$report_name]['header_summary'])) {
                $object->calculateSummary($report_name);
                foreach ($new_headers as $header_name => $header_data) {
                    if (empty($object->data[$report_name]['header_summary_calculated'][$header_name])) {
                        continue;
                    }
                    $row = [];
                    foreach ($header_data as $k2 => $v2) {
                        // render cell if not skipping
                        if (isset($object->data[$report_name]['header_summary_calculated'][$header_name][$v2['__index']])) {
                            $value = $object->data[$report_name]['header_summary_calculated'][$header_name][$v2['__index']]['final'];
                            if (!empty($object->data[$report_name]['header_summary'][$header_name][$v2['__index']]['format'])) {
                                $method = \Factory::method($object->data[$report_name]['header_summary'][$header_name][$v2['__index']]['format'], 'Format');
                                $value = call_user_func_array([$method[0], $method[1]], [$value, $object->data[$report_name]['header_summary'][$header_name][$v2['__index']]['format_options'] ?? []]);
                            }
                        } else {
                            $value = '';
                        }
                        $row[] = strip_tags2($value);
                    }
                    $result[] = $row;
                }
            }
            // render data
            foreach ($object->data[$report_name]['data'] as $row_number => $row_data) {
                if (!empty($row_data[2])) { // separator
                    $row = [' '];
                } elseif (!empty($row_data[4])) { // legend
                    $row = [strip_tags2($row_data[4])];
                } else { // regular rows
                    $header = $object->data[$report_name]['header'][$row_data[3]];
                    $row = [];
                    foreach ($header as $k2 => $v2) {
                        $value = $row_data[0][$v2['__index']] ?? '';
                        if (is_array($value)) {
                            $value = $value['value_export'] ?? $value['value'];
                        }
                        $row[] = strip_tags2($value);
                    }
                }
                $result[] = $row;
            }
            // summary
            if (!empty($object->data[$report_name]['header_summary'])) {
                foreach ($new_headers as $header_name => $header_data) {
                    if (empty($object->data[$report_name]['header_summary_calculated'][$header_name])) {
                        continue;
                    }
                    $row = [];
                    foreach ($header_data as $k2 => $v2) {
                        // render cell if not skipping
                        if (isset($object->data[$report_name]['header_summary_calculated'][$header_name][$v2['__index']])) {
                            $value = $object->data[$report_name]['header_summary_calculated'][$header_name][$v2['__index']]['final'];
                            if (!empty($object->data[$report_name]['header_summary'][$header_name][$v2['__index']]['format'])) {
                                $method = \Factory::method($object->data[$report_name]['header_summary'][$header_name][$v2['__index']]['format'], 'Format');
                                $value = call_user_func_array([$method[0], $method[1]], [$value, $object->data[$report_name]['header_summary'][$header_name][$v2['__index']]['format_options'] ?? []]);
                            }
                        } else {
                            $value = '';
                        }
                        $row[] = strip_tags2($value);
                    }
                    $result[] = $row;
                }
            }
            // add separator
            if ($report_counter != 1) {
                $result[] = [' '];
                $result[] = [' '];
                $result[] = [' '];
            }
            $report_counter++;
        }
        // render csv
        $export_model = new \Numbers\Backend\IO\Common\Base();
        $export_model->export('csv', ['Main Sheet' => $result], ['output_file_name' => str_replace(' ', '_', \Application::$controller->title) . '.csv']);
        return '';
    }
}
