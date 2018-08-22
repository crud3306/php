php信号通信  
  
  
  
关于信号需了解的php函数：  
> // declare() //php5.3以前用，效率低，php5.3以后版本可使用pcntl_signal_dispatch  
> pcntl_signal()  
> pcntl_signal_dispatch()  
> posix_kill()  
> pcntl_wait()  
> pcntl_waitpid()  
  
  
   
信号类型  
-------------  
可以使用kill -l 来查看当前系统的信号类型。   
每个信号所代表的的详细含义，请查看我的这篇文章：http://www.jb51.net/article/106040.htm   
  
使用信号的时候可以通过 php --version 来查看当前PHP的版本，来决定使用哪种方式来进行进程间的信号通信。  
> php --version  
> 或    
> php -v  
   
1）如果PHP 5 >= 5.3.0, PHP 7 ，使用pcntl_signal_dispatch 函数；  
2）如果PHP版本<5.3，使用declare(ticks=1)，意思为每执行一条低级指令，就会去检测是否出现该信号。  
详细的介绍可以查看 //www.jb51.net/article/48340.htm；  
  
官网解释如下：Tick（时钟周期）是一个在 declare 代码段中解释器每执行N条可计时的低级语句就会发生的事件。N 的值是在 declare 中的 directive 部分用 ticks=N 来指定的。  
  
那么什么是低级语句呢？如下代码所示：  
```php
for ($i = 0; $i < 3; $i++) {
  echo $i.PHP_EOL;
}
```
那么这个for 循环中就含有三条低级指令。每输出一条$i。就会去检测下是否发生了已注册的事件，可想而知，这样效率是比较低的。所以如果检测到自己的PHP大于等于5.3，就使用pcntl_singal_dispath 来进行信号派送。  
  


使用信号步骤：   
------------
> 1、主进程中定义信号发生所需要处理事件的函数   
> 2、主进程中将信号和信号处理函数绑定，称为信号安装。   
> 3、子进程中信号监听或者分发，出现信号调用已安装的信号。  
  
具体如下：  
  
1 主进程在启动的时候注册一些信号处理函数。  
```php
/**
 * @param $signal 信号
 */
function signalHandal($signal)
{
  switch ($signal) {
    case SIGINT:
      //do something
      break;
    case SIGHUP:
      //do something
      break;
    default :
      //do something
      break;
  }
}
```
  
2 将信号处理器与信号处理函数绑定：  
// 根据不同的信号，安装不同的信号处理器  
> pcntl_signal(SIGINT, 'signalHandal');  
> pcntl_signal(SIGHUP, 'signalHandal');  
> pcntl_signal(SIGUSR1, 'signalHandla');  
  
3 在子进程监听信号，如果出现该信号，就调用预安装的信号处理函数   
// 监听并分配信号。  
pcntl_signal_dispatch($signal);  
  
  

示例：
```php
<?php
$parentpid = posix_getpid();
echo "parent progress pid:{$parentpid}\n";
 
//定义一个信号处理函数
function sighandler($signal) {
  if ($signal == SIGINT) {
    $pid = getmypid();
    exit("{$pid} process, Killed!".PHP_EOL);
  }
}
 
//php version < 5.3 .每执行一条低级指令，就检查一次是否出现该信号。效率损耗很大。
//declare(ticks=1);

$child_list = [];
//注册一个信号处理器。当发出该信号的时候对调用已定义的函数
pcntl_signal(SIGINT, 'sighandler');
 
 
for ($i = 0; $i < 3; $i++) {
  $pid = pcntl_fork();
  if ($pid == 0) {
    //子进程
    while (true) {
      // 调用已安装的信号信号处理器，为了检测是否有新的信号等待dispatching
      pcntl_signal_dispatch();
      echo "I am child: ".getmypid(). " and i am running !".PHP_EOL;
      sleep(rand(1,3));
    }

  } elseif($pid > 0) {
    $child_list[] = $pid;

  } else {
    die('fork fail!'.PHP_EOL);
  }

}
 
sleep(5);
foreach ($child_list as $key => $pid) {
  // 发送信息
  posix_kill($pid, SIGINT);
}
 
sleep(2);
echo "{$parentpid} parent is end".PHP_EOL;
```
  


fork出的子进成变成僵尸进程的问题
------------
简单的说就是当子进程比父进程先退出，而父进程没对其做任何处理的时候，子进程将会变成僵尸进程。

僵尸进程的坏处  
上面说到僵尸进程由于父进程不回收系统保留的信息而一直占用着系统资源，其中有一项叫做进程描述符。系统通过分配它来启动一个进程。  
但是系统所能使用的进程号是有限的，如果存在大量的僵尸进程，系统将因为没有可用的进程号而导致系统不能产生新的进程。  

可以通过命令查看到僵尸进程  
> ps aux | grep php    
> 或者 
> ps ef | grep php    
> ... [php] <defunct>     
注：<defunct> 即表名是僵尸进程，状态 Z+ 也表示进程
  
解决此问题方法有以下几种：  
1 父进程调用 pcntl_wait()，但此方法会造成父进程阻塞。  
2 父进程中调用pcntl_signal(SIGCHLD, SIG_IGN);  // 推荐此方式
3 fork完子进程后，父进程自杀，让子进程自动提级  // 如果父进程不用管控子进程，则推荐此方式

分析原因：
子进程结束时，会主动给父进程发信号SIGCHLD，如果父进程收到信号了，但没处理(即父进程并没有调用 wait() 或 waitpid() 来回收)，子进程就是变成僵尸进程。
  
> int pcntl_wait ( int &$status [, int $options ] )：阻塞当前进程，直到当前进程的一个子进程退出或者收到一个结束当前进程的信号。  
> int pcntl_waitpid ( int $pid , int &$status [, int $options ] )：功能同 pcntl_wait，区别为 waitpid 为等待指定 pid 的子进程。当 pid 为 -1 时 pcntl_waitpid 与 pcntl_wait 一样。  

但调用pcntl_wait()会一直阻塞等待，所以我们往往选择另一种方式来处理这问题。  
父进程中调用pcntl_signal(SIGCHLD, SIG_IGN); //父进程不关心子进程什么时候结束,子进程结束后，内核会回收。   
  
 


