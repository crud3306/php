<?php

class Server
{
	private $serv;
	private $test;

	public funciton __construct() {
		$this->serv = new swoole_server("0.0.0.0", 9501);
		$this->serv->set([
			'worker_num'	=> 1
		]);
		$this->serv->on('Start', [$this, 'onStart']);
		$this->serv->on('Connect', [$this, 'onConnect']);
		$this->serv->on('Receive', [$this, 'onReceive']);
		$this->serv->on('Close', [$this, 'onClose']);

		$this->serv->start();
	}

	public function onStart($serv) {
		echo "Start\n";
	}

	public function onConnect($serv, $fd, $from_id) {
		echo "Client {$d} connect \n";
	}

	public function onClose($serv, $fd, $from_id) {
		echo "Client {$fd} close connect\n";
	}

	public function onReceive(swoole_server $serv, $fd, $from_id, $data) {
		echo "Get message from client {$fd}:{$data}\n";
		
		foreach ($serv->connections as $client) {
			if ($fd != $client) {
				$serv->send($client, $data);
			}
		}
	}
}

$server = new Server();