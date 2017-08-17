<?php

namespace Numbers\Backend\Session\Db\Form\Report;
class SessionLog extends \Object\Form\Wrapper\Report {
	public $form_link = 'session_log_report';
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
			'date' => [
				'date1' => ['order' => 1, 'row_order' => 100, 'label_name' => 'Date & Time', 'type' => 'datetime', 'percent' => 25, 'method'=> 'calendar', 'calendar_icon' => 'right', 'null' => true, 'query_builder' => 'a.datetime;>='],
				'date2' => ['order' => 2, 'label_name' => 'Date & Time', 'type' => 'datetime', 'percent' => 25, 'method'=> 'calendar', 'calendar_icon' => 'right', 'null' => true, 'query_builder' => 'a.datetime;<='],
				'user_id1' => ['order' => 3, 'label_name' => 'User #', 'domain' => 'user_id', 'percent' => 25, 'null' => true, 'query_builder' => 'a.user_id;>='],
				'user_id2' => ['order' => 4, 'label_name' => 'User #', 'domain' => 'user_id', 'percent' => 25, 'null' => true, 'query_builder' => 'a.user_id;<='],
			],
			'active' => [
				'active' => ['order' => 1, 'row_order' => 200, 'label_name' => 'Active', 'type' => 'boolean', 'percent' => 50, 'method' => 'multiselect', 'multiple_column' => 1, 'options_model' => '\Object\Data\Model\Inactive', 'query_builder' => 'a.active;='],
				'user_ip1' => ['order' => 2, 'label_name' => 'User IP', 'domain' => 'ip', 'percent' => 25, 'null' => true, 'query_builder' => 'a.user_ip;>='],
				'user_ip2' => ['order' => 3, 'label_name' => 'User IP', 'domain' => 'ip', 'percent' => 25, 'null' => true, 'query_builder' => 'a.user_ip;<='],
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
		'pages_count' => ['name' => '# of Pages'],
		'session_count' => ['name' => '# of Sessions'],
	];
	public $report_default_sort = [
		'pages_count' => SORT_DESC
	];
	public function buildReport(& $form) {
		// create new report
		$report = new \Object\Form\Builder\Report();
		$report->addReport(DEF);
		// add header
		$report->addHeader(DEF, 'row1', [
			'user_id' => ['label_name' => 'User #', 'percent' => 10],
			'user_name' => ['label_name' => 'User Name', 'percent' => 40],
			'login_name' => ['label_name' => 'User Login', 'percent' => 15],
			'active' => ['label_name' => 'Active', 'percent' => 5, 'data_align' => 'center'],
			'session_count' => ['label_name' => '# of Sessions', 'percent' => 15, 'data_align' => 'right'],
			'pages_count' => ['label_name' => '# of Pages', 'percent' => 15, 'data_align' => 'right'],
		]);
		$report->addHeader(DEF, 'row2', [
			'balnk' => ['label_name' => ' ', 'percent' => 10],
			'user_ip' => ['label_name' => 'User IP', 'percent' => 40],
			'start_datetime' => ['label_name' => 'First Timestamp', 'percent' => 25],
			'end_datetime' => ['label_name' => 'Last Timestamp', 'percent' => 25]
		]);
		// query the data
		$model = new \Numbers\Backend\Session\Db\Model\Sessions();
		$inner_query = $model->queryBuilder(['alias' => 'inner_a'])->select();
		$inner_query->columns([
			'datetime' => 'sm_session_last_requested',
			'user_id' => 'sm_session_user_id',
			'user_ip' => 'sm_session_user_ip',
			'pages_count' => 'sm_session_pages_count',
			'active' => 1
		]);
		$inner_query->union('UNION ALL', function (& $query) {
			$query = \Numbers\Backend\Session\Db\Model\Session\History::queryBuilderStatic(['alias' => 'inner_b'])->select();
			// columns
			$query->columns([
				'datetime' => 'sm_sesshist_last_requested',
				'user_id' => 'sm_sesshist_user_id',
				'user_ip' => 'sm_sesshist_user_ip',
				'pages_count' => 'sm_sesshist_pages_count',
				'active' => 0
			]);
		});
		$query = new \Object\Query\Builder($model->db_link);
		$query->from($inner_query, 'a');
		$query->columns([
			'user_id' => 'a.user_id',
			'user_ip' => 'a.user_ip',
			'active' => 'a.active',
			'start_datetime' => 'MIN(a.datetime)',
			'end_datetime' => 'MAX(a.datetime)',
			'pages_count' => 'SUM(a.pages_count)',
			'session_count' => 'COUNT(*)',
			'user_name' => 'MIN(b.um_user_name)',
			'login_name' => 'MIN(COALESCE(b.um_user_login_username, b.um_user_email))'
		]);
		$query->join('LEFT', new \Numbers\Users\Users\Model\Users, 'b', 'ON', [
			['AND', ['a.user_id', '=', 'b.um_user_id', true], false]
		]);
		$query->groupby(['user_id', 'user_ip', 'active']);
		$form->processReportQueryFilter($query);
		$form->processReportQueryOrderBy($query);
		$data = $query->query(null, ['cache' => false]);
		$counter = 1;
		foreach ($data['rows'] as $k => $v) {
			// replaces
			$v['active'] = i18n(null, $v['active'] ? 'Yes' : 'No');
			$v['user_id'] = $v['user_id'] ? $v['user_id'] : i18n(null, 'Unauthorized');
			$v['start_datetime'] = \Format::datetime($v['start_datetime']);
			$v['end_datetime'] = \Format::datetime($v['end_datetime']);
			$even = $counter % 2 ? ODD : EVEN;
			$report->addData(DEF, 'row1', $even, $v);
			$report->addData(DEF, 'row2', $even, $v);
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