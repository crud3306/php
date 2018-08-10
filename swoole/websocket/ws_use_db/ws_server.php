<?php

class Server
{
	private $server;

	private $pdo;

	public function __construct()
	{
		$this->server = new swoole_websocket_server(HOST, PORT);
		$this->server->set([
			'worker_num'	=>	8,
			'dispath_mode'	=>	2,
			'daemonize'		=> 0
		]);

		$this->server->on('workerstart', [$this, 'onWorkerStart']);
		$this->server->on('open', [$this, 'update']);
		$this->server->on('message', [$this, 'onMessage']);
		// $this->server->on('handshake', [$this, 'userHandshake']);

		$this->server->start();
	}

	public function onWorkerStart(swoole_server $server, $worker_id)
	{
		// 在第一个woker进程，加一个定时器
		if ($worker_id == 0) {
			$this->server->tick(500, [$this, 'onTick']);
		}

		$this->pdo = new PDO(DATABASE_DSN, DATABASE_USER, DATABASE_PWD, [
			PDO::MYSQL_ATTR_INIT_COMMAND =>	'SET NAMES UTF8;',
			PDO::ATTR_ERRMODE			 => PDO::ERRMODE_EXCEPTION,
			PDO::ATTR_PERSISTENT		 => true
		]);
	}

	public function onMessage(swoole_websocket_server $server, $frame)
	{
		// 
	}

	public function update()
	{
		global $cfg_table;
		$result = [];
		foreach ($cfg_table as $table=>$fields) {
			$result[$table] = $this->select($table, $fields);
		}
		var_dump($result);

		// 给所有连接的客户端群发消息
		foreach ($this->server->connections as $connection) {
			$this->server->push($connection, json_encode($result));
		}
	}

	public function select($table, $fields)
	{
		$field_list = implode(',', $fields);
		$sql = "select {$field_list} from {$table}";
		try {
			$statement = $this->pdo->prepare($sql);
			$statement->execute();

			$result = $statement->fetchAll(PDO::FETCH_ASSOC);
			if ($result === false) {
				return [];
			}

			return $result;
		} catch (Exception $e) {
			return [];
		}
	}

	// timer定时回调
	public function onTick()
	{
		$sql = "select is_update from tmp_record limit 1";
		try {
			$statement = $this->pdo->prepare($sql);
			$statement->execute();

			$result = $statement->fetch(PDO::FETCH_ASSOC);
			if ($result === false) {
				return [];
			}
			if ($result['is_update'] == 1) {
				$this->update();
			}

			$update = "update tmp_record set is_update = 0";
			$statement = $this->pdo->prepare($$update);
			$statement->execute();


		} catch (Exception $e) {
			$this->pdo = new PDO(DATABASE_DSN, DATABASE_USER, DATABASE_PWD, [
				PDO::MYSQL_ATTR_INIT_COMMAND =>	'SET NAMES UTF8;',
				PDO::ATTR_ERRMODE			 => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_PERSISTENT		 => true
			]);
		}
	}
}

new Server();