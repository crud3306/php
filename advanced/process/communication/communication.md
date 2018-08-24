进程间通程间通信  

php关于进程间资源抢占的问题非常的复杂，编码难度高，这也是为什么很少使用PHP跑多进程的原因。当需要用到多进程时我们更愿意去使用Python、go或者Java，它们对多线程封装的更好。需要重点说的是PHP并不是不能写多进程的程序，也不是像其他人说的不稳定，而是编码费时，维护成本高。   
  
参考地址：  
https://blog.csdn.net/ylhalo/article/details/72864247 （重要）  
https://www.cnblogs.com/lwrjiayou/p/6729196.html  

   
   
进程间通信常见方式：  
-----------
常见的进程通信方式有：消息队列、共享内存与信号量、管道、socket，我将一一举例说明。  
  
1.消息队列  
-----------
消息队列是存放在内存中的一个队列。如下代码将创建3个生产者子进程，2个消费者子进程。这5个进程将通过消息队列通信。 
```php
$parentPid = posix_getpid();
echo "parent progress pid:{$parentPid}\n";

$childList = array();

// 创建消息队列,以及定义消息类型(类似于数据库中的库)，
// 注：msg_get_queue是php自带方法

$id = ftok(__FILE__,'m');
$msgQueue = msg_get_queue($id);

const MSG_TYPE = 1;

// 生产者
function producer(){
    global $msgQueue;
    $pid = posix_getpid();
    $repeatNum = 5;
    for ( $i = 1; $i <= $repeatNum; $i++) {
        $str = "({$pid})progress create! {$i}";
        msg_send($msgQueue, MSG_TYPE, $str);
        $rand = rand(1,3);
        sleep($rand);
    }
}
// 消费者
function consumer(){
    global $msgQueue;
    $pid = posix_getpid();
    $repeatNum = 6;
    for ( $i = 1; $i <= $repeatNum; $i++) {
        $rel = msg_receive($msgQueue, MSG_TYPE, $msgType, 1024, $message);
        echo "{$message} | consumer({$pid}) destroy \n";
        $rand = rand(1,3);
        sleep($rand);
    }
}
function createProgress($callback){
    $pid = pcntl_fork();
    if ( $pid == -1) {
        // 创建失败
        exit("fork progress error!\n");
    } else if ($pid == 0) {
        // 子进程执行程序
        $pid = posix_getpid();
        $callback();

        // 注意每个子进程处理完后会有自动退出
        exit("({$pid})child progress end!\n");

    } else {
        // 父进程执行程序
        return $pid;
    }
}
// 3个生产者进程
for ($i = 0; $i < 3; $i ++ ) {
    $pid = createProgress('producer');
    $childList[$pid] = 1;
    echo "create producer child progress: {$pid} \n";
}
// 2个消费者进程
for ($i = 0; $i < 2; $i ++ ) {
    $pid = createProgress('consumer');
    $childList[$pid] = 1;
    echo "create consumer child progress: {$pid} \n";
}

// 等待所有子进程结束，回收资源，防止僵尸子进程
while(!empty($childList)){
    $childPid = pcntl_wait($status);
    if ($childPid > 0){
        unset($childList[$childPid]);
    }
}
echo "({$parentPid})main progress end!\n";
```
由于通过消息队列去取数据时，只有一个进程能取到，所以不需要额外的锁或信号量。  
  
  
2. 信号量与共享内存  
------------
信号量：是系统提供的一种原子操作，一个信号量，同时只有你个进程能操作。一个进程获得了某个信号量，就必须被该进程释放掉。  
  
共享内存：是系统在内存中开辟的一块公共的内存区域，任何一个进程都可以访问，在同一时刻，可以有多个进程访问该区域，为了保证数据的一致性，需要对该内存区域加锁或信号量。  
  
