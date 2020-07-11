<?php

$parentpid = posix_getpid();
echo "parent progress pid:{$parentpid}\n";
 
// 定义一个信号处理函数
function sighandler($signal) {
  if ($signal == SIGINT) {
    $pid = getmypid();
    exit("{$pid} process, Killed!".PHP_EOL);

  } elseif ($signal == SIGCHLD) {  
    $pid = getmypid();
    echo "{$pid} process, SIGCHLD!".PHP_EOL;

    pcntl_wait($status);

  } else {
  	echo $signal.PHP_EOL;
  }
}
 
//php version < 5.3 .每执行一条低级指令，就检查一次是否出现该信号。效率损耗很大。
//declare(ticks=1);

$child_list = [];
// 注册一个信号处理器。当发出该信号的时候对调用已定义的函数
pcntl_signal(SIGINT, 'sighandler');

// 子进程结束时（自已exit，或者通过信息被exit时）会给父进程发送SIGCHLD信号，防止子进程僵尸 
// pcntl_signal(SIGCHLD, SIG_IGN); 
// pcntl_signal(SIGCHLD, 'sighandler'); 
 
for ($i = 0; $i < 3; $i++) {
  $pid = pcntl_fork();
  if ($pid == 0) {

    // 子进程通过死循环阻塞住，这里一定要注意
    while (true) {

      // 子进程接收到信号时，调用注册的signalHandler()
      pcntl_signal_dispatch();
      echo "I am child: ".getmypid(). " and i am running !".PHP_EOL;
      sleep(rand(1,2));
    }
    
  } elseif($pid > 0) {
    $child_list[] = $pid;

  } else {
    die('fork fail!'.PHP_EOL);
  }

}

var_dump($child_list);
 
sleep(6);
foreach ($child_list as $key => $pid) {
  // 发送信息
  posix_kill($pid, SIGINT);
}

// 防止子进程僵尸
while (count($child_list) > 0) {
  $tmp_id = pcntl_wait($status);
  if ($tmp_id) {
    var_dump($tmp_id);
    array_pop($child_list);
  }
}

var_dump($child_list);

sleep(5);
echo "{$parentpid} parent is end".PHP_EOL;

