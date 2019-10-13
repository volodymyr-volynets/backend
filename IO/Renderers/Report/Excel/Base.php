<?php

namespace Numbers\Backend\IO\Renderers\Report\Excel;
class Base {

	/**
	 * Render
	 *
	 * @param \Object\Form\Builder\Report $object
	 * @return string
	 */
	public function render(\Object\Form\Builder\Report & $object) : string {
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$spreadsheet->removeSheetByIndex($spreadsheet->getActiveSheetIndex());
		$report_counter = 0;
		foreach (array_keys($object->data) as $report_name) {
			// chart
			if ($object->data[$report_name]['options']['type'] == CHART) {
				continue;
			}
			$worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($spreadsheet, $object->data[$report_name]['form_name']);
			// title
			$worksheet->setCellValue('A1', i18n(null, \Application::$controller->title) . ' (#' . \Format::id(\Application::$controller->controller_id) . ')');
			$worksheet->setCellValue('A2', i18n(null, 'By:') . ' ' . \User::get('name') . ', ' . i18n(null, ' On:') . ' ' . \Format::id(\Format::datetime(\Format::now('datetime'))));
			// render filter
			if (!empty($object->data[$report_name]['filter'])) {
				$filter_counter = 4;
				foreach ($object->data[$report_name]['filter'] as $k => $v) {
					$worksheet->setCellValue('A' . $filter_counter, $k);
					$worksheet->setCellValue('B' . $filter_counter, $v);
					$filter_counter++;
				}
			}
			// render headers
			$filter_counter++;
			$new_headers = [];
			$result = [];
			foreach ($object->data[$report_name]['header'] as $header_name => $header_data) {
				if (!empty($object->data[$report_name]['header_options'][$header_name]['skip_rendering'])) continue;
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
				} else if (!empty($row_data[4])) { // legend
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
			// add data as array
			$worksheet->fromArray(
				$result,
				NULL,
				'A' . $filter_counter
			);
			// add sheet to the document
			$spreadsheet->addSheet($worksheet, $report_counter);
			$report_counter++;
		}
		// generate temp excel and save
		$filename = \Helper\File::tempDirectory(\Helper\File::generateTempFileName('xlsx'));
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer->save($filename);
		// render
		\Layout::renderAs(
			file_get_contents($filename),
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			[
				'output_file_name' => str_replace([' ', '/'], ['_', ''], \Application::$controller->title) . '.xlsx',
			]
		);
		return '';
	}
}