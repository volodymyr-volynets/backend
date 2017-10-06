<?php

namespace Numbers\Backend\System\Modules\Data;
class Notifications extends \Object\Import {
	public $data = [
		'features' => [
			'options' => [
				'pk' => ['sm_feature_code'],
				'model' => '\Numbers\Backend\System\Modules\Model\Collection\Module\Features',
				'method' => 'save'
			],
			'data' => [
				[
					'sm_feature_module_code' => 'SM',
					'sm_feature_code' => 'SM::EMAIL_COMMON_RECORD_CHANGE',
					'sm_feature_type' => 21,
					'sm_feature_name' => 'S/M Email Common Record Changed',
					'sm_feature_icon' => 'envelope-o',
					'sm_feature_activated_by_default' => 1,
					'sm_feature_activation_model' => null,
					'sm_feature_inactive' => 0
				]
			]
		],
		'notifications' => [
			'options' => [
				'pk' => ['sm_notification_code'],
				'model' => '\Numbers\Backend\System\Modules\Model\Notifications',
				'method' => 'save'
			],
			'data' => [
				[
					'sm_notification_code' => 'SM::EMAIL_COMMON_RECORD_CHANGE',
					'sm_notification_name' => 'S/M Email Common Record Changed',
					'sm_notification_subject' => '[Page_Name]: Record has been [Page_Operation]',
					'sm_notification_body' => 'Hello [Name],

We wanted to let you know that record has been [Page_Operation] on page [Page_Name].

Record identifier:

[Page_Primary_Key]

Following changes has been made:

[Page_Changes]

Please do not reply to this email.

Thank you!',
					'sm_notification_inactive' => 0
				]
			]
		],
	];
}