<?php

namespace Numbers\Backend\Db\Common\Form\Report;
class DataClassification extends \Object\Form\Wrapper\Report {
	public $form_link = 'data_classification_report';
	public $options = [
		'segment' => self::SEGMENT_REPORT,
		'actions' => [
			'refresh' => true,
			'filter_sort' => ['value' => 'Filter/Sort', 'sort' => 32000, 'icon' => 'filter', 'onclick' => 'Numbers.Form.listFilterSortToggle(this);']
		]
	];
	public $containers = [
		'tabs' => ['default_row_type' => 'grid', 'order' => 1000, 'type' => 'tabs', 'class' => 'numbers_form_filter_sort_container'],
		'filter' => ['default_row_type' => 'grid', 'order' => 1500],
		'sort' => self::LIST_SORT_CONTAINER,
		self::REPORT_BUTTONS => ['default_row_type' => 'grid', 'order' => 2000, 'class' => 'numbers_form_filter_sort_container'],
	];
	public $rows = [
		'tabs' => [
			'filter' => ['order' => 100, 'label_name' => 'Filter'],
			'sort' => ['order' => 200, 'label_name' => 'Sort'],
		]
	];
	public $elements = [
		'tabs' => [
			'filter' => [
				'filter' => ['container' => 'filter', 'order' => 100]
			],
			'sort' => [
				'sort' => ['container' => 'sort', 'order' => 100]
			]
		],
		'filter' => [
			'sm_model_id' => [
				'sm_model_id1' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Model #', 'domain' => 'group_id', 'percent' => 50, 'null' => true, 'query_builder' => 'a.sm_model_id;>='],
				'sm_model_id2' => ['order' => 2, 'label_name' => 'Model #', 'domain' => 'group_id', 'percent' => 50, 'null' => true, 'query_builder' => 'a.sm_model_id;<='],
			],
			'full_text_search' => [
				'full_text_search' => ['order' => 1, 'row_order' => 300, 'label_name' => 'Text Search', 'full_text_search_columns' => ['a.sm_model_name'], 'placeholder' => true, 'domain' => 'name', 'percent' => 100, 'null' => true],
			]
		],
		'sort' => [
			'__sort' => [
				'__sort' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Sort', 'domain' => 'code', 'details_unique_select' => true, 'percent' => 50, 'null' => true, 'method' => 'select', 'options' => self::REPORT_SORT_OPTIONS, 'onchange' => 'this.form.submit();'],
				'__order' => ['order' => 2, 'label_name' => 'Order', 'type' => 'smallint', 'default' => SORT_ASC, 'percent' => 50, 'null' => true, 'method' => 'select', 'no_choose' => true, 'options_model' => '\Object\Data\Model\Order', 'onchange' => 'this.form.submit();'],
			]
		],
		self::REPORT_BUTTONS => self::REPORT_BUTTONS_DATA
	];
	const REPORT_SORT_OPTIONS = [
		'sm_model_id' => ['name' => 'Model #'],
		'sm_model_name' => ['name' => 'Model Name'],
	];
	public $report_default_sort = [
		'sm_model_name' => SORT_ASC
	];
	public function buildReport(& $form) {
		// create new report
		$report = new \Object\Form\Builder\Report();
		$report->addReport(DEF);
		// add header
		$report->addHeader(DEF, 'row1', [
			'sm_model_id' => ['label_name' => 'Model #', 'percent' => 10],
			'sm_model_name' => ['label_name' => 'Model Name', 'percent' => 40],
			'sm_model_da_classification' => ['label_name' => 'Classification', 'percent' => 30],
			'sm_model_da_protection' => ['label_name' => 'Protection', 'percent' => 10, 'data_align' => 'right'],
			'sm_model_da_scope' => ['label_name' => 'Scope', 'percent' => 10],
		]);
		// query the data
		$query = \Numbers\Backend\Db\Common\Model\Models::queryBuilderStatic()->select();
		$query->columns([
			'sm_model_id',
			'sm_model_name',
			'sm_model_da_classification',
			'sm_model_da_protection',
			'sm_model_da_scope'
		]);
		$form->processReportQueryFilter($query);
		$form->processReportQueryOrderBy($query);
		$data = $query->query('sm_model_id', ['cache' => false]);
		$counter = 1;
		foreach ($data['rows'] as $k => $v) {
			$report->addData(DEF, 'row1', $counter % 2 ? ODD : EVEN, $v);
			$counter++;
		}
		$report->addSeparator(DEF);
		// add number of rows
		$rows = count($data['rows']);
		$report->addLegend(DEF, i18n(null, \Object\Content\Messages::REPORT_ROWS_NUMBER, ['replace' => ['[Number]' => $rows]]));
		// free up memory
		unset($data);
		// we must return report object
		return $report;
	}
}