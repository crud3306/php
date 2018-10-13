
需看的地址：  
-----------
http://www.php.cn/php-weizijiaocheng-393701.html  
http://www.php.cn/php-weizijiaocheng-393700.html  
https://blog.csdn.net/Px01Ih8/article/details/80823381  
https://www.cnblogs.com/zypphp/p/8185155.html  
https://blog.csdn.net/dennis_ukagaka/article/details/76911655?ref=myread  


php方面的：
===========

请用最简单的语言告诉我php是什么?  
-----------
php全称：hypertext preprocessor，是一种用来开发动态网站的服务器脚本语言。  
    

echo print print_r printf sprintf var_dump 区别  
-----------
echo与print是PHP语句，也是一个语言结构, print_r是函数。这一点可以用函数function_exists('要验证的函数名')来验证。   
  
echo 和 print 之间的差异：  
echo - 能够输出一个或多个字符串  
print - 只能输出一个字符串，并始终返回 1  
提示：echo 比 print 稍快，因为它不返回任何值。  
  
echo，print 与 print_r的差异：  
echo，print 只能打印出简单类型变量的值(如int,string)    
echo，print 因为不是函数，所以后面可以带括号，也可以不带括号。  
print_r() 是函数，后面需带括号，可以打印出复杂类型变量的值(如数组,对象)，一次只能打印一个变量      
print_r() 返回值是bool，一般是true  
  

isset()、empty()、is_null() 区别
-----------
1）isset — 检测变量是否已设置且非NULL则返回TRUE；  
注意的是 null 字符（"\0"）并不等同于 PHP 的 NULL 常量。  
如果已经使用 unset() 释放了一个变量之后，它将不再是 isset()。  
官方文档：http://php.net/manual/zh/function.isset.php  
  
2）empty — 检查一个变量是否为空。  
官方文档：http://php.net/manual/zh/function.empty.php  
以下的东西被认为是空的：  
"" (空字符串)  
0 (作为整数的0)  
0.0 (作为浮点数的0)  
"0" (作为字符串的0)  
NULL  
FALSE  
array() (一个空数组)  
$var; (一个声明了，但是没有值的变量)  
  
3）is_null — 检测变量是否为 NULL，是则返回TRUE，否则返回FALSE。  
在下列情况下一个变量被认为是 NULL：  
被赋值为 NULL。  
尚未被赋值。  
被unset()。  
  
    
  
require与include 区别  
-----------
在php中 require和include 都是用来引用其它php文件的，后面可以带括号也可以省略。  
如：require("xxx.php"); 或者 require "xxxx.php";  
   
require 一般放在 PHP 文件的最前面，程序在执行前就会先导入要引用的文件；  
include 一般放在程序的流程控制中，当程序执行时碰到才会引用，简化程序的执行流程。  
require 引入的文件有错误时，执行会中断，并返回一个致命错误；  
include 引入的文件有错误时，会继续执行，并返回一个警告。  
   
加_once后缀(如：require_once，include_once)表示已加载的不加载   
  


cookie与session区别
-----------
1、cookie数据存放在客户的浏览器上，session数据放在服务器上。  
2、cookie不是很安全，别人可以分析存放在本地的COOKIE并进行COOKIE欺骗
考虑到安全应当使用session。  
3、session会在一定时间内保存在服务器上。当访问增多，会比较占用你服务器的性能
考虑到减轻服务器性能方面，应当使用COOKIE。  
4、单个cookie保存的数据不能超过4K，很多浏览器都限制一个站点最多保存20个cookie。

cookie 和session 的联系：  
session一般是借助cookie来传递sessionid。
  


接口与类有什么区别
-----------  
1、接口类似于类，但接口的成员都没有执行方式；类除了这四种成员之外还可以有别的成员(如字段)。  
2、不能实例化一个接口，接口只包括成员的签名；而类可以实例化(abstract类除外)。  
3、接口没有构造函数，类有构造函数。  
4、接口的成员没有任何修饰符，其成员总是公共的，而类的成员则可以有修饰符(如：虚拟或者静态)。  
5、派生于接口的类必须实现接口中所有成员的执行方式，而从类派生则不然。  


