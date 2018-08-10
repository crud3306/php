<?php

$socket = stream_socket_client("tcp://127.0.0.1:9501", $errno, $errstr, 30);

function onRead()
{
	global $socket;
	$buffer = stream_socket_recvfrom($socket, 1024);
	if (!$buffer) {
		echo "server closed\n";
		swoole_event_del($socket);
	}

	echo "\nRecv:{$buffer}\n";
	fwrite(STDOUT, "3 Enter msg:");
}

function onWrite()
{
	global $socket;
	echo "on write\n";
}

function onInput()
{
	global $socket;
	$msg = trim(fgets(STDIN));
	if ($msg == 'exit') {
		swoole_event_exit();
		exit();
	}
	swoole_event_write($socket, $msg);
	fwrite(STDOUT, "2 Enter msg:");
}

// 监听server端发送过来的信息
swoole_event_add($socket, 'onRead', 'onWrite');

// 监听命令行输入
swoole_event_add(STDIN, 'onInput');
// 命令行显示提示输入信息
fwrite(STDOUT, "1 Enter msg:");


