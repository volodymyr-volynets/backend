<?php

namespace Numbers\Backend\Session\Db;
class Base implements \Numbers\Backend\Session\Interface2\Base {

	/**
	 * Initialize session
	 */
	public function init() {
		// setting session handler
		session_set_save_handler(
			[& $this, 'open'],
			[& $this, 'close'],
			[& $this, 'read'],
			[& $this, 'write'],
			[& $this, 'destroy'],
			[& $this, 'gc']
		);
	}

	/**
	 * Open
	 *
	 * @param string $path
	 * @param string $name
	 * @return boolean
	 */
	public function open($path, $name) {
		return true;
	}

	/**
	 * Close
	 *
	 * @return boolean
	 */
	public function close() {
		return true;
	}

	/**
	 * Read
	 *
	 * @param string $id
	 */
	public function read($id) {
		$result = \Numbers\Backend\Session\Db\Model\Sessions::queryBuilderStatic(['skip_tenant' => true, 'skip_acl' => true])
			->select()
			->columns(['sm_session_data'])
			->where('AND', ['sm_session_id', '=', $id])
			->where('AND', ['sm_session_expires', '>=', \Format::now('timestamp')])
			->limit(1)
			->query();
		return $result['rows'][0]['sm_session_data'] ?? '';
	}

	/**
	 * Write
	 *
	 * @param string $id
	 * @param array $data
	 * @return boolean
	 */
	public function write($id, $data) {
		$timestamp = \Format::now('timestamp');
		// we only count for presentational content types
		$__ajax = \Request::input('__ajax');
		if (!$__ajax && \Object\Content\Types::existsStatic(['where' => ['no_virtual_controller_code' => \Application::get('flag.global.__content_type'), 'no_content_type_presentation' => 1]])) {
			$inc = 1;
		} else {
			$inc = 0;
		}
		$result = \Numbers\Backend\Session\Db\Model\Sessions::queryBuilderStatic(['skip_tenant' => true, 'skip_acl' => true])
			->update()
			->set([
				'sm_session_expires' => \Format::now('timestamp', ['add_seconds' => \Session::$default_options['gc_maxlifetime']]),
				'sm_session_last_requested' => $timestamp,
				'sm_session_pages_count;=;~~' => 'sm_session_pages_count + ' . $inc,
				'sm_session_user_ip' => $_SESSION['numbers']['ip']['ip'] ?? \Request::ip(),
				'sm_session_user_id' => \User::id() ?? 0,
				'sm_session_tenant_id' => \Tenant::id(),
				'sm_session_data' => $data,
				'sm_session_country_code' => $_SESSION['numbers']['ip']['country_code'] ?? null,
				'sm_session_request_count;=;~~' => 'sm_session_request_count + 1',
				'sm_session_bearer_token' => \Application::get('flag.global.__bearer_token') ?? null,
			])
			->where('AND', ['sm_session_id', '=', $id])
			->query();
		if (empty($result['affected_rows'])) {
			$result = \Numbers\Backend\Session\Db\Model\Sessions::queryBuilderStatic(['skip_tenant' => true, 'skip_acl' => true])
				->insert()
				->columns([
					'sm_session_id',
					'sm_session_started',
					'sm_session_expires',
					'sm_session_last_requested',
					'sm_session_pages_count',
					'sm_session_user_ip',
					'sm_session_user_id',
					'sm_session_tenant_id',
					'sm_session_data',
					'sm_session_country_code',
					'sm_session_request_count',
					'sm_session_bearer_token'
				])
				->values([[
					'sm_session_id' => $id,
					'sm_session_started' => \Format::now('timestamp'),
					'sm_session_expires' => \Format::now('timestamp', ['add_seconds' => \Session::$default_options['gc_maxlifetime']]),
					'sm_session_last_requested' => $timestamp,
					'sm_session_pages_count' => $inc,
					'sm_session_user_ip' => $_SESSION['numbers']['ip']['ip']  ?? \Request::ip(),
					'sm_session_user_id' => \User::id() ?? 0,
					'sm_session_tenant_id' => \Tenant::id(),
					'sm_session_data' => $data,
					'sm_session_country_code' => $_SESSION['numbers']['ip']['country_code'] ?? null,
					'sm_session_request_count' => 1,
					'sm_session_bearer_token' => \Application::get('flag.global.__bearer_token') ?? null,
				]])
				->query();
		}
		// insert into another table.
		\Numbers\Backend\Session\Db\Model\Session\IPs::queryBuilderStatic(['skip_tenant' => true, 'skip_acl' => true])
			->insert()
			->columns([
				'sm_sessips_tenant_id',
				'sm_sessips_session_id',
				'sm_sessips_last_requested',
				'sm_sessips_user_id',
				'sm_sessips_user_ip',
				'sm_sessips_pages_count',
				'sm_sessips_request_count',
				'sm_sessips_bearer_token',
			])
			->values([[
				'sm_sessips_tenant_id' => \Tenant::id(),
				'sm_sessips_session_id' => $id,
				'sm_sessips_last_requested' => $timestamp,
				'sm_sessips_user_id' => \User::id() ?? 0,
				'sm_sessips_user_ip' => $_SESSION['numbers']['ip']['ip']  ?? \Request::ip(),
				'sm_sessips_pages_count' => $inc,
				'sm_sessips_request_count' => 1,
				'sm_sessips_bearer_token' => \Application::get('flag.global.__bearer_token') ?? null,
			]])
			->query();
		return $result['affected_rows'] ? true : false;
	}

