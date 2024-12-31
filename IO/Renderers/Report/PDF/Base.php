<?php

/*
 * This file is part of Numbers Framework.
 *
 * (c) Volodymyr Volynets <volodymyr.volynets@gmail.com>
 *
 * This source file is subject to the Apache 2.0 license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Numbers\Backend\IO\Renderers\Report\PDF;

use Numbers\Backend\IO\PDF\Wrapper;
use Object\ACL\Resources;
use Object\Form\Builder\Report;

class Base
{
    /**
     * Aligns
     *
     * @var array
     */
    public static $aligns = [
        'left' => 'L',
        'right' => 'R',
        'center' => 'C'
    ];

    /**
     * Style
     *
     * @var array
     */
    public static $style = [
        '' => '',
        'bold' => 'B'
    ];

    /**
     * Page counter
     *
     * @var int
     */
    private int $page_counter = 0;

    /**
     * Row counter
     *
     * @var int
     */
    private int $row_counter = 0;

    /**
     * Positions
     *
     * @var array
     */
    private array $positions = [];

    /**
     * Render other objects
     *
     * @param int $page
     * @param Report $object
     * @param Wrapper $pdf
     */
    public function renderOtherObjects(Report & $object, Wrapper & $pdf, float & $page_y, array & $options)
    {
        foreach ($object->other as $k => $v) {
            switch ($v['type']) {
                case 'image':
                    $method = Resources::getStatic('generate_photo', 'get_file', 'method');
                    if (!empty($method)) {
                        $image_result = call_user_func_array(explode('::', $method), [$v['file_id']]);
                        if (!empty($image_result['data'])) {
                            $pdf->Image('@' . $image_result['data'], $v['x'], $v['y'], $v['w'], $v['h'], '', '', '', true, 300, '', false, false, 0, false, false, false);
                        }
                    }
                    break;
                case 'text':
                    if (strpos($v['x'], '%') !== false) {
                        $v['x'] = $pdf->getPageWidth() / 100 * ((float) str_replace('%', '', $v['x']));
                    }
                    if (strpos($v['w'], '%') !== false) {
                        $v['w'] = $pdf->getPageWidth() / 100 * ((float) str_replace('%', '', $v['w']));
                    }
                    if (strpos($v['y'], '~') !== false) {
                        $v['y'] = $page_y + ((float) str_replace('~', '', $v['y']));
                    }
                    $pdf->SetFont($v['options']['font_family'] ?? $pdf->__options['font']['family'], self::$style[$v['options']['font_style'] ?? ''], $v['options']['font_size'] ?? $pdf->__options['font']['size']);
                    $pdf->SetXY($v['x'], $v['y']);
                    $cells = $pdf->MultiCell($v['w'], $v['h'], $v['text'] . '', $v['options']['border'] ?? 1, self::$aligns[$v['options']['align'] ?? 'left'], 1, 0, '', '', true);
                    if (($v['y'] + $v['h']) > $page_y) {
                        $page_y = $v['y'] + $v['h'];
                    }
                    break;
                case 'setxy':
                    $pdf->SetXY($v['x'], $v['y']);
                    $page_y = $v['y'];
                    break;
                case 'list': // reports
                    // chart
                    if ($object->data[$v['report_name']]['options']['type'] == CHART) {
                        break;
                    }
                    // render filter
                    if (!empty($object->data[$v['report_name']]['filter'])) {
                        $pdf->SetFont($pdf->__options['font']['family'], '', $pdf->__options['font']['size']);
                        foreach ($object->data[$v['report_name']]['filter'] as $k8 => $v8) {
                            $pdf->SetXY($options['margin_x'], $page_y);
                            $pdf->MultiCell(50, 10, $k8 . ':', 0, 'L', 1, 0, '', '', true, 0, false, false, 50, 'T');
                            $pdf->SetXY(60, $page_y);
                            $cell_number = $pdf->MultiCell($pdf->getPageWidth() - 75, 10, $v8 . '', 0, 'L', 1, 0, '', '', true, 0, false, false, 50, 'T');
                            $page_y += 5 * $cell_number;
                        }
                        $page_y += 5;
                    }
                    // render header
                    $this->renderListHeader($v['report_name'], $object, $pdf, $page_y, $options);
                    // render data
                    $this->renderListData($v['report_name'], $object, $pdf, $page_y, $options);
                    break;
                case 'template': // templates
                    $options['skip_last_header_line'] = true;
                    $this->renderListHeader($v['report_name'], $object, $pdf, $page_y, $options);
                    $this->renderListData($v['report_name'], $object, $pdf, $page_y, $options);
                    break;
            }
        }
    }

    /**
     * Render list header
     *
     * @param type $report_name
     * @param Report $object
     * @param Wrapper $pdf
     * @param float $page_y
     * @param array $options
     */
    public function renderListHeader($report_name, Report & $object, Wrapper & $pdf, float & $page_y, array & $options)
    {
        // render headers
        $new_headers = [];
        foreach ($object->data[$report_name]['header'] as $header_name => $header_data) {
            $new_headers[$header_name] = $header_data;
        }
        // loop though headers
        if (!empty($new_headers)) {
            $pdf->SetFont($pdf->__options['font']['family'], 'B', $pdf->__options['font']['size']);
            if (empty($options['skip_last_header_line'])) {
                $pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
                $pdf->Line($options['margin_x'], $page_y + 2.5, $pdf->getPageWidth() - $options['margin_x'], $page_y + 2.5);
            }
            foreach ($object->data[$report_name]['header'] as $header_name => $header_data) {
                $start = $options['margin_x'];
                $max_cells = 1;
                foreach ($header_data as $k2 => $v2) {
                    $object->data[$report_name]['header'][$header_name][$k2]['__label_name'] = strip_tags2($v2['label_name']);
                    $object->data[$report_name]['header'][$header_name][$k2]['__mm'] = round(($pdf->getPageWidth() - $options['margin_2x']) * ($v2['percent'] / 100), 2);
                    $object->data[$report_name]['header'][$header_name][$k2]['__start'] = $start;
                    // render cell if not skipping
                    if (empty($object->data[$report_name]['header_options'][$header_name]['skip_rendering'])) {
                        $align = 'left';
                        $pdf->SetXY($start, $page_y + 2.5);
                        $temp = $pdf->MultiCell($object->data[$report_name]['header'][$header_name][$k2]['__mm'] + 2, 10, strip_tags2($object->data[$report_name]['header'][$header_name][$k2]['__label_name']) . '', 0, $align, false, 1, '', '', true, 0, false, true, 0, 'T', false);
                        if ($temp > $max_cells) {
                            $max_cells = $temp;
                        }
                    }
                    // left / right lines
                    if (!empty($object->data[$report_name]['header'][$header_name][$k2]['line'])) {
                        if (in_array('left', $object->data[$report_name]['header'][$header_name][$k2]['line'])) {
                            $pdf->Line($start, $page_y + 2.5, $start, $page_y + 2.5 + 5 * $max_cells);
                        }
                        if (in_array('right', $object->data[$report_name]['header'][$header_name][$k2]['line'])) {
                            $pdf->Line($start + $object->data[$report_name]['header'][$header_name][$k2]['__mm'], $page_y + 2.5, $start + $object->data[$report_name]['header'][$header_name][$k2]['__mm'], $page_y + 2.5 + 5 * $max_cells);
                        }
                    }
                    // increment start
                    $start += $object->data[$report_name]['header'][$header_name][$k2]['__mm'];
                }
                if (empty($object->data[$report_name]['header_options'][$header_name]['skip_rendering'])) {
                    $page_y += 5 * $max_cells;
                }
            }
            if (empty($options['skip_last_header_line'])) {
                $pdf->Line($options['margin_x'], $page_y + 2.5, $pdf->getPageWidth() - $options['margin_x'], $page_y + 2.5);
            }
        }
    }

    /**
     * Render list data
     *
     * @param string $report_name
     * @param Report $object
     * @param Wrapper $pdf
     * @param float $page_y
     * @param array $options
     */
    public function renderListData($report_name, Report & $object, Wrapper & $pdf, float & $page_y, array & $options)
    {
        // render data
        $page_y += 0.25;
        $prev_odd_even = null;
        foreach ($object->data[$report_name]['data'] as $row_number => $row_data) {
            // position
            $this->positions[$this->page_counter][$this->row_counter] = $page_y;
            $header_options = ($object->data[$report_name]['header_options'][$row_data[3]]);
            if (!empty($header_options['start_at_rows'])) {
                if (isset($this->positions[$this->page_counter][$header_options['start_at_rows']])) {
                    $page_y = $this->positions[$this->page_counter][$header_options['start_at_rows']];
                } else {
                    $page_y = $options['margin_y'] + $header_options['start_at_rows'] * 5;
                }
            } else {
                $page_y = end($this->positions[$this->page_counter]);
            }
            // set font
            $cell_counter = 1;
            if (!empty($row_data[2])) { // separator
                $page_y += 5;
                $page_y += $cell_counter * 5;
            } elseif (!empty($row_data[4])) { // legend
                $pdf->SetFont($pdf->__options['font']['family'], '', $pdf->__options['font']['size']);
                $pdf->SetTextColorArray(hex2rgb('#000000'));
                $pdf->SetXY($options['margin_x'], $page_y + 2.5);
                $temp = $pdf->MultiCell(0, 10, strip_tags2($row_data[4]) . '', 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);
                if ($temp > $cell_counter) {
                    $cell_counter = $temp;
                }
                $page_y += $cell_counter * 5;
            } else { // regular rows
                $row = [];
                $header = $object->data[$report_name]['header'][$row_data[3]];
                foreach ($header as $k2 => $v2) {
                    $value = $row_data[0][$v2['__index']] ?? '';
                    $align = $v2['data_align'] ?? $v2['align'] ?? 'left';
                    $bold = $v2['data_bold'] ?? false;
                    $total = $v2['data_total'] ?? false;
                    $subtotal = $v2['data_subtotal'] ?? false;
                    $underline = $v2['data_underline'] ?? false;
                    $as_header = $v2['data_as_header'] ?? false;
                    $alarm = false;
                    if (is_array($value)) {
                        $align = $value['align'] ?? $align;
                        $bold = $value['bold'] ?? $bold;
                        $underline = $value['underline'] ?? $underline;
                        $as_header = $value['as_header'] ?? $as_header;
                        $total = $value['total'] ?? $total;
                        $subtotal = $value['subtotal'] ?? $subtotal;
                        $alarm = $value['alarm'] ?? $alarm;
                        $value = $value['value'] ?? null;
                    }
                    $align = str_replace(['left', 'right', 'center'], ['L', 'R', 'C'], $align);
                    // global odd/even
                    /*
                    if ($row_data[1] == ODD) {
                        $pdf->Rect($v2['__start'], $page_y + 2.5, $v2['__mm'], 10, 'DF', $rectangle_style, hex2rgb('#f9f9f9'));
                    } else if ($row_data[1] == EVEN && $prev_odd_even != EVEN) {
                        $pdf->Rect($v2['__start'], $page_y + 2.5, $v2['__mm'], 10, 'DF', $rectangle_style, hex2rgb('#ffffff'));
                    }
                    */
                    if ($prev_odd_even != $row_data[1] && empty($v2['skip_odd_even_underline'])) {
                        $pdf->SetLineStyle(['width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => hex2rgb('#d0d0d0')]);
                        $pdf->Line($v2['__start'], $page_y + 2.5, $v2['__start'] + $v2['__mm'], $page_y + 2.5);
                    }
                    // inner odd/even
                    /*
                    if (isset($row_data[5]['cell_even']) && $value . '' != '') {
                        if ($row_data[5]['cell_even'] == ODD) {
                            $pdf->Rect($v2['__start'], $page_y + 2.5, $v2['__mm'], 10, 'DF', $rectangle_style, hex2rgb('#f9f9f9'));
                        } else if ($row_data[5]['cell_even'] == EVEN) {
                            $pdf->Rect($v2['__start'], $page_y + 2.5, $v2['__mm'], 10, 'DF', $rectangle_style, hex2rgb('#ffffff'));
                        }
                    }
                    */
                    // color
                    if ($alarm) {
                        $pdf->SetTextColorArray(hex2rgb('#ff0000'));
                    } else {
                        $pdf->SetTextColorArray(hex2rgb('#000000'));
                    }
                    // total
                    if ($total) {
                        $bold = true;
                        $pdf->SetLineStyle(['width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => hex2rgb('#000000')]);
                        $pdf->Line($v2['__start'], $page_y + 2.5, $v2['__start'] + $v2['__mm'], $page_y + 2.5);
                        $pdf->Line($v2['__start'], $page_y + 3, $v2['__start'] + $v2['__mm'], $page_y + 3);
                    }
                    if ($subtotal) {
                        $bold = true;
                        $pdf->SetLineStyle(['width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => hex2rgb('#000000')]);
                        $pdf->Line($v2['__start'], $page_y + 2.5, $v2['__start'] + $v2['__mm'], $page_y + 2.5);
                    }
                    // bold
                    if ($bold) {
                        $pdf->SetFont($v2['font_family'] ?? $pdf->__options['font']['family'], 'B', $v2['font_size'] ?? $pdf->__options['font']['size']);
                    } else {
                        $pdf->SetFont($v2['font_family'] ?? $pdf->__options['font']['family'], $v2['font_style'] ?? '', $v2['font_size'] ?? $pdf->__options['font']['size']);
                    }
                    if ($as_header) {
                        $pdf->SetFont($v2['font_family'] ?? $pdf->__options['font']['family'], 'I', $v2['font_size'] ?? $pdf->__options['font']['size']);
                        $pdf->Line($v2['__start'], $page_y + 7.5, $v2['__start'] + $v2['__mm'], $page_y + 7.5);
                    }
                    if ($underline == 'black') {
                        $pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
                        $pdf->Line($v2['__start'], $page_y + 7.5, $v2['__start'] + $v2['__mm'], $page_y + 7.5);
                    } elseif ($underline || $as_header) {
                        $pdf->SetLineStyle(['width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => hex2rgb('#d0d0d0')]);
                        $pdf->Line($v2['__start'], $page_y + 7.5, $v2['__start'] + $v2['__mm'], $page_y + 7.5);
                    }
                    // image
                    if (!empty($v2['__file_id'])) {
                        $method = Resources::getStatic('generate_photo', 'get_file', 'method');
                        if (!empty($method)) {
                            $image_result = call_user_func_array(explode('::', $method), [$value]);
                            if (!empty($image_result['data'])) {
                                $image = imagecreatefromstring($image_result['data']);
                                $width = imagesx($image);
                                $height = imagesy($image);
                                $aspect_ratio = $height / $width;
                                $new_size = $v2['__mm'] * $pdf->getImageScale() * 2.8346456692913;
                                if ($width <= $new_size) {
                                    $new_w = $width;
                                    $new_h = $height;
                                } else {
                                    $new_w = $new_size;
                                    $new_h = abs($new_w * $aspect_ratio);
                                }
                                imagedestroy($image);
                                $pdf->Image('@' . $image_result['data'], $v2['__start'], $page_y, $pdf->pixelsToUnits($new_w), $pdf->pixelsToUnits($new_h), '', '', '', true, 300, '', false, false, 0, false, false, false);
                            }
                        }
                    } else {
                        // render cell
                        $pdf->SetXY($v2['__start'], $page_y + 2.5);
                        if (is_array($value)) {
                            $value = $value['value'] ?? '';
                        }
                        // padding
                        if (isset($v2['padding_left']) || isset($v2['padding_right']) || isset($v2['padding_top']) || isset($v2['padding_bottom'])) {
                            $pdf->setCellPaddings($pdf->pixelsToUnits($v2['padding_left'] ?? 0), $pdf->pixelsToUnits($v2['padding_top'] ?? 0), $pdf->pixelsToUnits($v2['padding_right'] ?? 0), $pdf->pixelsToUnits($v2['padding_bottom'] ?? 0));
                        }
                        // border
                        $border = [];
                        if (isset($v2['border_left'])) {
                            $temp = explode(' ', $v2['border_left']);
                            $border['L'] = ['width' => $pdf->pixelsToUnits(str_replace('px', '', $temp[0])), 'cap' => 'butt', 'join' => 'miter', 'dash' => $temp[1] == 'solid' ? 0 : 1, 'color' => \HTML::color2rgb($temp[2])];
                        }
                        if (isset($v2['border_top'])) {
                            $temp = explode(' ', $v2['border_top']);
                            $border['L'] = ['width' => $pdf->pixelsToUnits(str_replace('px', '', $temp[0])), 'cap' => 'butt', 'join' => 'miter', 'dash' => $temp[1] == 'solid' ? 0 : 1, 'color' => \HTML::color2rgb($temp[2])];
                        }
                        if (isset($v2['border_right'])) {
                            $temp = explode(' ', $v2['border_right']);
                            $border['L'] = ['width' => $pdf->pixelsToUnits(str_replace('px', '', $temp[0])), 'cap' => 'butt', 'join' => 'miter', 'dash' => $temp[1] == 'solid' ? 0 : 1, 'color' => \HTML::color2rgb($temp[2])];
                        }
                        if (isset($v2['border_bottom'])) {
                            $temp = explode(' ', $v2['border_bottom']);
                            $border['L'] = ['width' => $pdf->pixelsToUnits(str_replace('px', '', $temp[0])), 'cap' => 'butt', 'join' => 'miter', 'dash' => $temp[1] == 'solid' ? 0 : 1, 'color' => \HTML::color2rgb($temp[2])];
                        }
                        // render cell
                        $temp = $pdf->MultiCell($v2['__mm'], 10, strip_tags2($value) ?? '', $border ? $border : 0, $align, false, 1, '', '', true, 0, false, true, 0, 'T', false);
                        if ($temp > $cell_counter) {
                            $cell_counter = $temp;
                        }
                        // reset cell padddings
                        $pdf->setCellPaddings(0, 0, 0, 0);
                    }
                    // render cell
                    /*
                    $pdf->SetXY($v2['__start'], $page_y + 2.5);
                    if (is_array($value)) {
                        $value = $value['value'] ?? '';
                    }
                    $temp = $pdf->MultiCell($v2['__mm'], 10, strip_tags2($value) . '', 0, $align, false, 1, '', '', true, 0, false, true, 0, 'T', false);
                    if ($temp > $cell_counter) {
                        $cell_counter = $temp;
                    }
                    */
                }
                // left / right lines
                foreach ($header as $k2 => $v2) {
                    if (!empty($v2['line'])) {
                        $pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
                        if (in_array('left', $v2['line'])) {
                            $pdf->Line($v2['__start'], $page_y + 2.5, $v2['__start'], $page_y + 2.5 + 5 * $cell_counter);
                        }
                        if (in_array('right', $v2['line'])) {
                            $pdf->Line($v2['__start'] + $v2['__mm'], $page_y + 2.5, $v2['__start'] + $v2['__mm'], $page_y + 2.5 + 5 * $cell_counter);
                        }
                    }
                }
                $page_y += $cell_counter * 5;
                $this->row_counter++;
                // bottom line
                if ($page_y >= ($pdf->getPageHeight() - $options['margin_y'])) {
                    if (!empty($object->data[$report_name]['options']['line_bottom_new_page'])) {
                        foreach ($header as $k2 => $v2) {
                            $pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
                            $pdf->Line($v2['__start'], $page_y + 2.5, $v2['__start'] + $v2['__mm'], $page_y + 2.5);
                        }
                    }
                }
            }
            if ($page_y >= ($pdf->getPageHeight() - $options['margin_y'])) {
                $page_y = $options['margin_y'];
                $pdf->AddPage();
                $this->page_counter++;
                $this->row_counter = 1;
                // add header
                if (!empty($object->data[$report_name]['options']['reprint_header_per_page'])) {
                    $this->renderListHeader($report_name, $object, $pdf, $page_y, $options);
                }
            }
            $prev_odd_even = $row_data[1] ?? null;
        }
        // bottom line
        if (!empty($object->data[$report_name]['options']['line_bottom_new_page'])) {
            foreach ($header as $k2 => $v2) {
                $pdf->SetLineStyle(array('width' => 0.25, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
                $pdf->Line($v2['__start'], $page_y + 2.5, $v2['__start'] + $v2['__mm'], $page_y + 2.5);
            }
        }
    }

    /**
     * Render
     *
     * @param Report $object
     * @return string
     */
    public function render(Report & $object): string
    {
        // create new PDF document
        $pdf = new Wrapper($object->options['pdf'] ?? []);
        $pdf->AddPage();
        $this->page_counter = 1;
        $this->row_counter = 1;
        $options = [];
        $options['margin_x'] = $object->options['pdf']['margin_x'] ?? 15;
        $options['margin_2x'] = $options['margin_x'] * 2;
        $options['margin_y'] = $object->options['pdf']['margin_y'] ?? 25;
        $options['margin_2y'] = $options['margin_y'] * 2;
        $options['rectangle_style'] = ['width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => [255, 0, 0]];
        $page_y = $options['margin_y'];
        // render other objects first
        $this->renderOtherObjects($object, $pdf, $page_y, $options);
        // output
        $pdf->Output(str_replace(' ', '_', \Application::$controller->title) . '.pdf', 'I');
        exit;
        return '';
    }
}
