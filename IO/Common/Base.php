<?php

namespace Numbers\Backend\IO\Common;
class Base {

	/**
	 * Import
	 *
	 * @param string $format
	 * @param string $filename
	 * @param array $options
	 *		process_keys_and_values - whether process keys and values
	 * @return array
	 */
	public function import(string $format, string $filename, array $options = []) : array {
		$formats = \Object\Content\ImportFormats::getStatic();
		if (empty($formats[$format])) {
			Throw new \Exception('Unknown import format: ' . $format);
		}
		$class = $formats[$format]['model'];
		$model = new $class();
		$result = $model->import($format, $filename, $options);
		// if we need to process keys and values
		if (!empty($options['process_keys_and_values'])) {
			foreach ($result['data'] as $k => $v) {
				$result['data'][$k] = [];
				if (empty($v)) continue;
				$header = $v[0];
				unset($v[0]);
				foreach ($v as $k2 => $v2) {
					$result['data'][$k][$k2] = array_combine($header, $v2);
					// remove empty key
					unset($result['data'][$k][$k2]['']);
				}
			}
		}
		return $result;
	}

	/**
	 * Export
	 *
	 * @param string $format
	 * @param array $data
	 * @param array $options
	 *		output_file_name
	 * @return array
	 */
	public function export(string $format, array $data, array $options = []) : array {
		$formats = \Object\Content\ExportFormats::getStatic();
		if (empty($formats[$format])) {
			Throw new \Exception('Unknown import format: ' . $format);
		}
		$class = $formats[$format]['model'];
		$model = new $class();
		$result = $model->export($format, $data, $options);
		// output
		if ($result['success'] && !empty($options['output_file_name'])) {
			\Layout::renderAs($result['data'], $formats[$format]['content_type'], [
				'output_file_name' => $options['output_file_name']
			]);
		}
		return $result;
	}
}