	/**
	 * Destroy
	 *
	 * @param string $id
	 * @return boolean
	 */
	public function destroy($id) {
		$result = \Numbers\Backend\Session\Db\Model\Sessions::queryBuilderStatic(['skip_tenant' => true, 'skip_acl' => true])
			->update()
			->set(['sm_session_expires' => \Format::now('timestamp', ['add_seconds' => -100])])
			->where('AND', ['sm_session_id', '=', $id])
			->query();
		return true;
	}

	/**
	 * Garbage collector
	 *
	 * @param int $life
	 * @return boolean
	 */
	public function gc($life) {
		$object = new \Numbers\Backend\Session\Db\Model\Sessions();
		$object->db_object->begin();
		// step 1: we need to move expired sessions to history table
		$expire = \Format::now('timestamp');
		$result = \Numbers\Backend\Session\Db\Model\Session\History::queryBuilderStatic(['skip_tenant' => true, 'skip_acl' => true])
			->insert()
			->columns([
				'sm_sesshist_started',
				'sm_sesshist_last_requested',
				'sm_sesshist_pages_count',
				'sm_sesshist_user_ip',
				'sm_sesshist_user_id',
				'sm_sesshist_tenant_id',
				'sm_sesshist_country_code',
				'sm_sesshist_request_count',
				'sm_sesshist_session_id',
				'sm_sesshist_bearer_token',
			])
			->values(function(& $subquery) use ($expire) {
				$subquery = \Numbers\Backend\Session\Db\Model\Sessions::queryBuilderStatic(['skip_tenant' => true, 'skip_acl' => true])
					->select()
					->columns([
						'sm_sesshist_started' => 'a.sm_session_started',
						'sm_sesshist_last_requested' => 'a.sm_session_last_requested',
						'sm_sesshist_pages_count' => 'a.sm_session_pages_count',
						'sm_sesshist_user_ip' => 'a.sm_session_user_ip',
						'sm_sesshist_user_id' => 'a.sm_session_user_id',
						'sm_sesshist_tenant_id' => 'a.sm_session_tenant_id',
						'sm_sesshist_country_code' => 'a.sm_session_country_code',
						'sm_sesshist_request_count' => 'a.sm_session_request_count',
						'sm_sesshist_session_id' => 'a.sm_session_id',
						'sm_sesshist_bearer_token' => 'a.sm_session_bearer_token',
					])
					->where('AND', ['sm_session_expires', '<', $expire]);
			})
			->query();
		if (!$result['success']) {
			$object->db_object->rollback();
			return false;
		}
		// step 2: remove expired sessions
		$result = \Numbers\Backend\Session\Db\Model\Sessions::queryBuilderStatic(['skip_tenant' => true, 'skip_acl' => true])
			->delete()
			->where('AND', ['sm_session_expires', '<', $expire])
			->query();
		if (!$result['success']) {
			$object->db_object->rollback();
			return false;
		}
		// step 3: remove IPs
		$datetime = \Format::now('datetime');
		$result = \Numbers\Backend\Session\Db\Model\Session\IPs::queryBuilderStatic(['skip_acl' => true])
			->delete()
			->where('AND', ["'$datetime'::timestamp - sm_sessips_last_requested", '>', "'2 min'::interval", true])
			->query();
		if (!$result['success']) {
			$object->db_object->rollback();
			return false;
		}
		$object->db_object->commit();
		return true;
	}

