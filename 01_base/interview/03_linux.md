


如何检测 web 服务慢
-----------
top:查看系统性能cpu、mem、io     

Nginx：访问日志中最后一个字段加入$request_time，分析nginx  
列出 php 页面请求时间超过3秒的页面，并统计其出现的次数，显示前100条  
cat access.log|awk '($NF > 1 && $7~/\.php/){print $7}'|sort -n|uniq -c|sort -nr|head -100  

磁盘是否已满df -h  

代码中排查，开头写入时间，结尾写入时间  


自动脚本
------------
crond 是 linux 下用来周期性的执行某种任务或等待处理某些事件的一个守护进程。Linux 下的任务调度分为两类，系统任务调度和用户任务调度。  

1) 系统任务调度：系统周期性所要执行的工作，比如写缓存数据到硬盘、日志清理等。在/etc目录下有一个 crontab 文件，这个就是系统任务调度的配置文件。    

2) 用户任务调度：用户定期要执行的工作，比如用户数据备份、定时邮件提醒等。用户可以使用 crontab 工具来定制自己的计划任务。所有用户定义的 crontab 文件都被保存在 /var/spool/cron目录中。其文件名与用户名一致。  

语法：
```
minute hour day month week command  

还可以使用以下特殊字符：  

星号（*）：代表所有可能的值，例如 month 字段如果是星号，则表示在满足其它字段的制约条件后每月都执行该命令操作。
逗号（,）：可以用逗号分隔来指定多个值，例如，“1,2,5,7,8,9”
中杠（-）：可以用整数之间的中杠表示一个整数范围，例如“2-6”表示“2,3,4,5,6”
正斜线（/）：可以用正斜线指定时间的间隔频率，例如“0-23/2”表示每两小时执行一次。同时正斜线可以和星号一起使用，例如*/10，如果用在 minute 字段，表示每十分钟执行一次。
```

inux批量删除进程的两种方法
---------------
介绍两种方法。要kill的进程都有共同的字串。
``` 
方式1：
kill -9 `ps -ef |grep xxx|awk '{print $2}' `   
 
kill -9后面的符号是Tab键上方那个。
 
如上就是kill -9 `列出进程，找到包含xxx的行，输出pid的列`
 
kill、ps、grep都是很常用的命令了。
 
awk的作用是输出某一列，{print $2}就是输出第二列，如上即是pid这一列。这里有一篇awk的教程/os/201307/230381.html。
 
方式2：
ps -ef | grep xxx | grep -v root | awk '{print $2}' | xargs kill -9  
 
grep -v这个参数的作用是排除某个字符。所以这里排除了root执行的命令。
 
之后也利用awk找到pid这一列。
 
最后的xargs是从标准输出获取参数并执行命令的程序，即从前面的命令获取输出作为参数来执行下一个命令。
```


Nginx负载均衡方案  
------------
常用的几种方式：

1) 轮询 (Round Robin)  
根据Nginx配置文件中的顺序，依次把客户端的Web请求分发到不同的后端服务器。每个请求按时间顺序逐一分配到不同的后端服务器，如果后端服务器 down 掉，能自动剔除。
```
upstream web { 
	server server1; 
	server server2; 
} 
```

2) 最少连接  
Web请求会被转发到连接数最少的服务器上。least_conn算法很简单，首选遍历后端集群，比较每个后端的 conns/weight，选取该值最小的后端。如果有多个后端的 conns/weight值同为最小的，那么对它们采用加权轮询算法。  
```
upstream web { 
	least_conn; 
	server server1; 
	server server2; 
} 
```

3）IP地址哈希
同一客户端连续的 Web 请求可能会被分发到不同的后端服务器进行处理，因此如果涉及到会话Session，可以使用基于 IP 地址哈希的负载均衡方案。这样的话，同一客户端连续的 Web 请求都会被分发到同一服务器进行处理（每个请求按访问ip的hash结果分配，这样每个访客固定访问一个后端服务器，可以解决 session 的问题）。  
```
upstream web { 
	ip_hash;
	server server1; 
	server server2; 
} 
```

