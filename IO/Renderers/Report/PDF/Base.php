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
			// render filter
			if (!empty($object->data[$report_name]['filter'])) {
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
					foreach ($header_data as $k2 => $v2) {
						$object->data[$report_name]['header'][$header_name][$k2]['__label_name'] = strip_tags2($v2['label_name']);
						$object->data[$report_name]['header'][$header_name][$k2]['__mm'] = round(($pdf->getPageWidth() - 30) * ($v2['percent'] / 100), 2);
						$object->data[$report_name]['header'][$header_name][$k2]['__start'] = $start;
						// render cell if not skipping
						if (empty($object->data[$report_name]['header_options'][$header_name]['skip_rendering'])) {
							$align = str_replace(['left', 'right', 'center'], ['L', 'R', 'C'], $v2['align'] ?? 'left');
							$pdf->SetXY($start, $page_y);
							$pdf->Cell($object->data[$report_name]['header'][$header_name][$k2]['__mm'], 10, $object->data[$report_name]['header'][$header_name][$k2]['__label_name'], 0, false, $align, 0, '', 0, false, 'T', 'M');
						}
						// increment start
						$start+= $object->data[$report_name]['header'][$header_name][$k2]['__mm'];
					}
					if (empty($object->data[$report_name]['header_options'][$header_name]['skip_rendering'])) {
						$page_y+= 5;
					}
				}
				$pdf->Line(15, $page_y + 2.5, $pdf->getPageWidth() - 15, $page_y + 2.5);
			}
			// render data
			$page_y+= 0.25;
			$prev_odd_even = null;
			foreach ($object->data[$report_name]['data'] as $row_number => $row_data) {
				// set font
				if (!empty($row_data[2])) { // separator
					$page_y+= 5;
				} else if (!empty($row_data[4])) { // legend
					$pdf->SetFont($pdf->__options['font']['family'], '', $pdf->__options['font']['size']);
					$pdf->SetTextColorArray(hex2rgb('#000000'));
					$pdf->SetXY(15, $page_y);
					$pdf->Cell(0, 10, strip_tags2($row_data[4]), 0, false, 'L', 0, '', 0, false, 'T', 'M');
				} else { // regular rows
					$header = $object->data[$report_name]['header'][$row_data[3]];
					$row = [];
					foreach ($header as $k2 => $v2) {
						$value = $row_data[0][$v2['__index']] ?? '';
						$align = $v2['data_align'] ?? 'left';
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
							$pdf->SetLineStyle(['width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => hex2rgb('#d9d9d9')]);
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
							$pdf->SetLineStyle(['width' => 0, 'cap' => 'butt', 'join' => 'miter', 'dash' => 0, 'color' => hex2rgb('#000000')]);
							$pdf->Line($v2['__start'], $page_y + 7.5, $v2['__start'] + $v2['__mm'], $page_y + 7.5);
						}
						// render cell
						$pdf->SetXY($v2['__start'], $page_y);
						$pdf->Cell($v2['__mm'], 10, strip_tags2($value), 0, false, $align, 0, '', 0, false, 'T', 'M');
					}
				}
				$page_y+= 5;
				if ($page_y >= ($pdf->getPageHeight() - 25)) {
					$page_y = 25;
					$pdf->AddPage();
				}
				$prev_odd_even = $row_data[1] ?? null;
			}
			// add separator
			if ($report_counter != 1) {
				$pdf->AddPage();
			}
			$report_counter++;
		}
		// output
		$pdf->Output(str_replace(' ', '_', \Application::$controller->title) . '.pdf', 'I');
		exit;
		return '';
	}
}