<?php

namespace Numbers\Backend\IO\Renderers\List2\Dump;
class Base {

	/**
	 * Render
	 *
	 * @param \Object\Form\Base $object
	 * @return string
	 */
	public function render(\Object\Form\Base & $object) : string {
		$class = $object->form_parent->query_primary_model;
		$model = new $class();
		$columns = $model->columns;
		foreach ($columns as $k => $v) {
			// skip certain domains
			if (in_array($v['domain'] ?? '', ['tenant_id', 'signature', 'password'])) {
				unset($columns[$k]);
				continue;
			}
			$columns[$k]['label_name'] = $v['name'];
		}
		// use report builder
		$report = new \Object\Form\Builder\Report();
		// add header
		$report->addHeader(DEF, 'main', $columns);
		// add data
		foreach ($object->misc_settings['list']['rows'] as $k => $v) {
			$v_original = $v;
			foreach ($columns as $k3 => $v3) {
				// booleans
				if ($v3['type'] == 'boolean') {
					$v3['options_model'] = '\Object\Data\Model\Inactive';
				}
				// format
				if (!empty($v3['format']) && empty($v3['options_model'])) {
					$method = \Factory::method($v3['format'], 'Format');
					$v[$k3] = call_user_func_array([$method[0], $method[1]], [$v[$k3] ?? null, $v3['format_options'] ?? []]);
				}
				// custom renderer
				if (!empty($v3['custom_renderer'])) {
					$method = \Factory::method($v3['custom_renderer'], null, true);
					$v[$k3] = call_user_func_array($method, [& $this->object, & $v3, & $v[$k3], & $v]);
				} else {
					// process options
					if (!empty($v3['options_model'])) {
						$v[$k3] = $object->renderListContainerDefaultOptions($v3, $v[$k3], $v_original);
					}
				}
			}
			$report->addData(DEF, 'main', 0, $v);
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