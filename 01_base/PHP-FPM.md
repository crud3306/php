

PHP-FPM（FastCGI Process Manager）是 PHP FastCGI 运行模式的一个进程管理器，从它的定义可以看出，FPM 的核心功能是进程管理，那么它用来管理什么进程呢？这个问题就需要从 FastCGI 说起了。

概述
--------
FastCGI 是 Web 服务器（如：Nginx、Apache）和处理程序之间的一种通信协议，它是与 Http 类似的一种应用层通信协议，注意：它只是一种协议！

前面曾一再强调，PHP 只是一个脚本解析器，你可以把它理解为一个普通的函数，输入是 PHP 脚本，输出是执行结果。

假如我们想用 PHP 代替 shell，在命令行中执行一个文件，那么就可以写一个程序来嵌入 PHP 解析器，这就是 cli 模式，这种模式下 PHP 就是普通的一个命令工具。

接着我们又想：能不能让 PHP 处理 http 请求呢？  
这时就涉及到了网络处理，PHP 需要接收请求、解析协议，然后处理完成返回请求。在网络应用场景下，PHP 并没有像 Golang 那样实现 http 网络库，而是实现了 FastCGI 协议，然后与 web 服务器配合实现了 http 的处理，web 服务器来处理 http 请求，然后将解析的结果再通过 FastCGI 协议转发给处理程序，处理程序处理完成后将结果返回给 web 服务器，web 服务器再返回给用户。


PHP 实现了 FastCGI 协议的解析，但是并没有具体实现网络处理，一般的处理模型：多进程、多线程，多进程模型通常是主进程只负责管理子进程，而基本的网络事件由各个子进程处理，nginx、fpm 就是这种模式；另一种多线程模型与多进程类似，只是它是线程粒度，通常会由主线程监听、接收请求，然后交由子线程处理，memcached 就是这种模式，有的也是采用多进程那种模式：主线程只负责管理子线程不处理网络事件，各个子线程监听、接收、处理请求，memcached 使用 udp 协议时采用的是这种模式。


基本实现
----------
概括来说，fpm 的实现就是创建一个 master 进程，在 master 进程中创建并监听 socket，然后 fork 出多个子进程，这些子进程各自 accept 请求，子进程的处理非常简单，它在启动后阻塞在 accept 上，有请求到达后开始读取请求数据，读取完成后开始处理然后再返回，在这期间是不会接收其它请求的，也就是说 fpm 的子进程同时只能响应一个请求，只有把这个请求处理完成后才会 accept 下一个请求，这一点与 nginx 的事件驱动有很大的区别，nginx 的子进程通过 epoll 管理套接字，如果一个请求数据还未发送完成则会处理下一个请求，即一个进程会同时连接多个请求，它是非阻塞的模型，只处理活跃的套接字。

fpm 的 master 进程与 worker 进程之间不会直接进行通信，master 通过共享内存获取 worker 进程的信息，比如 worker 进程当前状态、已处理请求数等，当 master 进程要杀掉一个 worker 进程时则通过发送信号的方式通知 worker 进程。


fpm 可以同时监听多个端口，每个端口对应一个 worker pool，而每个 pool 下对应多个 worker 进程，类似 nginx 中 server 概念。

在 php-fpm.conf 中通过[pool name]声明一个 worker pool：
```sh
[web1]
listen = 127.0.0.1:9000
...

[web2]
listen = 127.0.0.1:9001
...
```

启动 fpm 后查看进程：
```sh
$ ps -aux|grep fpm
root     27155  0.0  0.1 144704  2720 ?  Ss   15:16   0:00 php-fpm: master process (/usr/local/php7/etc/php-fpm.conf)
nobody   27156  0.0  0.1 144676  2416 ?  S    15:16   0:00 php-fpm: pool web1
nobody   27157  0.0  0.1 144676  2416 ?  S    15:16   0:00 php-fpm: pool web1
nobody   27159  0.0  0.1 144680  2376 ?  S    15:16   0:00 php-fpm: pool web2
nobody   27160  0.0  0.1 144680  2376 ?  S    15:16   0:00 php-fpm: pool web2
```





php-fpm 进程池优化方法
------------------
php-fpm进程池开启进程有两种方式。  
一种是：static，直接开启指定数量的php-fpm进程，不再增加或者减少；  
另一种：dynamic，开始时开启一定数量的php-fpm进程，当请求量变大时，动态的增加php-fpm进程数到上限，当空闲时自动释放空闲的进程数到一个下限。  

这两种不同的执行方式，可以根据服务器的实际需求来进行调整。

要用到的一些参数，分别是
```
pm
pm.max_children
pm.start_servers
pm.min_spare_servers
pm.max_spare_servers
```
pm表示使用那种方式，有两个值可以选择，就是static（静态）或者dynamic（动态）。

下面4个参数的意思分别为：
```
pm.max_children：静态方式下开启的php-fpm进程数量，在动态方式下他限定php-fpm的最大进程数（这里要注意pm.max_spare_servers的值只能小于等于pm.max_children）

pm.start_servers：动态方式下的起始php-fpm进程数量。

pm.min_spare_servers：动态方式空闲状态下的最小php-fpm进程数量。

pm.max_spare_servers：动态方式空闲状态下的最大php-fpm进程数量。
```

如果dm设置为static，那么其实只有pm.max_children这个参数生效。系统会开启参数设置数量的php-fpm进程。

如果dm设置为dynamic，4个参数都生效。系统会在php-fpm运行开始时启动pm.start_servers个php-fpm进程，然后根据系统的需求动态在pm.min_spare_servers和pm.max_spare_servers之间调整php-fpm进程数。


PS.  
pm.min_spare_servers、pm.max_spare_servers这2个参数需注意，否则会报错：  
pm.start_servers(70) must not be less than pm.min_spare_servers(15) and not greater than pm.max_spare_servers(60)
要求pm.start_servers的值在pm.min_spare_servers和pm.max_spare_servers之间