4) 权重 (Weighted Load Balancing)
可以根据服务器的性能状况有选择的分发 web 请求。指定轮询几率，weight 越高、访问比率越大。weight=2，意味着每接收到3个请求，前2个请求会被分发到第一个服务器，第3个请求会分发到第二个服务器，其它的配置同轮询配置。
```
upstream web { 
    server server1 weight=2; 
    server server2; 
} 
```
基于权重的负载均衡和基于 IP 地址哈希的负载均衡可以组合在一起使用。

5) fair（第三方）
按后端服务器的响应时间来分配请求，响应时间短的优先分配。
```
upstream web {
    server server1;
    server server2;
    fair;
}
```

6) url_hash（第三方）
按访问 url 的 hash 结果来分配请求，使每个 url 定向到同一个后端服务器，后端服务器为缓存时比较有效。 hash_method 是使用的 hash 算法
```
upstream web {
	server server1:3128;
	server server1:3128;
	hash $request_uri;
	hash_method crc32;
}
```

每个设备的状态设置为:
```
1.down 表示单前的 server 暂时不参与负载 
2.weight 默认为1.weight 越大，负载的权重就越大。 
3.max_fails：允许请求失败的次数默认为1。当超过最大次数时，返回 proxy_next_upstream 模块定义的错误 
4.fail_timeout:max_fails 次失败后，暂停的时间。 
如： server server1 max_fails=3 fail_timeout=30s;

5.backup：其它所有的非backup机器down或者忙的时候，请求backup机器。所以这台机器压力会最轻。sorry server 提供非业务功能。
```


