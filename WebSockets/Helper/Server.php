<?php

namespace Numbers\Backend\WebSocket\Helper;
class Server {

	/**
	 * Socket
	 *
	 * @var resource
	 */
	private $socket;

	/**
	 * Options
	 *
	 * @var array
	 */
	public $options = [];

	/**
	 * Constructor
	 *
	 * @param string $host
	 * @param int $port
	 */
	public function __construct($host, $port) {
		$this->options['host'] = $host;
		$this->options['port'] = $port;
	}

	/**
	 * Start
	 */
	public function start() {
		register_tick_function(array(& $this, 'tick'));
		declare(ticks = 200000);
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		socket_set_option($this->socket, SOL_SOCKET, SO_REUSEADDR, 1);
		socket_bind($this->socket, 0, $this->options['port']);
		socket_listen($this->socket);
		$clients = [$this->socket];
		$client_data = [];
		$null = null;
		// endless loop
		while (true) {
			$changed = $clients;
			socket_select($changed, $null, $null, 0, 10);
			//check for new socket
			if (in_array($this->socket, $changed)) {
				$new = socket_accept($this->socket);
				$clients[] = $new;
				$header = socket_read($new, 1024);
				$this->handshake($header, $new, $this->options['host'], $this->options['port']);
				socket_getpeername($new, $ip);
				$response = $this->mask(json_encode([
					'success' => true,
					'error' => [],
					'type' => 'connected',
					'message' => $ip . ' connected!'
				]));
				$this->sendMessage($response, $clients);
				unset($changed[array_search($this->socket, $changed)]);
			}
			//loop through all connected sockets
			$api_sent = false;
			foreach ($changed as $k => $v) {
				while(socket_recv($v, $buffer, 1024, 0) >= 1) {
					$received_text = $this->unmask($buffer);
					$message = json_decode($received_text, true);
					if (!empty($message['api']) && empty($api_sent) && ($message['type'] ?? '') == 'message') {
						$api_sent = true;
						$api_result = $this->post($message['host'] . $message['api'], [
							'params' => [
								'token' => $message['token'],
								'group_id' => $message['group_id'],
								'user_id' => $message['user_id'],
								'message' => $message['message']
							],
							'json' => true
						]);
					}
					$response = $this->mask(json_encode([
						'success' => true,
						'error' => [],
						'type' => $message['type'],
						'message' => $message['message'],
						// special fields
						'group_id' => $message['group_id'],
						'user_id' => $message['user_id']
					]));
					$this->sendMessage($response, $clients);
					break 2;
				}
				$buffer = @socket_read($v, 1024, PHP_NORMAL_READ);
				if ($buffer === false) {
					socket_getpeername($v, $ip);
					unset($clients[array_search($v, $clients)]);
					$response = $this->mask(json_encode([
						'success' => true,
						'error' => [],
						'type' => 'disconnected',
						'message' => $ip . ' disconnected!'
					]));
					$this->sendMessage($response, $clients);
				}
			}
		}
		// close the listening socket
		socket_close($this->socket);
	}

	public function tick() {
		// we need to shutdown
		if (file_exists(__DIR__ . '/websocket.lock')) {
			$lock = file_get_contents(__DIR__ . '/websocket.lock');
			if (!empty($lock)) {
				socket_close($this->socket);
				exit;
			}
		}
	}

	/**
	 * Send message
	 *
	 * @param masked $messsage
	 * @param array $clients
	 * @return boolean
	 */
	private function sendMessage($messsage, $clients) {
		foreach($clients as $k => $v) {
			@socket_write($v, $messsage, strlen($messsage));
		}
		return true;
	}

	/**
	 * Mask
	 *
	 * @param string $messsage
	 * @return tring
	 */
	private function mask($messsage) {
		$b1 = 0x80 | (0x1 & 0x0f);
		$length = strlen($messsage);
		if ($length <= 125) {
			$header = pack('CC', $b1, $length);
		} elseif ($length > 125 && $length < 65536) {
			$header = pack('CCn', $b1, 126, $length);
		} elseif ($length >= 65536) {
			$header = pack('CCNN', $b1, 127, $length);
		}
		return $header . $messsage;
	}

	/**
	 * Unmask
	 *
	 * @param string $messsage
	 * @return string
	 */
	private function unmask($messsage) {
		$length = ord($messsage[1]) & 127;
		if ($length == 126) {
			$masks = substr($messsage, 4, 4);
			$data = substr($messsage, 8);
		} elseif ($length == 127) {
			$masks = substr($messsage, 10, 4);
			$data = substr($messsage, 14);
		} else {
			$masks = substr($messsage, 2, 4);
			$data = substr($messsage, 6);
		}
		$messsage = '';
		for ($i = 0; $i < strlen($data); ++$i) {
			$messsage.= $data[$i] ^ $masks[$i%4];
		}
		return $messsage;
	}

	/**
	 * Handshake
	 *
	 * @param string $header
	 * @param resource $socket
	 * @param string $host
	 * @param int $port
	 */
	private function handshake($header, $socket, $host, $port) {
		$headers = [];
		$lines = preg_split("/\r\n/", $header);
		foreach ($lines as $v) {
			$v = chop($v);
			if(preg_match('/\A(\S+): (.*)\z/', $v, $matches)) {
				$headers[$matches[1]] = $matches[2];
			}
		}
		$secret_key = $headers['Sec-WebSocket-Key'] ?? '';
		$accept = base64_encode(pack('H*', sha1($secret_key . '258EAFA5-E914-47DA-95CA-C5AB0DC85B11')));
		//hand shaking header
		$upgrade  = "HTTP/1.1 101 Web Socket Protocol Handshake\r\n" .
					"Upgrade: websocket\r\n" .
					"Connection: Upgrade\r\n" .
					"WebSocket-Origin: $host\r\n" .
					"WebSocket-Location: ws://$host:$port/\r\n".
					"Sec-WebSocket-Accept: $accept\r\n\r\n";
		socket_write($socket, $upgrade, strlen($upgrade));
	}

	/**
	 * Post
	 *
	 * @param string $url
	 * @param array $options
	 * @return array
	 */
	public function post(string $url, array $options = []) : array {
		$result = [
			'success' => false,
			'error' => [],
			'data' => null
		];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($options['params']));
		$result['data'] = curl_exec($ch);
		if (!empty($options['json'])) {
			$result['data'] = json_decode($result['data'], true);
		}
		curl_close($ch);
		return $result;
	}
}