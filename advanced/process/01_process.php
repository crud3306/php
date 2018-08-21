<?php

// 当前进程
$ppid = posix_getpid();

// fork出的子进程，并返回子进程id
$pid = pcntl_fork();

// pid=-1，即fork失败
if ($pid == -1) {
	exit('fork子进程失败!');

// pid>0，即进入父进程
} elseif ($pid > 0) {
	cli_set_process_title("php_process 我是父进程,我的进程id是{$ppid}.");
	sleep(30); // 保持30秒，确保能被ps查到

// pid=0，是进入子进程
} else {
	$cpid = posix_getpid();
	cli_set_process_title("php_process 我是{$ppid}的子进程,我的进程id是{$cpid}.");
	sleep(30);
}