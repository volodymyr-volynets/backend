<?php

namespace Numbers\Backend\Session\Db;
class Base implements \Numbers\Backend\Session\Interface2\Base {

	/**
	 * Initialize session
	 */
	public function init() {
		// setting session handler
		session_set_save_handler(
			[$this, 'open'],
			[$this, 'close'],
			[$this, 'read'],
			[$this, 'write'],
			[$this, 'destroy'],
			[$this, 'gc']
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
		$result = \Numbers\Backend\Session\Db\Model\Sessions::queryBuilderStatic()
			->select()
			->columns(['sm_session_data'])
			->where('AND', ['sm_session_id', '=', $id])
			->where('AND', ['sm_session_expires', '>=', \Format::now('timestamp')])
			->limit(1)
			->query();
		return $result['rows'][0]['sm_session_data'] ?? "";
	}

	/**
	 * Write
	 *
	 * @param string $id
	 * @param array $data
	 * @return boolean
	 */
	public function write($id, $data) {
		// we only count for presentational content types
		if (\Object\Content\Types::existsStatic(['where' => ['no_virtual_controller_code' => Application::get('flag.global.__content_type'), 'no_content_type_presentation' => 1]])) {
			$inc = 1;
		} else {
			$inc = 0;
		}
		$result = \Numbers\Backend\Session\Db\Model\Sessions::queryBuilderStatic()
			->update()
			->set([
				'sm_session_expires' => \Format::now('timestamp', ['add_seconds' => Session::$default_options['gc_maxlifetime']]),
				'sm_session_last_requested' => \Format::now('timestamp'),
				'sm_session_pages_count;=;~~' => 'sm_session_pages_count + ' . $inc,
				'sm_session_user_ip' => $_SESSION['numbers']['ip']['ip'],
				'sm_session_user_id' => $_SESSION['numbers']['user']['id'] ?? 0,
				'sm_session_data' => $data
			])
			->where('AND', ['sm_session_id', '=', $id])
			->query();
		if (empty($result['affected_rows'])) {
			$result = \Numbers\Backend\Session\Db\Model\Sessions::queryBuilderStatic()
				->insert()
				->columns([
					'sm_session_id',
					'sm_session_started',
					'sm_session_expires',
					'sm_session_last_requested',
					'sm_session_pages_count',
					'sm_session_user_ip',
					'sm_session_user_id',
					'sm_session_data'
				])
				->values([[
					'sm_session_id' => $id,
					'sm_session_started' => \Format::now('timestamp'),
					'sm_session_expires' => \Format::now('timestamp', ['add_seconds' => Session::$default_options['gc_maxlifetime']]),
					'sm_session_last_requested' => \Format::now('timestamp'),
					'sm_session_pages_count' => $inc,
					'sm_session_user_ip' => $_SESSION['numbers']['ip']['ip'],
					'sm_session_user_id' => $_SESSION['numbers']['user']['id'] ?? 0,
					'sm_session_data' => $data
				]])
				->query();
		}
		return $result['affected_rows'] ? true : false;
	}

	/**
	 * Destroy
	 *
	 * @param string $id
	 * @return boolean
	 */
	public function destroy($id) {
		$result = \Numbers\Backend\Session\Db\Model\Sessions::queryBuilderStatic()
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
		$result = \Numbers\Backend\Session\Db\Model\Session\History::queryBuilderStatic()
			->insert()
			->columns([
				'sm_sesshist_id',
				'sm_sesshist_started',
				'sm_sesshist_last_requested',
				'sm_sesshist_pages_count',
				'sm_sesshist_user_ip',
				'sm_sesshist_user_id'
			])
			->values(function(& $subquery) use ($expire) {
				$subquery = \Numbers\Backend\Session\Db\Model\Sessions::queryBuilderStatic()
					->select()
					->columns([
						'sm_sesshist_id' => "nextval('sm_session_history_sm_sesshist_id_seq')",
						'sm_sesshist_started' => 'a.sm_session_started',
						'sm_sesshist_last_requested' => 'a.sm_session_last_requested',
						'sm_sesshist_pages_count' => 'a.sm_session_pages_count',
						'sm_sesshist_user_ip' => 'a.sm_session_user_ip',
						'sm_sesshist_user_id' => 'a.sm_session_user_id'
					])
					->where('AND', ['sm_session_expires', '<', $expire]);
			})
			->query();
		if (!$result['success']) {
			$object->db_object->rollback();
			return false;
		}
		// step 2: remove expired sessions
		$result = \Numbers\Backend\Session\Db\Model\Sessions::queryBuilderStatic()
			->delete()
			->where('AND', ['sm_session_expires', '<', $expire])
			->query();
		if (!$result['success']) {
			$object->db_object->rollback();
			return false;
		}
		$object->db_object->commit();
		return true;
	}

	/**
	 * Expiry dialog
	 *
	 * @return string
	 */
	public function expiryDialog() {
		$body = i18n(null, 'Your session is about to expire, would you like to renew your session?');
		$body.= '<br/><br/>';
		$body.= i18n(null, 'This dialog would close in [seconds] seconds.', [
			'replace' => [
				'[seconds]' => '<span id="modal_session_expiry_seconds">60</span>'
			]
		]);
		$buttons = '';
		$buttons.= \HTML::button2(['id' => 'modal_session_expiry_renew_button', 'type' => 'primary', 'value' => i18n(null, 'Renew'), 'onclick' => 'modal_session_expiry_renew_session();']) . ' ';
		$url = Application::get('flag.global.authorization.logout.controller');
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
				modal_session_expiry_waiting_interval = setInterval(function(){ modal_session_expiry_check(); }, 2 * 60 * 1000);
			}
			function modal_session_expiry_check() {
				if (flag_modal_session_expiry_waiting) {
					return;
				}
				// we make a call to the server to see session status
				var request = $.ajax({
					url: '/numbers/backend/session/db/controller/check/_index',
					method: "POST",
					data: {
						token: numbers.token,
						__skip_session: true
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
							numbers.modal.show('modal_session_expiry');
							flag_modal_session_expiry_waiting = true;
							modal_session_expiry_counter_interval = setInterval(function(){
								modal_session_expiry_counter_value--;
								$('#modal_session_expiry_seconds').html(modal_session_expiry_counter_value);
								if (modal_session_expiry_counter_value == 0) {
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
				numbers.modal.hide('modal_session_expiry');
				clearInterval(modal_session_expiry_waiting_interval);
				clearInterval(modal_session_expiry_counter_interval);
				// we make a call to the server to renew session
				var request = $.ajax({
					url: '/numbers/backend/session/db/controller/check/_renew',
					method: "POST",
					data: {
						token: numbers.token
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