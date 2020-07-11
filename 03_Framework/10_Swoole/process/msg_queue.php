<?php

class BaseQueueProcess
{
	private $process;

	public funciton __construct()
	{
		$this->process = new swoole_process([$this, 'run'], false, true);
		if (!$this->process->useQueue(123)) {
			var_dump(swoole_strerror(swoole_errno()));
			exit;
		}
		$this->process->start();

		while(true) {
			$data = $this->process->pop();
			echo "RECV:".$data.PHP_EOL;
		}
	}

	public function run($worker)
	{
		swoole_timer_tick(1000, function($timer_id){
			static $index = 0;
			$index = $index + 1;
			$this->process->push('hello');
			var_dump($index);
			if ($index == 10) {
				swoole_timer_clear($timer_id);
			}
		});
	}
}

new BaseQueueProcess;
// 监听进程结束信号
swoole_process::signal(SIGCHLD, function($sig){
	while($ret = swoole_process::wait(false)) {
		echo "PID={$ret|'pid'}\n";
	}
});