	/**
	 * Check for over usage.
	 *
	 * @param string $ip
	 * @param array $rules
	 */
	public function checkOverUsage(string $ip, array $rules) {
		$timestamp = \Format::now('timestamp');
		$result = \Numbers\Backend\Session\Db\Model\Session\IPs::queryBuilderStatic(['alias' => 'a', 'skip_acl' => true])
			->select()
			->columns([
				'sm_sessips_user_ip' => 'sm_sessips_user_ip',
				'sm_sessips_pages_count' => 'SUM(sm_sessips_pages_count)',
				'sm_sessips_request_count' => 'SUM(sm_sessips_request_count)'
			])
			->where('AND', ['sm_sessips_user_ip', '=', $ip])
			->where('AND', ["'$timestamp'::timestamp - sm_sessips_last_requested", '<=', "'1 min'::interval", true])
			->groupby(['sm_sessips_user_ip'])
			->query();
		// Final verification.
		$error = [];
		if (isset($result['rows'][0])) {
			// we calculate # of requests per 1 minute for specificc IP address.
			if (($result['rows'][0]['sm_sessips_pages_count'] / 60) > ((int) ($rules['pages'] ?? 5))) {
				$error[] = 'Too Many Requests';
			}
			if (($result['rows'][0]['sm_sessips_request_count'] / 60) > ((int) ($rules['pages'] ?? 50))) {
				$error[] = 'Too Many Requests';
			}
		}
		// if we have an error we log in firewall.
		if (!empty($error)) {
			$error = array_unique($error);
			// add data to firewall
			$firewalls = \Object\ACL\Resources::getStatic('firewalls', 'primary');
			if (!empty($firewalls)) {
				if (!\Format::$initialized) {
					\Format::init();
				}
				call_user_func_array($firewalls['method'], [$ip, $error]);
			}
			header('HTTP/1.1 429');
			echo implode("\n", $error);
			exit;
		}
	}