以下，创建多个进程修改内存中的同一个值。  
```php
$parentPid = posix_getpid();
echo "parent progress pid:{$parentPid}\n";
$childList = array();
  
// 创建共享内存,创建信号量,定义共享key
$shm_id = ftok(__FILE__,'m');
$sem_id = ftok(__FILE__,'s');
$shareMemory = shm_attach($shm_id);
$signal = sem_get($sem_id);

const SHARE_KEY = 1;
// 生产者
function producer(){
    global $shareMemory;
    global $signal;
    $pid = posix_getpid();
    $repeatNum = 5;
    for ( $i = 1; $i <= $repeatNum; $i++) {
        // 获得信号量
        sem_acquire($signal);
  
        if (shm_has_var($shareMemory,SHARE_KEY)){
            // 有值,加一
            $count = shm_get_var($shareMemory,SHARE_KEY);
            $count ++;
            shm_put_var($shareMemory,SHARE_KEY,$count);
            echo "({$pid}) count: {$count}\n";
        }else{
            // 无值,初始化
            shm_put_var($shareMemory,SHARE_KEY,0);
            echo "({$pid}) count: 0\n";
        }
        // 用完释放
        sem_release($signal);
  
        $rand = rand(1,3);
        sleep($rand);
    }
}
function createProgress($callback){
    $pid = pcntl_fork();
    if ( $pid == -1) {
        // 创建失败
        exit("fork progress error!\n");
    } else if ($pid == 0) {
        // 子进程执行程序
        $pid = posix_getpid();
        $callback();
        exit("({$pid})child progress end!\n");
    }else{
        // 父进程执行程序
        return $pid;
    }
}
// 3个写进程
for ($i = 0; $i < 3; $i ++ ) {
    $pid = createProgress('producer');
    $childList[$pid] = 1;
    echo "create producer child progress: {$pid} \n";
}

// 等待所有子进程结束
while(!empty($childList)){
    $childPid = pcntl_wait($status);
    if ($childPid > 0){
        unset($childList[$childPid]);
    }
}

// 释放共享内存与信号量
shm_remove($shareMemory);
sem_remove($signal);
echo "({$parentPid})main progress end!\n";
```
  
3.信号  
------------
信号是一种系统调用。通常我们用的kill命令就是发送某个信号给某个进程的。具体有哪些信号可以在liunx/mac中运行(kill -l)查看。  
下面这个例子中，父进程等待5秒钟，向子进程发送sigint信号。子进程捕获信号，掉信号处理函数处理。  
```php
$parentPid = posix_getpid();
echo "parent progress pid:{$parentPid}\n";
  
// 定义一个信号处理函数
function sighandler($signo) {
    $pid = posix_getpid();
    echo "{$pid} progress,oh no ,I'm killed!\n";
    exit(1);
}
  
$pid = pcntl_fork();
if ( $pid == -1) {
    // 创建失败
    exit("fork progress error!\n");

} else if ($pid == 0) {
    // 子进程执行程序
    // 注册信号处理函数
    declare(ticks=10);
    pcntl_signal(SIGINT, "sighandler");
    $pid = posix_getpid();
    while(true){
        echo "{$pid} child progress is running!\n";
        sleep(1);
    }
    exit("({$pid})child progress end!\n");

}else{
    // 父进程执行程序
    $childList[$pid] = 1;
    // 5秒后,父进程向子进程发送sigint信号.
    sleep(5);
    posix_kill($pid, SIGINT);
    sleep(5);
}
echo "({$parentPid})main progress end!\n";
```
上面示例有问题，会造成僵尸子进程，因父进程存在的时间不长，僵尸存在时间也不长。

  
4.管道（有名管道）
-----------
管道是比较常用的多进程通信手段，管道分为无名管道与有名管道，无名管道只能用于具有亲缘关系的进程间通信，而有名管道可以用于同一主机上任意进程。这里只介绍有名管道。下面的例子，子进程写入数据，父进程读取数据。  
```php
// 定义管道路径,与创建管道
$pipe_path = '/data/test.pipe';
if(!file_exists($pipe_path)){
    if(!posix_mkfifo($pipe_path,0664)){
        exit("create pipe error!");
    }
}
$pid = pcntl_fork();
if ( $pid == -1) {
    // 创建失败
    exit("fork progress error!\n");

} elseif ($pid == 0){
    // 子进程,向管道写数据
    $file = fopen($pipe_path,'w');
    while (true){
        fwrite($file,'hello world');
        $rand = rand(1,3);
        sleep($rand);
    }
    exit('child end!');

} else {
    // 父进程,从管道读数据
    $file = fopen($pipe_path,'r');
    while (true){
        $rel = fread($file,20);
        echo "{$rel}\n";
        $rand = rand(1,2);
        sleep($rand);
    }
}
```
  
  
当然借助第三方存储也可以，比如redis等等
redis队列
redis锁
...




