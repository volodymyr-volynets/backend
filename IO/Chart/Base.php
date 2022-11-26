<?php

namespace Numbers\Backend\IO\Chart;
class Base {

	/**
	 * Add media to layout
	 */
	public static function add() {
		\Layout::addJs('/composer/nnnick/chartjs/dist/Chart.min.js', 10000);
		\Layout::addCss('/composer/nnnick/chartjs/dist/Chart.min.css', 10000);
	}

	/**
	 * Render
	 *
	 * @param \Object\Form\Builder\Report $object
	 * @param string $report_name
	 */
	public static function render(\Object\Form\Builder\Report & $object, string $report_name) : string {
		\Numbers\Backend\IO\Chart\Base::add();
		$result = '';
		$result.= '<div id="canvas-holder-' . $report_name . '" style="width:100%">';
			$result.= '<canvas id="chart-area-' . $report_name . '"></canvas>';
		$result.= '</div>';
		// assemble config variable
		$config = [
			'type' => $object->data[$report_name]['options']['chart_type'],
			'data' => [
				'datasets' => [],
				'labels' => []
			],
			'options' => [
				'responsive' => true,
				'legend' => [
					'position' => 'right'
				]
			]
		];
		// scales
		if (!empty($object->data[$report_name]['options']['chart_scales'])) {
			$config['options']['scales'] = $object->data[$report_name]['options']['chart_scales'];
		}
		// skip legend
		if (!empty($object->data[$report_name]['options']['chart_skip_legend'])) {
			unset($config['options']['legend']);
			$config['options']['legend'] = false;
		}
		$counter = 1;
		$only_calculate = false;
		$other_values = [];
		$indexes = [];
		foreach ($object->data[$report_name]['data'] as $row_number => $row_data) {
			$header = $object->data[$report_name]['header'][$row_data[3]];
			$last_label = '';
			foreach ($header as $k2 => $v2) {
				if ($only_calculate) {
					if ($k2 == $object->data[$report_name]['options']['chart_labels_column']) {
						continue;
					}
					if (!isset($other_values[$v2['__index']])) {
						$other_values[$v2['__index']] = 0;
					}
					$other_values[$v2['__index']]+= $row_data[0][$v2['__index']];
					continue;
				}
				if ($k2 == $object->data[$report_name]['options']['chart_labels_column']) {
					$last_label = $row_data[0][$v2['__index']];
					if (!isset($last_label)) {
						$last_label = 'Empty';
					}
					$config['data']['labels'][] = $last_label;
					continue;
				}
				if (!in_array($v2['__index'], $indexes)) {
					$indexes[]= $v2['__index'];
				}
				$current_index = array_search($v2['__index'], $indexes);
				if (!isset($config['data']['datasets'][$current_index])) {
					$config['data']['datasets'][$current_index] = [
						'data' => [],
						'backgroundColor' => [],
						'label' => $v2['label_name'],
					];
				}
				$config['data']['datasets'][$current_index]['data'][] = $row_data[0][$v2['__index']] ?? 0;
				$config['data']['datasets'][$current_index]['backgroundColor'][] = \Numbers\Frontend\HTML\Renderers\Common\Colors::colorFromString($config['data']['datasets'][$current_index]['label'] . ' - ' . $row_data[0][$v2['__index']]);
			}
			if (!empty($object->data[$report_name]['options']['chart_limit'])) {
				if ($counter >= $object->data[$report_name]['options']['chart_limit']) {
					$only_calculate = true;
				}
			}
			$counter++;
		}
		if (!empty($other_values)) {
			$config['data']['labels'][] = 'Other';
			$last_label = 'Other';
			foreach ($other_values as $k => $v) {
				$current_index = array_search($k, $indexes);
				if (!isset($config['data']['datasets'][$current_index])) {
					$config['data']['datasets'][$current_index] = [
						'data' => [],
						'backgroundColor' => [],
						'label' => $last_label,
					];
				}
				$config['data']['datasets'][$current_index]['data'][] = $v;
				$config['data']['datasets'][$current_index]['backgroundColor'][] = \Numbers\Frontend\HTML\Renderers\Common\Colors::colorFromString($last_label);
			}
		}
		$config = json_encode($config);
		$js = <<<TTT
			var config_{$report_name} = {$config};
			var ctx_{$report_name} = document.getElementById('chart-area-{$report_name}').getContext('2d');
			window.myDoughnut = new Chart(ctx_{$report_name}, config_{$report_name});
TTT;
		\Layout::onLoad($js);
		return $result;
	}
}