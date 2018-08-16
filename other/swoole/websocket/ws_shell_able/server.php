<?php

class Server
{
	private $server;
	private $process;
	private $async_process = [];

	public function __construct()
	{
		$this->server = new swoole_websocket_server(HOST, PORT);
		$this->server->set([
			'worker_num'	=> 2,
			'dispatch_mode'	=> 2,
			'daemonize'		=> 0
		]);

		$this->server->on('workerstart', [$this, 'onWorkerStart']);
		$this->server->on('message', [$this, 'onMessage']);
		$this->server->on('request', [$this, 'onRequest']);

		$this->process = new swoole_process([$this, 'onProcess'], true);
		$this->server->addProcess($this->process);
		$this->server->start();

		swoole_process::wait();
	}

	public function onWorkerStart(swoole_server $server, $worker_id)
	{
		swoole_process::signal(SIGCHLD, function($sig){
			while($ret = swoole_process::wait(false)) {
				echo "PID={$ret['pid']}\n";
			}
		});
	}

	public funciton onRequest(swoole_http_request $request, swoole_http_response $response)
	{
		$pathinfo = $request->server['path_info'];
		if ($path_info == '/shell.html') {
			// end方法 发送Http响应体，并结束请求处理。
			$response->end(file_get_contents('shell.html'));
		}

		foreach ($this->server->connections as $connection) {
			// 通过使用$server->connection_info($fd)获取连接信息，返回的数组中有一项为 websocket_status，根据此状态可以判断是否为WebSocket客户端。
			$connection_info = $this->server->connection_info($connection);
			if (isset($connection_info['websocket_status']) && $connection_info['websocket_status'] == WEBSOCKET_STATUS_FRAME) {
				$this->server->push($connection, json_encode($result));
			}
		}
	}

	// 当服务器收到来自客户端的数据帧时会回调此函数。
	public function onMessage(swoole_websocket_server $server, $frame)
	{
		var_dump($frame->data);
		$data = json_decode($frame->data, true);
		var_dump($data);
		$cmd = $data['cmd'];

		$is_block = isset($data['is_block']) ? $data['is_block'] : 0;
		if ($is_block) {
			if (isset($this->async_process[$frame->fd])) {
				$process = $this->async_process[$frame->fd];
			} else {
				$process = new swoole_process([$this, 'onTmpProgress'], true, 2);
				$process->start();
				$this->async_process[$frame->fd] = $process;

				swoole_event_add($process->pipe, function() use($process, $frame){
					$data = $process->read();
					var_dump($data);
					$this->server->push($frame->fd, $data);
				});
			}

			$process->write($cmd);
			sleep(1);

		} else {
			$this->process->write($cmd);
			$data = $this->process->read();
			$this->server->push($frame->fd, $data);
		}

	}

	public function onProcess(swoole_process $worker)
	{
		while(true) {
			// 这里是同步阻塞读取的，可以使用swoole_event_add将管道加入到事件循环中，变为异步模式
			$cmd = $worker->read();
			if ($cmd == 'exit') {
				$worker->exit();
				break;
			}

			// passthru执行命令后，直接将结果输出，不需要使用echo或return来查看结果，不返回任何值
			passthru($cmd);
		}
	}

	public function onTmpProgress(swoole_process $worker)
	{
		// 这里是同步阻塞读取的，可以使用swoole_event_add将管道加入到事件循环中，变为异步模式
		$cmd = $worker->read();
		$handle = popen($cmd, 'r');

		swoole_event_add($worker->pipe, function()use($worker, $handle){
			$cmd = $worker->read();
			if ($cmd == 'exit') {
				$worker->exit();
			}
			fwrite($handle, $cmd);
		});

		while(!feof($handle)) {
			$buffer = fread($handle, 18192);
			// 主要是创建该进程时的第二个参数为true，所以 echo的信息会直接进入管道中，父进程通过管道可接收。
			echo $buffer;
		}
	}


}

new Server();