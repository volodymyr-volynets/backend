<?php

namespace Numbers\Users\Users\Data;
class Tasks extends \Object\Import {
	public $data = [
		'tasks' => [
			'options' => [
				'pk' => ['ts_task_code'],
				'model' => '\Numbers\Users\TaskScheduler\Model\Collection\Tasks',
				'method' => 'save'
			],
			'data' => [
				[
					'ts_task_code' => 'UM_NOTIFICATION_POSTPONED',
					'ts_task_name' => 'U/M Postponed Notifications',
					'ts_task_model' => '\Numbers\Users\Users\Task\PostponedNotifications',
					'ts_task_inactive' => 0,
					'\Numbers\Users\TaskScheduler\Model\TaskParameters' => [
						[
							'ts_tskparam_name' => 'start_date',
							'ts_tskparam_description' => 'Start Date',
							'ts_tskparam_default' => null,
							'ts_tskparam_mandatory' => 0
						],
						[
							'ts_tskparam_name' => 'end_date',
							'ts_tskparam_description' => 'End Date',
							'ts_tskparam_default' => null,
							'ts_tskparam_mandatory' => 0
						],
					]
				],
			],
		],
	];
}