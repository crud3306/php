<?php

// 测试僵尸进程
// 僵尺进程的产生：当子进程比父进程先退出，而父进程没对其做任何处理的时候，子进程将会变成僵尸进程。

$ppid = posix_getpid();

// pcntl_signal(SIGCHLD, SIG_IGN);  // （方式1）避免子进程变僵尸进程

$pid = pcntl_fork();

if ($pid == -1){
    die('fork failed');

} else if ($pid == 0){
    $mypid = posix_getpid();
    echo 'I am child process. My PID is ' . $mypid . ' and my father is',$ppid.PHP_EOL;
    exit(); //关闭子进程 ,需要配合pcntl_wait使用 , 否则通过ps aux | grep php - >[php] <defunct> 僵尸进程

} else {
    echo 'Oh my god! I am a father now! My child is'. $pid . ' and mine is ' . $ppid . PHP_EOL;
    //pcntl_wait($status); // （方式2） 回收子进程，避免子进程变僵尸进程

    //使主进程挂起
	sleep(50);
}