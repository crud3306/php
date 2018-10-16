linux：
-----------




服务器
-----------
Apache与Nginx的优缺点比较
-----------
1、nginx相对于apache的优点：
轻量级，比apache 占用更少的内存及资源。高度模块化的设计，编写模块相对简单  
抗并发，nginx 处理请求是异步非阻塞，多个连接（万级别）可以对应一个进程，而apache 则是阻塞型的，是同步多进程模型，一个连接对应一个进程，在高并发下nginx   能保持低资源低消耗高性能  
nginx处理静态文件好，Nginx 静态处理性能比 Apache 高 3倍以上  
  
2、apache 相对于nginx 的优点：  
apache 的rewrite 比nginx 的rewrite 强大 ，模块非常多，基本想到的都可以找到 ，比较稳定，少bug ，nginx 的bug 相对较多  
  
3：原因：这得益于Nginx使用了最新的epoll（Linux 2.6内核）和kqueue（freebsd）网络I/O模型，而Apache则使用的是传统的select模型。目前Linux下能够承受高并发访问的 Squid、Memcached都采用的是epoll网络I/O模型。   处理大量的连接的读写，Apache所采用的select网络I/O模型非常低效。  