	/**
	 * Expiry dialog
	 *
	 * @return string
	 */
	public function expiryDialog() {
		// quick logout url
		$url = \Object\ACL\Resources::getStatic('authorization', 'logout', 'url');
		if (empty($url)) return;
		// assemble body
		$body = i18n(null, 'Your session is about to expire, would you like to renew your session?');
		$body.= '<br/><br/>';
		$body.= i18n(null, 'This dialog would close in [seconds] seconds.', [
			'replace' => [
				'[seconds]' => '<span id="modal_session_expiry_seconds">60</span>'
			]
		]);
		$buttons = '';
		$buttons.= \HTML::button2(['id' => 'modal_session_expiry_renew_button', 'type' => 'primary', 'value' => i18n(null, 'Renew'), 'onclick' => 'modal_session_expiry_renew_session();']) . ' ';
		$buttons.= \HTML::button2(['id' => 'modal_session_expiry_close_button', 'type' => 'danger', 'value' => i18n(null, 'Close'), 'onclick' => "window.location.href = '{$url}'"]);
		$options = [
			'id' => 'modal_session_expiry',
			'title' => i18n(null, 'Session'),
			'body' => $body,
			'footer' => $buttons,
			'no_header_close' => true,
			'close_by_click_disabled' => true
		];
		$js = <<<TTT
			// Session handling logic
			var flag_modal_session_expiry_waiting = false;
			var modal_session_expiry_waiting_interval;
			var modal_session_expiry_counter_interval, modal_session_expiry_counter_value;
			function modal_session_expiry_init() {
				modal_session_expiry_counter_value = 60;
				$('#modal_session_expiry_seconds').html(modal_session_expiry_counter_value);
				// check every two minutes
				modal_session_expiry_waiting_interval = setInterval(function(){ modal_session_expiry_check(); }, 120 * 1000);
			}
			function modal_session_expiry_check() {
				if (flag_modal_session_expiry_waiting) {
					return;
				}
				// we make a call to the server to see session status
				var request = $.ajax({
					url: '/Numbers/Backend/Session/Db/Controller/Check/_Index',
					method: "POST",
					data: {
						token: Numbers.token,
						__skip_session: true,
						__ajax: true
					},
					dataType: "json"
				});
				request.done(function(data) {
					var flag_expired = false;
					if (data.success) {
						// if not logged in we redirect
						if (!data.loggedin || data.expired) {
							flag_expired = true;
						}
						// we check if session expires in 5 minutes, if yes we show dialog
						if (data.expires_in <= 300) {
							Numbers.Modal.show('modal_session_expiry');
							flag_modal_session_expiry_waiting = true;
							modal_session_expiry_counter_interval = setInterval(function(){
								modal_session_expiry_counter_value--;
								$('#modal_session_expiry_seconds').html(modal_session_expiry_counter_value);
								// need to check 5 secs before if session has been renewed
								if (modal_session_expiry_counter_value == 5) {
									$.ajax({
										url: '/Numbers/Backend/Session/Db/Controller/Check/_Index',
										method: "POST",
										data: {
											token: Numbers.token,
											__skip_session: true
										},
										dataType: "json"
									}).done(function(data) {
										if (data.success && data.expires_in > 300) {
											Numbers.Modal.hide('modal_session_expiry');
											clearInterval(modal_session_expiry_waiting_interval);
											clearInterval(modal_session_expiry_counter_interval);
										}
									});
								} else if (modal_session_expiry_counter_value == 0) {
									window.location.href = '{$url}';
								}
							}, 1000);
				
						}
					} else {
						flag_expired = true;
					}
					// if expired we redirect to logout
					if (flag_expired) {
						window.location.href = '{$url}';
					}
				});
				request.fail(function(jqXHR, textStatus) {
					window.location.href = '{$url}';
				});
			}
			window.modal_session_expiry_renew_session = function() {
				Numbers.Modal.hide('modal_session_expiry');
				clearInterval(modal_session_expiry_waiting_interval);
				clearInterval(modal_session_expiry_counter_interval);
				// we make a call to the server to renew session
				var request = $.ajax({
					url: '/Numbers/Backend/Session/Db/Controller/Check/_Renew',
					method: "POST",
					data: {
						token: Numbers.token,
						__ajax: true
					},
					dataType: "json"
				});
				request.done(function(data) {
					if (data.success) {
						flag_modal_session_expiry_waiting = false;
						modal_session_expiry_init();
					} else {
						window.location.href = '{$url}';
					}
				});
				request.fail(function(jqXHR, textStatus) {
					window.location.href = '{$url}';
				});
			}
			// initialize the engine
			modal_session_expiry_init();
TTT;
		\Layout::onload($js);
		return \HTML::modal($options);
	}
}