oop的三大特征是什么
-----------
1、封装性：也称为信息隐藏，就是将一个类的使用和实现分开，只保留部分接口和方法与外部联系，或者说只公开了一些供开发人员使用的方法。于是开发人员只需要关注这个类如何使用，而不用去关心其具体的实现过程，这样就能实现MVC分工合作，也能有效避免程序间相互依赖，实现代码模块间松藕合。

2、继承性：就是子类自动继承其父级类中的属性和方法，并可以添加新的属性和方法或者对部分属性和方法进行重写。继承增加了代码的可重用性。PHP只支持单继承，也就是说一个子类只能有一个父类。

3、多态性：子类继承了来自父级类中的属性和方法，并对其中部分方法进行重写。于是多个子类中虽然都具有同一个方法，但是这些子类实例化的对象调用这些相同的方法后却可以获得完全不同的结果，这种技术就是多态性。多态性增强了软件的灵活性。



svn、git 区别
-----------




  
数组内置的排序方法有哪些？  
-----------
sort($array); //数组升序排序  
rsort($array); //数组降序排序  
   
asort($array);  //根据值，以升序对关联数组进行排序  
ksort($array);  //根据建，以升序对关联数组进行排序  
   
arsort($array);  //根据值，以降序对关联数组进行排序  
krsort($array);  //根据键，以降序对关联数组进行排序  
  
  
用PHP写出显示客户端IP与服务器IP的代码
-----------
$_SERVER["REMOTE_ADDR"]
$_SERVER["SERVER_ADDR"]
  
  
php序列化和反序列化用的函数  
-----------
serialize() 序列化  
unserialize() 反序列化  


PHP读取文件速度快，还是读取mysql的数据快？为何？
-----------
一般情况下读文件 要比 读数据库 快；因为：读数据库每次要建立连接，判断连接是否成功，然后执行操作，返回结果等等，执行步骤多多，所以读写文件更快。
如果同目录文件又非常多的情况下，读数据库大于读文件。
  

SESSION 保存在服务器的哪里？
-----------
session保存位置通过php.ini指定，可存在指定目录的文件中或数据库中或内存中。  
我们可以在php.ini文件查找session.save_handler
与 session.save_path，如果session.save_handler=file，则session存储在session.save_path提定的文件中。
如果session.save_handler=user，则存储在用户自定义位置，这时我们通过查看项目代码中的session_set_save_handler函数，来确定session存储在哪里。
  
  
你所知道的缓存技术有哪些，分别做下简单介绍  
------------
模板静态化：  
借助ob缓存，成生静态模板；主要通过ob_start() ob_get_contents() ob_end_flush()等函数来实现    
  
内存缓存：  
借助memcache、redis将查询数据在内存中做缓存，减少数据库负载，提升访问速度。     
  
PHP的缓冲器、加速器：  
Zend Opcache、XCache、APC、eAccelerator等等。  
原理：通过将预编译的脚本文件存储在共享内存中供以后使用，从而避免了从磁盘读取代码并进行编译的时间消耗。  
  
MYSQL缓存：  
  
cdn 
dns缓存  


HTTP 协议的原理，什么是全双工，什么是半双工?  
------------
HTTP协议是Hyper Text Transfer Protocol（超文本传输协议）的缩写,是用于从万维网（WWW:World Wide Web ）服务器传输超文本到本地浏览器的传送协议，是无状态协议。  
HTTP协议定义Web客户端如何从Web服务器请求Web页面，以及服务器如何把Web页面传送给客户端。HTTP协议采用了请求/响应模型。客户端向服务器发送一个请求报文，请求报文包含请求的方法、URL、协议版本、请求头部和请求数据。服务器以一个状态行作为响应，响应的内容包括协议的版本、成功或者错误代码、服务器信息、响应头部和响应数据。  

全双工（Full Duplex）是指请求和响应同时进行，也就是说在发送数据的同时也能够接收数据，两者同步进行。这好像我们平时打电话一样，说话的同时也能够听到对方的声音。目前的网卡一般都支持全双工。  

半双工（Half Duplex），所谓半双工就是指一个时间段内只有一个动作发生，举个简单例子:一条窄窄的马路，同时只能有一辆车通过，当目前有两量车对开，这种情况下就只能一辆先过，等到头儿后另一辆再开，这个例子就形象的说明了半双工的原理。早期的对讲机、以及早期集线器等设备都是基于半双工的产品。随着技术的不断进步，半双工会逐渐退出历史舞台。    


