<?php

namespace Numbers\Backend\IO\CSV;
class Imports {

	/**
	 * Import
	 *
	 * @param string $format
	 * @param string $filename
	 * @return array
	 */
	public function import(string $format, string $filename) : array {
		$result = [
			'success' => false,
			'error' => [],
			'data' => null
		];
		$formats = \Object\Content\ImportFormats::getStatic();
		$delimiter = $formats[$format]['delimiter'];
		$enclosure = $formats[$format]['enclosure'];
		// open file for reading
		$temp = false;
		if (($handle = fopen($filename, 'r')) !== false) {
			while (($data = fgetcsv($handle, 0, $delimiter, $enclosure)) !== false) {
				$temp[] = $data;
			}
			fclose($handle);
		} else {
			$result['error'][] = 'Could not open a file for reading!';
			return $result;
		}
		$data = [];
		$data_index = 'Main Sheet';
		if (!empty($temp)) {
			foreach ($temp as $k => $v) {
				if (stripos($v[0], '(Sheet)') !== false) {
					$data_index = $v[1];
					continue;
				}
				$data[$data_index][] = $v;
			}
		}
		$result['success'] = true;
		$result['data'] = $data;
		return $result;
	}
}