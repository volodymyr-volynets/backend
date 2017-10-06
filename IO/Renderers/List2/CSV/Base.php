<?php

namespace Numbers\Backend\IO\Renderers\List2\CSV;
class Base {

	/**
	 * Render
	 *
	 * @param \Object\Form\Base $object
	 * @return string
	 */
	public function render(\Object\Form\Base & $object) : string {
		// use report builder
		$report = new \Object\Form\Builder\Report();
		$report->addReport(DEF);
		// add header
		$header = [];
		foreach ($object->misc_settings['list']['columns'] as $k => $v) {
			$temp = [];
			foreach ($v['elements'] as $k2 => $v2) {
				$temp[$k2] = $v2['options'];
			}
			$report->addHeader(DEF, $k, $temp);
			$header[$k] = $k;
		}
		// add data
		foreach ($object->misc_settings['list']['rows'] as $k => $v) {
			foreach ($header as $v2) {
				foreach ($object->misc_settings['list']['columns'][$v2]['elements'] as $k3 => $v3) {
					// format
					if (!empty($v3['options']['format']) && empty($v3['options']['options_model'])) {
						$method = \Factory::method($v3['options']['format'], 'Format');
						$v[$k3] = call_user_func_array([$method[0], $method[1]], [$v[$k3] ?? null, $v3['options']['format_options'] ?? []]);
					}
					// custom renderer
					if (!empty($v3['options']['custom_renderer'])) {
						$method = \Factory::method($v3['options']['custom_renderer'], null, true);
						$v[$k3] = call_user_func_array($method, [& $this->object, & $v3, & $v[$k3], & $v]);
					} else {
						// process options
						if (!empty($v3['options']['options_model'])) {
							$v[$k3] = $object->renderListContainerDefaultOptions($v3['options'], $v[$k3], $v);
						}
					}
				}
				$report->addData(DEF, $v2, 0, $v);
			}
			// gc
			unset($object->misc_settings['list']['rows'][$k]);
		}
		// add number of rows
		$report->addSeparator(DEF);
		$report->addLegend(DEF, i18n(null, \Object\Content\Messages::REPORT_ROWS_NUMBER, ['replace' => ['[Number]' => \Format::id($object->misc_settings['list']['num_rows'])]]));
		// render CSV through report renderer
		$renderer = new \Numbers\Backend\IO\Renderers\Report\CSV\Base();
		return $renderer->render($report);
	}
}