HTTPS和HTTP的区别： 
-------------
https协议需要到ca申请证书，一般免费证书很少，需要交费。 
http是超文本传输协议，信息是明文传输，https 则是具有安全性的ssl加密传输协议。 
http和https使用的是完全不同的连接方式用的端口也不一样,前者是80,后者是443。 


URI和URL的区别  
-------------
URI，是uniform resource identifier，统一资源标识符，用来唯一的标识一个资源。  
Web上可用的每种资源如HTML文档、图像、视频片段、程序等都是一个来URI来定位的
URI一般由三部组成：  
①访问资源的命名机制  
②存放资源的主机名  
③资源自身的名称，由路径表示，着重强调于资源。  
  
URL是uniform resource locator，统一资源定位器，它是一种具体的URI，即URL可以用来标识一个资源，而且还指明了如何locate这个资源。  
URL是Internet上用来描述信息资源的字符串，主要用在各种WWW客户程序和服务器程序上，特别是著名的Mosaic。  
采用URL可以用一种统一的格式来描述各种信息资源，包括文件、服务器的地址和目录等。URL一般由三部组成：  
①协议(或称为服务方式)  
②存有该资源的主机IP地址(有时也包括端口号)  
③主机资源的具体地址。如目录和文件名等   
  

mt_rand() 与 rand() 区别
-----------
mt_rand()用法跟rand()类似，但是mt_rand()的执行效率更高，平常使用也推荐用mt_rand().
  
  
PHP中array_merge函数与array+array的区别   
-----------
区别如下：  
当下标为数值时，array_merge()不会覆盖掉原来的值，但array＋array合并数组则会把最先出现的值作为最终结果返回，而把后面的数组拥有相同键名的那些值“抛弃”掉（不是覆盖）.   
  
当下标为字符时，array＋array仍然把最先出现的值作为最终结果返回，而把后面的数组拥有相同键名的那些值“抛弃”掉，但array_merge()此时会覆盖掉前面相同键名的值.   
  
  

php.ini中 safe mod 关闭，影响哪些函数和参数，至少写6个
-----------
```
move_uploaded_file() exec()
system() passthru()
popen() fopen()
mkdir() rmdir()
rename() unlink()
copy() chgrp()
chown() chmod()
touch() symlink()
link() parse_ini_file()
set_time_limit() max_execution_time mail()
```






mysql方面的：
===========

请说出mysql常用存储引擎？memory存储引擎的特点？  
-----------
Myisam、InnoDB、memory  
memory的特点是将表存到内存中，数度快，重启后数据丢失   
  

索引算法Hash与BTree的区别
-----------
https://blog.csdn.net/u011305680/article/details/55520853  


select * from table where (ID = 10) or (ID = 32) or (ID = 22) or (ID = 76) or (ID = 13) or (ID = 44) 让结果按10，32，22，76，13，44的顺序检索出来,请问如何书写?
------------
```sql
select * from table
where id in (10,32,22,76,13,44)
order by charindex(id,'10,32,22,76,13,44') desc  
```





nosql方面的：
===========

memcache、redis 区别，及应用场景  
-----------
相同点  
1 都是在内存中进行数据的存取
2 都支持k/v的方式存取数据
  
不同点  
1)、数据支持类型
memcache只有string类型的数据。  
Redis不仅仅支持简单的string类型数据，同时还提供list，set，hash等数据结构的存储。

2)、存储方式
Memecache把数据全部存在内存之中，断电后会数据会丢失。
Redis支持数据的持久化，可以将内存中的数据保持在磁盘中，重启的时候可以再次加载进行使用。  
3）value大小
redis最大可以达到1GB，而memcache只有1MB

4)、使用底层模型不同
它们之间底层实现方式 以及与客户端之间通信的应用协议不一样。
Redis直接自己构建了VM机制，因为一般的系统调用系统函数的话，会浪费一定的时间去移动和请求。

总结：
1.Redis使用最佳方式是全部数据in-memory。  
2.Redis更多场景是作为Memcached的替代者来使用。  
3.当需要除key/value之外的更多数据类型支持时，使用Redis更合适。  
4.当存储的数据不能被剔除时，使用Redis更合适。  





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
  
  








