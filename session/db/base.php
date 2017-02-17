<?php

class numbers_backend_session_db_base implements numbers_backend_session_interface_base {

	/**
	 * Model sessions
	 *
	 * @var string
	 */
	public $model_sessions;

	/**
	 * Initialize session
	 */
	public function init() {
		// creating models
		$this->model_seessions = new numbers_backend_session_db_model_sessions();
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
	 * Open a session
	 *
	 * @param string $path
	 * @param string $name
	 * @return boolean
	 */
	public function open($path, $name) {
		return true;
	}

	/**
	 * Close session
	 *
	 * @return boolean
	 */
	public function close() {
		return true;
	}

	/**
	 * Read session data
	 *
	 * @param string $id
	 */
	public function read($id) {
		$data = $this->model_seessions->get(['columns' => ['sm_session_data'], 'limit' => 1, 'pk' => null, 'where' => ['sm_session_id' => $id, 'sm_session_expires;>=' => format::now('timestamp')]]);
		if (isset($data[0])) {
			return $data[0]['sm_session_data'];
		} else {
			return "";
		}
	}

	/**
	 * Write session data
	 *
	 * @param string $id
	 * @param array $data
	 * @return boolean
	 */
	public function write($id, $data) {
		// we only count for presentational content types
		if (object_content_types::exists_static(['where' => ['no_virtual_controller_code' => application::get('flag.global.__content_type'), 'no_content_type_presentation' => 1]])) {
			$inc = 1;
		} else {
			$inc = 0;
		}
		$save = [
			'sm_session_id' => $id,
			'sm_session_expires' => format::now('timestamp', ['add_seconds' => session::$default_options['gc_maxlifetime']]),
			'sm_session_last_requested' => format::now('timestamp'),
			'sm_session_pages_count;=;~~' => 'sm_session_pages_count + ' . $inc,
			'sm_session_user_ip' => $_SESSION['numbers']['ip']['ip'],
			'sm_session_user_id' => $_SESSION['numbers']['user']['id'] ?? 0,
			'sm_session_data' => $data
		];
		$db = new db($this->model_seessions->db_link);
		// we update first
		$result = $db->update($this->model_seessions->name, $save, 'sm_session_id');
		if ($result['affected_rows'] == 0) {
			$save['sm_session_started'] = format::now('timestamp');
			$save['sm_session_pages_count'] = $inc;
			unset($save['sm_session_pages_count;=;~~']);
			// we insert
			$result = $db->insert($this->model_seessions->name, [$save]);
		}
		return $result['affected_rows'] ? true : false;
	}

	/**
	 * Destroy the session
	 *
	 * @param string $id
	 * @return boolean
	 */
	public function destroy($id) {
		// we set session expired 100 seconds ago, gc will do the rest
		$save = [
			'sm_session_id' => $id,
			'sm_session_expires' => format::now('timestamp', ['add_seconds' => -100]),
		];
		$db = new db($this->model_seessions->db_link);
		$result = $db->update($this->model_seessions->name, $save, 'sm_session_id');
		return true;
	}

	/**
	 * Garbage collector
	 *
	 * @param int $life
	 * @return boolean
	 */
	public function gc($life) {
		// step 1: we need to move expired sessions to logins table
		$db = new db($this->model_seessions->db_link);
		$expire = format::now('timestamp');
		// generating sqls
		$sql_move = <<<TTT
			INSERT INTO sm_session_history (
				sm_sesshist_id,
				sm_sesshist_started,
				sm_sesshist_last_requested,
				sm_sesshist_pages_count,
				sm_sesshist_user_ip,
				sm_sesshist_user_id
			)
			SELECT
				nextval('sm_session_history_sm_sesshist_id_seq') sm_sesshist_id,
				s.sm_session_started sm_sesshist_started,
				s.sm_session_last_requested sm_sesshist_last_requested,
				s.sm_session_pages_count sm_sesshist_pages_count,
				s.sm_session_user_ip sm_sesshist_user_ip,
				s.sm_session_user_id sm_sesshist_user_id
			FROM sm_sessions s
			WHERE 1=1
				AND s.sm_session_expires < '{$expire}'
TTT;
		// session cleaning sql
		$sql_delete = <<<TTT
			DELETE FROM sm_sessions
			WHERE 1=1
				AND sm_session_expires < '{$expire}'
TTT;
		// making changes to database
		$db->begin();
		$result = $db->query($sql_move);
		if (!$result['success']) {
			$db->rollback();
			return false;
		}
		$result = $db->query($sql_delete);
		if (!$result['success']) {
			$db->rollback();
			return false;
		}
		$db->commit();
		return true;
	}

	/**
	 * Expiry dialog
	 *
	 * @return string
	 */
	public function expiry_dialog() {
		$body = i18n(null, 'Your session is about to expire, would you like to renew your session?');
		$body.= '<br/><br/>';
		$body.= i18n(null, 'This dialog would close in [seconds] seconds.', [
			'replace' => [
				'[seconds]' => '<span id="modal_session_expiry_seconds">60</span>'
			]
		]);
		$buttons = '';
		$buttons.= html::button2(['id' => 'modal_session_expiry_renew_button', 'type' => 'primary', 'value' => i18n(null, 'Renew'), 'onclick' => 'modal_session_expiry_renew_session();']) . ' ';
		$url = application::get('flag.global.authorization.logout.controller');
		$buttons.= html::button2(['id' => 'modal_session_expiry_close_button', 'type' => 'danger', 'value' => i18n(null, 'Close'), 'onclick' => "window.location.href = '{$url}'"]);
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
		layout::onload($js);
		return html::modal($options);
	}
}