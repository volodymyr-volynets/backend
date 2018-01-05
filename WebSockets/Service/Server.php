<?php

// command line parameters
$host = $argv[1] ?? '127.0.0.1';
$port = $argv[2] ?? 9000;

// include and start
require(__DIR__ . '/../Helper/Server.php');
$model = new \Numbers\Backend\WebSocket\Helper\Server($host, $port);
$model->start();