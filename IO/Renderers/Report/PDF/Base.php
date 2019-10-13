<?php

namespace Numbers\Backend\IO\Renderers\Report\PDF;
class Base {

	/**
	 * Render
	 *
	 * @param \Object\Form\Builder\Report $object
	 * @return string
	 */
	public function render(\Object\Form\Builder\Report & $object) : string {
		// create new PDF document
		$pdf = new \Numbers\Backend\IO\PDF\Wrapper($object->options['pdf'] ?? []);
		$pdf->AddPage();
		$page_y = 25;
		$rectangle_style = ['width' => 0.5, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'phase' => 10, 'color' => [255, 0, 0]];
		// render results
		$report_counter = 1;
		foreach (array_keys($object->data) as $report_name) {
			// chart
			if ($object->data[$report_name]['options']['type'] == CHART) {
				continue;
			}
			// render filter
			if (!empty($object->data[$report_name]['filter'])) {
				$pdf->SetFont($pdf->__options['font']['family'], '', $pdf->__options['font']['size']);
				foreach ($object->data[$report_name]['filter'] as $k => $v) {
					$pdf->SetXY(15, $page_y);
					$pdf->MultiCell(50, 10, $k . ':', 0, 'L', 1, 0, '', '', true, 0, false, false, 50, 'T');
					$pdf->SetXY(60, $page_y);
					$cell_number = $pdf->MultiCell($pdf->getPageWidth() - 75, 10, $v, 0, 'L', 1, 0, '', '', true, 0, false, false, 50, 'T');
					$page_y+= 5 * $cell_number;
				}
				$page_y+= 5;
			}
			// render headers
			$new_headers = [];
			foreach ($object->data[$report_name]['header'] as $header_name => $header_data) {
				$new_headers[$header_name] = $header_data;
			}
			// loop though headers
			if (!empty($new_headers)) {
				$pdf->SetFont($pdf->__options['font']['family'], 'B', $pdf->__options['font']['size']);
				$pdf->SetLineStyle(array('width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
				$pdf->Line(15, $page_y + 2.5, $pdf->getPageWidth() - 15, $page_y + 2.5);
				foreach ($object->data[$report_name]['header'] as $header_name => $header_data) {
					$start = 15;
					$max_cells = 1;
					foreach ($header_data as $k2 => $v2) {
						$object->data[$report_name]['header'][$header_name][$k2]['__label_name'] = strip_tags2($v2['label_name']);
						$object->data[$report_name]['header'][$header_name][$k2]['__mm'] = round(($pdf->getPageWidth() - 30) * ($v2['percent'] / 100), 2);
						$object->data[$report_name]['header'][$header_name][$k2]['__start'] = $start;
						// render cell if not skipping
						if (empty($object->data[$report_name]['header_options'][$header_name]['skip_rendering'])) {
							$align = 'left';
							$pdf->SetXY($start, $page_y + 2.5);
							$temp = $pdf->MultiCell($object->data[$report_name]['header'][$header_name][$k2]['__mm'] + 2, 10, strip_tags2($object->data[$report_name]['header'][$header_name][$k2]['__label_name']), 0, $align, false, 1, '', '', true, 0, false, true, 0, 'T', false);
							if ($temp > $max_cells) {
								$max_cells = $temp;
							}
						}
						// increment start
						$start+= $object->data[$report_name]['header'][$header_name][$k2]['__mm'];
					}
					if (empty($object->data[$report_name]['header_options'][$header_name]['skip_rendering'])) {
						$page_y+= 5 * $max_cells;
					}
				}
				$pdf->Line(15, $page_y + 2.5, $pdf->getPageWidth() - 15, $page_y + 2.5);
			}
			// summary
			if (!empty($object->data[$report_name]['header_summary'])) {
				$object->calculateSummary($report_name);
				$counter = 1;
				foreach ($new_headers as $header_name => $header_data) {
					if (empty($object->data[$report_name]['header_summary_calculated'][$header_name])) {
						continue;
					}
					$start = 15;
					$max_cells = 1;
					foreach ($header_data as $k2 => $v2) {
						$object->data[$report_name]['header'][$header_name][$k2]['__label_name'] = strip_tags2($v2['label_name']);
						$object->data[$report_name]['header'][$header_name][$k2]['__mm'] = round(($pdf->getPageWidth() - 30) * ($v2['percent'] / 100), 2);
						$object->data[$report_name]['header'][$header_name][$k2]['__start'] = $start;
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
						$align = str_replace(['left', 'right', 'center'], ['L', 'R', 'C'], $v2['align'] ?? 'left');
						$pdf->SetXY($start, $page_y + 2.5);
						$pdf->SetFillColor(255, 255, 0);
						$temp = $pdf->MultiCell($object->data[$report_name]['header'][$header_name][$k2]['__mm'] + 0, 5, $value, 0, $align, true, 1, '', '', true, 0, false, true, 0, 'T', false);
						$pdf->SetFillColor(255, 255, 255);
						if ($temp > $max_cells) {
							$max_cells = $temp;
						}
						// increment start
						$start+= $object->data[$report_name]['header'][$header_name][$k2]['__mm'];
					}
					$page_y+= 5 * $max_cells;
					$counter++;
				}
				$pdf->SetLineStyle(array('width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
				$pdf->Line(15, $page_y + 2.1, $pdf->getPageWidth() - 15, $page_y + 2.1);
				$pdf->Line(15, $page_y + 2.6, $pdf->getPageWidth() - 15, $page_y + 2.6);
			}
			// render data
			$page_y+= 0.25;
			$prev_odd_even = null;
			foreach ($object->data[$report_name]['data'] as $row_number => $row_data) {
				// set font
				$cell_counter = 1;
				if (!empty($row_data[2])) { // separator
					$page_y+= 5;
				} else if (!empty($row_data[4])) { // legend
					$pdf->SetFont($pdf->__options['font']['family'], '', $pdf->__options['font']['size']);
					$pdf->SetTextColorArray(hex2rgb('#000000'));
					$pdf->SetXY(15, $page_y + 2.5);
					$temp = $pdf->MultiCell(0, 10, strip_tags2($row_data[4]), 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);
					if ($temp > $cell_counter) {
						$cell_counter = $temp;
					}
				} else { // regular rows
					$header = $object->data[$report_name]['header'][$row_data[3]];
					$row = [];
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
						if ($prev_odd_even != $row_data[1]) {
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
							$pdf->SetFont($pdf->__options['font']['family'], 'B', $pdf->__options['font']['size']);
						} else {
							$pdf->SetFont($pdf->__options['font']['family'], '', $pdf->__options['font']['size']);
						}
						if ($as_header) {
							$pdf->SetFont($pdf->__options['font']['family'], 'I', $pdf->__options['font']['size']);
							$pdf->Line($v2['__start'], $page_y + 7.5, $v2['__start'] + $v2['__mm'], $page_y + 7.5);
						}
						if ($underline || $as_header) {
							$pdf->SetLineStyle(['width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => hex2rgb('#d0d0d0')]);
							$pdf->Line($v2['__start'], $page_y + 7.5, $v2['__start'] + $v2['__mm'], $page_y + 7.5);
						}
						// render cell
						$pdf->SetXY($v2['__start'], $page_y + 2.5);
						if (is_array($value)) {
							$value = $value['value'] ?? '';
						}
						$temp = $pdf->MultiCell($v2['__mm'], 10, strip_tags2($value), 0, $align, false, 1, '', '', true, 0, false, true, 0, 'T', false);
						if ($temp > $cell_counter) {
							$cell_counter = $temp;
						}
					}
				}
				$page_y+= $cell_counter * 5;
				if ($page_y >= ($pdf->getPageHeight() - 25)) {
					$page_y = 25;
					$pdf->AddPage();
				}
				$prev_odd_even = $row_data[1] ?? null;
			}
			// summary
			if (!empty($object->data[$report_name]['header_summary'])) {
				$counter = 1;
				$pdf->SetFont($pdf->__options['font']['family'], 'B', $pdf->__options['font']['size']);
				foreach ($new_headers as $header_name => $header_data) {
					if (empty($object->data[$report_name]['header_summary_calculated'][$header_name])) {
						continue;
					}
					$start = 15;
					$max_cells = 1;
					foreach ($header_data as $k2 => $v2) {
						$object->data[$report_name]['header'][$header_name][$k2]['__label_name'] = strip_tags2($v2['label_name']);
						$object->data[$report_name]['header'][$header_name][$k2]['__mm'] = round(($pdf->getPageWidth() - 30) * ($v2['percent'] / 100), 2);
						$object->data[$report_name]['header'][$header_name][$k2]['__start'] = $start;
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
						$align = str_replace(['left', 'right', 'center'], ['L', 'R', 'C'], $v2['align'] ?? 'left');
						$pdf->SetXY($start, $page_y + 2.5);
						$pdf->SetFillColor(255, 255, 0);
						$temp = $pdf->MultiCell($object->data[$report_name]['header'][$header_name][$k2]['__mm'] + 0, 5, $value, 0, $align, true, 1, '', '', true, 0, false, true, 0, 'T', false);
						$pdf->SetFillColor(255, 255, 255);
						if ($temp > $max_cells) {
							$max_cells = $temp;
						}
						// increment start
						$start+= $object->data[$report_name]['header'][$header_name][$k2]['__mm'];
					}
					$page_y+= 5 * $max_cells;
					$counter++;
				}
				$pdf->SetLineStyle(array('width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => array(0, 0, 0)));
				$pdf->Line(15, $page_y - 2.5, $pdf->getPageWidth() - 15, $page_y - 2.5);
				$pdf->Line(15, $page_y + 2.1, $pdf->getPageWidth() - 15, $page_y + 2.1);
				$pdf->Line(15, $page_y + 2.6, $pdf->getPageWidth() - 15, $page_y + 2.6);
			}
			// legends after
			if (!empty($object->data[$report_name]['data_legend'])) {
				$row_number = PHP_INT_MAX - 1000;
				$page_y+= 0.25;
				$cell_counter = 1;
				foreach ($object->data[$report_name]['data_legend'] as $row_number2 => $row_data) {
					if (!empty($row_data[2])) { // separator
						$page_y+= 5;
					} else if (!empty($row_data[4])) { // legend
						$pdf->SetFont($pdf->__options['font']['family'], '', $pdf->__options['font']['size']);
						$pdf->SetTextColorArray(hex2rgb('#000000'));
						$pdf->SetXY(15, $page_y + 2.5);
						$temp = $pdf->MultiCell(0, 10, strip_tags2($row_data[4]), 0, 'L', false, 1, '', '', true, 0, false, true, 0, 'T', false);
						if ($temp > $cell_counter) {
							$cell_counter = $temp;
						}
					}
					$page_y+= $cell_counter * 5;
					if ($page_y >= ($pdf->getPageHeight() - 25)) {
						$page_y = 25;
						$pdf->AddPage();
					}
					$row_number++;
				}
			}
			$report_counter++;
		}
		// output
		$pdf->Output(str_replace(' ', '_', \Application::$controller->title) . '.pdf', 'I');
		exit;
		return '';
	}
}