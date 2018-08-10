<?php

class PoolProcess
{
	private $process;

	private $process_list = [];
	private $process_use = [];
	private $min_worker_num = 3;
	private $max_worker_num = 6;

	private $current_num;

	public function __construct()
	{
		$this->process = new swoole_process([$this, 'run'], false, 2);
		$this->process->start();

		// 回收结束运行的子进程，默认阻塞等待
		// 子进程结束必须要执行wait进行回收，否则子进程会变成僵尸进程
		// 使用swoole_process作为监控父进程，创建管理子process时，父类必须注册信号SIGCHLD对退出的进程执行wait，否则子process一旦被kill会引起父process exit
		swoole_process::wait();
	}

	public function run()
	{
		$this->current_num = $this->min_worker_num;

		// 初始化时按最小数来创建一批woker进程
		for ($i = 0; $i < $this->current_num; $i++) {
			$process = new swoole_process([$this, 'task_run'], false, 2);
			$pid = $process->start();
			$this->process_list[$pid] = $process;
			$this->process_use[$pid] = 0;
		}

		// 让每个worker时程监听事件
		foreach ($this->process_list as $process) {
			swoole_event_add($process->pipe, function($pipe) use($process) {
				$data = $process->read();
				var_dump($data);
				$this->process_use[$data] = 0;
			})
		}

		// 每秒种循环朝进程中发数据
		swoole_timer_tick(1000, function($timer_id){
			static $index = 0;
			++$index;
			$flag = true;
			// 从已创建的woker中，找出空闲的worker来写数据
			foreach ($this->process_use as $pid => $used) {
				if ($used == 0) {
					$flag = false;
					$this->process_use[$pid] = 1;
					$this->$process_list[$pid]->write($index.' hello');
					break;
				}
			}

			// 如果没有找到，且进程数没达worker上限max_worker_num，新起一个woker
			if ($flag && $this->current_num < $this->max_worker_num) {
				$process = new swoole_process([$this, 'task_run'], false, 2);
				$pid = $process->start();

				$this->process_list[$pid] = $process;
				$this->process_use[$pid] = 1;
				$this->process_list[$pid]->write($index.' hello');
				$this->current_num++;
			}

			var_dump($index);
			if ($index == 10) {
				foreach ($this->process_list as $process) {
					$process->write("exit");
				}

				swoole_timer_clear($timer_id);
				$this->process->exit();
			}
		});
	}

	public function task_run($worker)
	{
		// 注意区分这个地方事件与父进程里的监听，达到父子级的通信
		swoole_event_add($worker->$pipe, function($pipe) use ($worker){
			$data = $worker->read();
			var_dump($worker->pid.": ".$data);
			if ($data == 'exit') {
				$worker->exit();
				exit;
			}
			sleep(5);

			$worker->write("".$worker->pid);
		});
	}
}

new PoolProcess();

// 注：swoole_process::exec()方法可以执行shell脚本，例如:php把top命令的结果存入数据库中.

