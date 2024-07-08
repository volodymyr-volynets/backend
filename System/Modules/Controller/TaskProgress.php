<?php

namespace Numbers\Backend\System\Modules\Controller;
class TaskProgress extends \Object\Controller { // \Object\Controller\Authorized

	public $title = 'Task Progress';

	public function actionIndex() {
		$id = (int) \Request::input('__form_progress');
		$result = ['success' => false, 'error' => [], 'message' => \Object\Content\Messages::LOADING, 'data ' => []];
		if ($id) {
			$query = \Numbers\Backend\System\Modules\Model\Task\Progresses::queryBuilderStatic(['skip_acl' => true])->select();
			$query->columns([
				'percent' => 'sm_tskprogress_percent',
				'total' => 'sm_tskprogress_task_total',
				'completed' => 'sm_tskprogress_task_completed'
			]);
			$query->where('AND', ['sm_tskprogress_tenant_id', '=', \Tenant::id()]);
			$query->where('AND', ['sm_tskprogress_id', '=', $id]);
			$result['data'] = $query->query()['rows'][0] ?? [];
			if (!empty($result['data'])) {
				$result['success'] = true;
				$result['message'] = str_replace(
				[
					'[percent]',
					'[completed]',
					'[total]'
				],
				[
					$result['data']['percent'] . '%',
					$result['data']['completed'],
					$result['data']['total']
				], \Object\Content\Messages::LOADING_COMPLETED);
			}
		}
		\Layout::renderAs($result, 'application/json');
	}
}