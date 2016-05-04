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
			array($this, 'open'),
			array($this, 'close'),
			array($this, 'read'),
			array($this, 'write'),
			array($this, 'destroy'),
			array($this, 'gc')
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
		$data = $this->model_seessions->get(['columns' => ['sm_session_data'], 'limit' => 1, 'pk' => null, 'where' => ['sm_session_id' => $id, 'sm_session_expires,>=' => format::now('timestamp')]]);
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
			'sm_session_pages_count,=,~~' => 'sm_session_pages_count + ' . $inc,
			'sm_session_user_ip' => $_SESSION['numbers']['ip']['ip'],
			'sm_session_user_id' => 0,
			'sm_session_data' => $data
		];
		$db = new db($this->model_seessions->db_link);
		// we update first
		$result = $db->update($this->model_seessions->name, $save, 'sm_session_id');
		if ($result['affected_rows'] == 0) {
			$save['sm_session_started'] = format::now('timestamp');
			$save['sm_session_pages_count'] = $inc;
			unset($save['sm_session_pages_count,=,~~']);
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
		$session_model = new numbers_backend_session_db_model_sessions();
		$login_model = new numbers_backend_session_db_model_logins();
		$expire = format::now('timestamp');

		// generating sqls
		$sql_move = <<<TTT
			INSERT INTO {$login_model->name} (
				sm_login_started,
				sm_login_last_requested,
				sm_login_pages_count,
				sm_login_user_ip,
				sm_login_user_id
			)
			SELECT
				s.sm_session_started sm_login_started,
				s.sm_session_last_requested sm_login_last_requested,
				s.sm_session_pages_count sm_login_pages_count,
				s.sm_session_user_ip sm_login_user_ip,
				s.sm_session_user_id sm_login_user_id
			FROM {$session_model->name} s
			WHERE 1=1
				AND s.sm_session_expires < '{$expire}'
TTT;

		// session cleaning sql
		$sql_delete = <<<TTT
			DELETE FROM {$session_model->name} s
			WHERE 1=1
				AND s.sm_session_expires < '{$expire}'
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
}