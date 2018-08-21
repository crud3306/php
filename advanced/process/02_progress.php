<?php

// 当前进程
$ppid = posix_getpid();
// for出的子进程，并返回子进程id
$pid = pcntl_fork();

// 输出信息
function logMessage($msg){
    echo date("Y/m/d H:i:s") . "\t" . $msg.PHP_EOL;
}

// 如果pcntl_fork成功，此处会输出两遍
logMessage('forked pid not in if:' . $pid);

// error
if ($pid == -1) {
    logMessage('Could not fork');
    return false;
}
// parent
elseif ($pid) {
	cli_set_process_title("php_process 我是父进程,我的进程id是.".$ppid);

    sleep(4);
    logMessage('Killing parent ID:' . $ppid);
}
// children
else {
	$pid_new = posix_getpid();
	cli_set_process_title("php_process 我是子进程,我的进程id是.".$pid_new);

    sleep(8);
    logMessage('children pid:' . $pid.' '.$pid_new);
}

// 如果pcntl_fork成功，此处会输出两遍
logMessage('end pid '.$pid);


