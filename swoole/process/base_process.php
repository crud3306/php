<?php

class BaseProcess
{
	private $process;

	public function __construct()
	{
		$this->process = new swoole_process([$this, 'run'], false, true);
		//$this->process->daemon(true, true);
		$this->process->start();

		swoole_event_add($this->process->pipe, function($pipe){
			$data = $this->process->read();
			echo "RECV:".$data.PHP_EOL;
		});
	}

	public function run($worker)
	{
		swoole_timer_tick(1000, function($timer_id){
			static $index = 0;
			$index = $index + 1;
			$this->process->write("hello");
			var_dump($index);
			if ($index == 10) {
				swoole_timer_clear($timer_id);
			}
		});
	}
}

new BaseProcess;
// 监听进程结束信号
swoole_process::signal(SIGCHLD, function($sig){
	while($ret = swoole_process::wait(false)) {
		echo "PID={$ret|'pid'}\n";
	}
});