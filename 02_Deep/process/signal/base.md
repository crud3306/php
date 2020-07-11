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
3 fork完子进程后，父进程自杀，然后子进程posix_setsid()，主要目的脱离终端控制，自立门户  // 如果父进程不用管控子进程，则推荐此方式

分析原因：
子进程结束时，会主动给父进程发信号SIGCHLD，如果父进程收到信号了，但没处理(即父进程并没有调用 wait() 或 waitpid() 来回收)，子进程就是变成僵尸进程。
  
> int pcntl_wait ( int &$status [, int $options ] )：阻塞当前进程，直到当前进程的一个子进程退出或者收到一个结束当前进程的信号。  
> int pcntl_waitpid ( int $pid , int &$status [, int $options ] )：功能同 pcntl_wait，区别为 waitpid 为等待指定 pid 的子进程。当 pid 为 -1 时 pcntl_waitpid 与 pcntl_wait 一样。  

但调用pcntl_wait()会一直阻塞等待，所以我们往往选择另一种方式来处理这问题。  
父进程中调用pcntl_signal(SIGCHLD, SIG_IGN); //父进程不关心子进程什么时候结束,子进程结束后，内核会回收。   
   
  
  
上面提的 方式3 其实是创建一个子进程做为守护进程，具体如下：  
>  1.创建子进程，父进程退出  
>    父进程先与子进程退出，子进程则会被1号进程收养，这个子进程就会成为init的子进程   
  
>  2.子进程创建会话   
>     这个是重要的一步，在这一步中该子进程会做这些事情：a.让进程摆脱原会话的控制；b.让进程摆脱员进程组的控制；b.让进程摆脱终端的控制。  
  
>  为什么要这样？这个在守护进程介绍里面有  
  
>  php这里使用posix_setsid()来在这个子进程中创建会话,使得这个进程成为会话组组长  
  
>  通过这两步骤就可以创建一个守护进程了。如果多需要多进程，那么只需要在这个守护进程fork子进程就可以。  
  



php中singal
---------------

SIGHUP     终止进程     终端线路挂断
SIGINT     终止进程     中断进程
SIGQUIT    建立CORE文件终止进程，并且生成core文件
SIGILL   建立CORE文件       非法指令
SIGTRAP    建立CORE文件       跟踪自陷
SIGBUS   建立CORE文件       总线错误
SIGSEGV   建立CORE文件        段非法错误
SIGFPE   建立CORE文件       浮点异常
SIGIOT   建立CORE文件        执行I/O自陷
SIGKILL   终止进程     杀死进程
SIGPIPE   终止进程      向一个没有读进程的管道写数据
SIGALARM   终止进程     计时器到时
SIGTERM   终止进程      软件终止信号
SIGSTOP   停止进程     非终端来的停止信号
SIGTSTP   停止进程      终端来的停止信号
SIGCONT   忽略信号     继续执行一个停止的进程
SIGURG   忽略信号      I/O紧急信号
SIGIO     忽略信号     描述符上可以进行I/O
SIGCHLD   忽略信号      当子进程停止或退出时通知父进程
SIGTTOU   停止进程     后台进程写终端
SIGTTIN   停止进程      后台进程读终端
SIGXGPU   终止进程     CPU时限超时
SIGXFSZ   终止进程     文件长度过长
SIGWINCH    忽略信号     窗口大小发生变化
SIGPROF   终止进程     统计分布图用计时器到时
SIGUSR1   终止进程      用户定义信号1
SIGUSR2   终止进程     用户定义信号2
SIGVTALRM 终止进程     虚拟计时器到时
 
