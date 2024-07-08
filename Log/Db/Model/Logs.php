<?php

namespace Numbers\Backend\Log\Db\Model;
class Logs extends \Object\Table {
	public $db_link;
	public $db_link_flag;
	public $module_code = 'SM';
	public $title = 'S/M Logs';
	public $name = 'sm_logs';
	public $pk = ['sm_log_id'];
	public $tenant = false;
	public $orderby;
	public $limit;
	public $column_prefix = 'sm_log_';
	public $columns = [
		'sm_log_tenant_id' => ['name' => 'Tenant #', 'domain' => 'tenant_id', 'default' => 0], // not tenanted logs have tenant 0
		'sm_log_id' => ['name' => 'Log #', 'domain' => 'uuid'],
		'sm_log_group_id' => ['name' => 'Group #', 'domain' => 'uuid'],
		'sm_log_originated_id' => ['name' => 'Originated #', 'domain' => 'uuid', 'null' => true],
		'sm_log_host' => ['name' => 'Host', 'domain' => 'host', 'default' => 'CLI'],
		'sm_log_year' => ['name' => 'Year', 'domain' => 'year'],
		'sm_log_user_id' => ['name' => 'User #', 'domain' => 'user_id', 'default' => null, 'null' => true],
		'sm_log_user_ip' => ['name' => 'User IP', 'domain' => 'ip', 'default' => null, 'null' => true],
		'sm_log_chanel' => ['name' => 'Chanel', 'domain' => 'name', 'default' => null, 'null' => true],
		'sm_log_type' => ['name' => 'Type', 'domain' => 'name', 'default' => 'General'],
		'sm_log_level' => ['name' => 'Level', 'domain' => 'name', 'default' => 'ALL'],
		'sm_log_status' => ['name' => 'Status', 'domain' => 'code'], // default Information
		'sm_log_message' => ['name' => 'Message', 'domain' => 'message'],
		'sm_log_trace' => ['name' => 'Trace', 'type' => 'json', 'null' => true],
		'sm_log_content_type' => ['name' => 'Content Type', 'domain' => 'code', 'default' => 'text/html'],
		'sm_log_controller_name' => ['name' => 'Controller Name', 'domain' => 'code', 'default' => null, 'null' => true],
		'sm_log_form_name' => ['name' => 'Form Name', 'domain' => 'code', 'default' => null, 'null' => true],
		'sm_log_form_statistics' => ['name' => 'Form Statistics', 'type' => 'json', 'null' => true],
		'sm_log_notifications' => ['name' => 'Motifications', 'type' => 'json', 'null' => true],
		'sm_log_affected_users' => ['name' => 'Affected Users', 'type' => 'json', 'null' => true],
		'sm_log_affected_rows' => ['name' => 'Affected Rows', 'domain' => 'counter', 'default' => 0],
		'sm_log_error_rows' => ['name' => 'Error Rows', 'domain' => 'counter', 'default' => 0],
		'sm_log_operation' => ['name' => 'Operation', 'domain' => 'code'], // like SELECT, UPDATE, DELETE, INSERT, NONE
		'sm_log_duration' => ['name' => 'Duration (Float)', 'domain' => 'duration_float', 'default' => 0],
		'sm_log_request_url' => ['name' => 'Request URL', 'domain' => 'url', 'default' => 'None', 'null' => true],
		'sm_log_sql' => ['name' => 'SQL', 'domain' => 'sql_long_query', 'null' => true, 'default' => null],
		'sm_log_ajax' => ['name' => 'AJAC', 'type' => 'boolean'],
		'sm_log_inactive' => ['name' => 'Inactive', 'type' => 'boolean']
	];
	public $constraints = [
		'sm_logs_pk' => ['type' => 'pk', 'columns' => ['sm_log_id']],
	];
	public $history = false;
	public $audit = false;
	public $optimistic_lock = false;
	public $options_map = [];
	public $options_active = [];
	public $engine = [
		'MySQLi' => 'InnoDB'
	];

	public $cache = false;
	public $cache_tags = [];
	public $cache_memory = false;

	public $who = [
		'inserted' => true,
	];

	public $periods = [
		'type' => YEAR,
		'year_start' => 2024,
		'year_end' => 2030,
		'class' => 'LogsGeneratedYear[year]',
	];

	public $data_asset = [
		'classification' => 'public',
		'protection' => 1,
		'scope' => 'global'
	];

	/**
	 * @see $this->options()
	 */
	public function optionsYears($options) {
		$result = [];
		for ($i = $this->periods['year_start']; $i <= $this->periods['year_end']; $i++) {
			$result[$i] = ['name' => \Format::id($i)];
		}
		return $result;
	}

	/**
	 * @see $this->options()
	 */
	public function optionsColumnSettings($options) {
		$reflector = new \ReflectionClass($this);
		$reflector->getShortName();
		$period_year = $options['where']['sm_log_year'] ?? (int) date('Y');
		$period_short_name = str_replace(['[table]', '[year]'], [$reflector->getShortName(), $period_year], $this->periods['class']);
		$ar_class = explode("\\", get_class($this));
		array_pop($ar_class);
		array_push($ar_class, $period_short_name);
		$new_class_name = '\\' . implode('\\', $ar_class);
		$query_primary_model = new $new_class_name();
		$query = $query_primary_model->queryBuilder(['alias' => 'a'])
			->select()
			->columns([
				'name' => $options['where']['__column']
			])
			->distinct()
			->where('AND', [$options['where']['__column'], 'IS NOT', null])
			->orderby(['name' => SORT_ASC]);
		return $query->query('name')['rows'];
	}
}