Keepalived
------------
Keepalived软件主要是通过VRRP(虚拟路由器冗余协议）协议实现高可用功能的。VRRP 出现的目的就是为了解决静态路由单点故障问题的，它能够保证当个别节点宕机时，整个网络可以不间断地运行。  


LVS 的原理及配置
------------



在浏览器输入 www.xiaomi.com 发生的全部过程
------------
对于使用 CDN 的网站（从运维的角度解释）：  

１，解析域名：先查找浏览器 DNS 缓存，查找不到然后就会到系统的 hosts 文件和系统缓存中查找，若找不到，则继续查找路由器的 DNS 缓存，若还没有，则查找 Local DNS 缓存，若没有则 Local DNS 会从根开始进行迭代查找，最终找到域名的 CNAME　记录。浏览器需要再次对获得的 CNAME 域名进行解析，再次过程中会用到智能 CDN 的解析得到离用户最近的缓存服务器的 IP  

2，建立连接：查找到 IP 记录后，然后就开始进行 TCP 三次握手建立连接  

3，传输数据：客户端向缓存服务器发出请求，根据 HTTP 中指定的资源，缓存服务器中有的会直接让 tcp 传输给客户端，没有的则会请求源站，源站可能还会有像 varnish，squid 这样的缓存服务器，若有资源则直接返回给 CDN 的缓存服务器，若没有则向 Web 服务器（像 nginx，apache，LVS）请求，web 服务器将动态请求转发到后端的应用服务（像 php，tomcat，uwsgi 等），若后端配置有 redis，memcache 数据库缓存服务器，应用服务器会先请求 NoSQL，若没有则 NoSQL 请求数据库服务器（像 MySQL/MariaDB 等），然后将数据发送给前端，并在自身缓存一份。然后服务器将数据传送到 CDN 的缓存服务器，再将数据传送到客户端。  



vi 和 vim 的区别
------------
它们都是多模式编辑器，不同的是 vim 是 vi 的升级版本，它不仅兼容 vi 的所有指令，而且还有一些新的特性在里面。vim 的这些优势主要体现在以下几个方面：

1、多级撤消    
我们知道在 vi 里，按 u 只能撤消上次命令，而在 vim 里可以无限制的撤消。  

2、易用性  
vi只能运行于 unix 中，而 vim 不仅可以运行于 unix, windows, mac 等多操作平台。

3、语法加亮  
vim 可以用不同的颜色来加亮你的代码。

4、可视化操作  
就是说 vim 不仅可以在终端运行，也可以运行于 x window、 mac os、 windows。

5、对 vi 的完全兼容  
某些情况下，你可以把 vim 当成 vi 来使用。vi 和 vim 都是 Linux 中的编辑器，不同的是 vim 比较高级，可以视为 vi 的升级版本。vi 使用于文本编辑，但是 vim 更适用于coding。



awk 命令
-------------
AWK是一种处理文本文件的语言，是一个强大的文本分析工具。通常，awk 是以文件的一行为处理单位的。awk 每接收文件的一行，然后执行相应的命令，来处理文本。

语法： awk -F'指定的分隔符，默认空格' '{pattern + action}' filenames  
pattern 表示 AWK 在数据中查找的内容，而 action 是在找到匹配内容时所执行的一系列命令。pattern就是要表示的正则表达式，用斜杠括起来。



nginx查看日志访问IP最高的20个IP记录
-----------
```
awk '{print $1}' xxx.log | sort | uniq -c | sort -nr -k1 | head -n 20

说明:  
awk '{ print $1}'：取数据的低1域（第1列）
sort：对IP部分进行排序。
uniq -c：打印每一重复行出现的次数。（并去掉重复行）
sort -nr -k1：按照重复行出现的次序倒序排列, -k1以第一列为标准排序。
head -n 20：取排在前20位的IP。


给一段ngxin log示例文件：

61.185.165.77 - - [17/Oct/2018:00:15:54 +0800] "GET /core/handler/file_uploader?res_name=photo&source_from=ueditor&action=config&&noCache=1539706556341 HTTP/1.1" 200 655 "http://www.zhuisutianyuan.com/goods/admin/add?id=271&is_self_run=1" "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.26 Safari/537.36 Core/1.63.6726.400 QQBrowser/10.2.2265.400" 0.007 61.185.165.77
61.185.165.77 - - [17/Oct/2018:00:15:54 +0800] "GET /js/ueditor1_4_3/third-party/zeroclipboard/ZeroClipboard.swf?noCache=1539706556817 HTTP/1.1" 200 3933 "http://www.zhuisutianyuan.com/goods/admin/add?id=271&is_self_run=1" "Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/63.0.3239.26 Safari/537.36 Core/1.63.6726.400 QQBrowser/10.2.2265.400" 0.000 61.185.165.77



将某天(比如17/Oct/2018全天)的访问日志放到a.txt文本  
cat access.log |sed -rn '/17\/Oct\/2018/p' > a.txt 
```


nginx访问量统计
```
1.根据访问IP统计UV
awk '{print $1}' access.log | uniq -c |wc -l

2.统计访问URL统计PV
awk '{print $7}' access.log|wc -l

3.查询访问最频繁的URL
awk '{print $7}' access.log | uniq -c |sort -n -k 1 -r|more

4.查询访问最频繁的IP
awk '{print $1}' access.log | uniq -c |sort -n -k 1 -r|more

5.根据时间段统计查看日志
cat access.log| sed -n '/14\/Mar\/2015:21/,/14\/Mar\/2015:22/p'|more
```



IP相关统计
------------
```
统计IP访问量（独立ip访问数量）
awk '{print $1}' access.log | uniq | wc -l

查看某一时间段的IP访问量(4-5点)
grep "07/Apr/2017:0[4-5]" access.log | awk '{print $1}' | uniq -c| sort -nr  
grep "07/Apr/2017:0[4-5]" access.log | awk '{print $1}' | uniq -c| sort -nr | wc -l  

查看访问最频繁的前100个IP
awk '{print $1}' access.log | uniq -c | sort -rn -k1| head -n 100

查看访问100次以上的IP
awk '{print $1}' access.log | sort -n |uniq -c |awk '{if($1 >100) print $0}'|sort -rn

查询某个IP的详细访问情况，按访问频率排序（比如页面的访问频次）
grep '127.0.0.1' access.log |awk '{print $7}'| uniq -c |sort -rn |head -n 100
```


页面访问统计
------------
```
查看访问最频的页面(TOP100)
awk '{print $7}' access.log |uniq -c | sort -rn | head -n 100

查看访问最频的页面([排除php页面】(TOP100)
grep -v ".php"  access.log | awk '{print $7}' | sort |uniq -c | sort -rn | head -n 100 

查看页面访问次数超过100次的页面
cat access.log | cut -d ' ' -f 7 | uniq -c | awk '{if ($1 > 100) print $0}' |sort -nr | less

查看最近1000条记录，访问量最高的页面
tail -1000 access.log |awk '{print $7}'| uniq -c|sort -nr|less


每秒请求量统计
统计每秒的请求数,top100的时间点(精确到秒)
awk '{print $4}' access.log |cut -c 14-21|sort|uniq -c|sort -nr|head -n 100

每分钟请求量统计
统计每分钟的请求数,top100的时间点(精确到分钟)
awk '{print $4}' access.log |cut -c 14-18|sort|uniq -c|sort -nr|head -n 100

每小时请求量统计
统计每小时的请求数,top100的时间点(精确到小时)
awk '{print $4}' access.log |cut -c 14-15|sort|uniq -c|sort -nr|head -n 100
```


性能分析
-----------
在nginx log中最后一个字段加入$request_time
```
列出传输时间超过 3 秒的页面，显示前20条
cat access.log|awk '($NF > 3){print $7}'|sort -n|uniq -c|sort -nr|head -20

列出php页面请求时间超过3秒的页面，并统计其出现的次数，显示前100条
cat access.log|awk '($NF > 1 &&  $7~/\.php/){print $7}'|sort -n|uniq -c|sort -nr|head -100
```


蜘蛛抓取统计
-----------
统计蜘蛛抓取次数
grep 'Baiduspider' access.log |wc -l

统计蜘蛛抓取404的次数
grep 'Baiduspider' access.log |grep '404' | wc -l



TCP连接统计
-----------
```
查看当前TCP连接数
netstat -tan | grep "ESTABLISHED" | grep ":80" | wc -l

用tcpdump嗅探80端口的访问看看谁最高
tcpdump -i eth0 -tnn dst port 80 -c 1000 | awk -F"." '{print $1"."$2"."$3"."$4}' | uniq -c | sort -nr
```


nginx日志格式
-----------
在nginx在配置文件中的http块中配置，比如：
log_format  access  '$_remote_addr - $remote_user [$time_local] "$request" '
        '$status $body_bytes_sent "$http_referer" '
        '"$http_user_agent" $request_time $remote_addr';

具体参数说明

```
参数                      说明                                         示例
$remote_addr             客户端地址                                    211.28.65.253

$remote_user             客户端用户名称                                --

$time_local              访问时间和时区                                18/Jul/2012:17:00:01 +0800

$request                 请求的URI和HTTP协议                           "GET /article-10000.html HTTP/1.1"

$http_host               请求地址，即浏览器中你输入的地址（IP或域名）     www.wang.com 192.168.100.100

$status                  HTTP请求状态                                  200
$upstream_status         upstream状态                                  200
$body_bytes_sent         发送给客户端文件内容大小                        1547

$http_referer            url跳转来源                                   https://www.baidu.com/

$http_user_agent         用户终端浏览器等信息                           "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; SV1; GTB7.0; .NET4.0C;

$ssl_protocol            SSL协议版本                                   TLSv1
$ssl_cipher              交换数据中的算法                               RC4-SHA

$upstream_addr           后台upstream的地址，即真正提供服务的主机地址     10.10.10.100:80

$request_time            整个请求的总时间                               0.205
$upstream_response_time  请求过程中，upstream响应时间                    0.002
```







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