1)  SIGHUP 本信号在用户终端连接(正常或非正常)结束时发出, 通常是在终端的控 
制进程结束时, 通知同一session内的各个作业,  这时它们与控制终端 
不再关联. 
2) SIGINT 程序终止(interrupt)信号, 在用户键入INTR字符(通常是Ctrl-C)时发出  
3) SIGQUIT 和SIGINT类似, 但由QUIT字符(通常是Ctrl-)来控制. 进程在因收到 
SIGQUIT退出时会产生core文件,  在这个意义上类似于一个程序错误信 
号. 
4) SIGILL 执行了非法指令. 通常是因为可执行文件本身出现错误, 或者试图执行 
数据段.  堆栈溢出时也有可能产生这个信号. 
5) SIGTRAP 由断点指令或其它trap指令产生. 由debugger使用. 
6) SIGABRT  程序自己发现错误并调用abort时产生. 
6) SIGIOT 在PDP-11上由iot指令产生, 在其它机器上和SIGABRT一样. 
7)  SIGBUS 非法地址, 包括内存地址对齐(alignment)出错. eg: 访问一个四个字长 
的整数, 但其地址不是4的倍数. 
8)  SIGFPE 在发生致命的算术运算错误时发出. 不仅包括浮点运算错误, 还包括溢 
出及除数为0等其它所有的算术的错误. 
9) SIGKILL  用来立即结束程序的运行. 本信号不能被阻塞, 处理和忽略. 
10) SIGUSR1 留给用户使用 
11) SIGSEGV  试图访问未分配给自己的内存, 或试图往没有写权限的内存地址写数据. 
12) SIGUSR2 留给用户使用 
13) SIGPIPE Broken  pipe 
14) SIGALRM 时钟定时信号, 计算的是实际的时间或时钟时间. alarm函数使用该 
信号. 
15) SIGTERM  程序结束(terminate)信号, 与SIGKILL不同的是该信号可以被阻塞和 
处理. 通常用来要求程序自己正常退出.  shell命令kill缺省产生这 
个信号. 
17) SIGCHLD 子进程结束时, 父进程会收到这个信号. 
18) SIGCONT  让一个停止(stopped)的进程继续执行. 本信号不能被阻塞. 可以用 
一个handler来让程序在由stopped状态变为继续执行时完成特定的  
工作. 例如, 重新显示提示符 
19) SIGSTOP 停止(stopped)进程的执行.  注意它和terminate以及interrupt的区别: 
该进程还未结束, 只是暂停执行. 本信号不能被阻塞, 处理或忽略. 
20)  SIGTSTP 停止进程的运行, 但该信号可以被处理和忽略. 用户键入SUSP字符时 
(通常是Ctrl-Z)发出这个信号 
21) SIGTTIN  当后台作业要从用户终端读数据时, 该作业中的所有进程会收到SIGTTIN 
信号. 缺省时这些进程会停止执行. 
22) SIGTTOU  类似于SIGTTIN, 但在写终端(或修改终端模式)时收到. 
23) SIGURG 有"紧急"数据或out-of-band数据到达socket时产生.  
24) SIGXCPU 超过CPU时间资源限制. 这个限制可以由getrlimit/setrlimit来读取/ 
改变 
25)  SIGXFSZ 超过文件大小资源限制. 
26) SIGVTALRM 虚拟时钟信号. 类似于SIGALRM, 但是计算的是该进程占用的CPU时间.  
27) SIGPROF 类似于SIGALRM/SIGVTALRM, 但包括该进程用的CPU时间以及系统调用的 
时间. 
28)  SIGWINCH 窗口大小改变时发出. 
29) SIGIO 文件描述符准备就绪, 可以开始进行输入/输出操作. 
30) SIGPWR Power  failure 
 
有 两个信号可以停止进程:SIGTERM和SIGKILL。  SIGTERM比较友好，进程能捕捉这个信号，根据您的需要来关闭程序。在关闭程序之前，您 可以结束打开的记录文件和完成正在做的任务。在某些情况下，假  如进程正在进行作业而且不能中断，那么进程可以忽略这个SIGTERM信号。
 
对于SIGKILL信号，进程是不能忽略的。这是一个  “我不管您在做什么,立刻停止”的信号。假如您发送SIGKILL信号给进程，Linux就将进程停止在那里。



