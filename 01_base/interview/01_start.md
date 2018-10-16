
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

总结：  
echo与print是PHP语句没有返回值，print_r是PHP函数有返回值  
echo 可以打印1个或多个变量  
print() 只能打印一个变量  
print_r() 可以打印复合型 数组，对象等  
  


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
  

时间
-----------
1.获取当前时间   
```
date_default_timezone_set('PRC');  
echo date('Y-m-d H:i:s');  
```

2.获取前一天此时时间  
```
date_default_timezone_set('PRC);
echo date('Y-m-d H:i:s',strtotime('-1 day'));
```
3.计算两个日期的时间差
```
$a='2013-12-31';
$b='2016-1-3';
echo floor((strtotime($b)-strtotime($a))/(24*60*60));
```
    
  
require与include 区别  
-----------
在php中 require和include 都是用来引用其它php文件的，后面可以带括号也可以省略。  
如：require("xxx.php"); 或者 require "xxxx.php";  
   
require 一般放在 PHP 文件的最前面，程序在执行前就会先导入要引用的文件；  
include 一般放在程序的流程控制中，当程序执行时碰到才会引用，简化程序的执行流程。  
require 引入的文件有错误时，执行会中断，并返回一个致命错误；  
include 引入的文件有错误时，会继续执行，并返回一个警告。  
   
加_once后缀(如：require_once，include_once)表示已加载的不加载   
  


cookie与session区别与联系
-----------
区别：  
1、cookie数据存放在客户的浏览器上，session数据放在服务器上。  
2、cookie不是很安全，别人可以分析存放在本地的COOKIE并进行COOKIE欺骗
考虑到安全应当使用session。  
3、session会在一定时间内保存在服务器上。当服务器访问增多时，可能产生的SESSION 文件会比较多，这时可以设置分级目录进行SESSION文件的保存，效率会提高很多，设置方法为：session.save_path="N;/save_path"，N 为分级的级数，save_path 为开始目录。   
4、单个cookie保存的数据不能超过4K，很多浏览器都限制一个站点最多保存20个cookie。
  
联系：  
session是通过cookie来工作的，Session数据保存在服务器端的文件（默认保存）或数据库中，客户端通过访问cookie ，而session和cookie之间是通过$_COOKIE['PHPSESSID']来联系的，通过$_COOKIE['PHPSESSID']可以知道session的id，之后通过它来获得服务端的数据。  
如果cookie被禁用，可以通过URL来传递session_id。    



post与get区别
-----------
GET(默认)  

1、GET请求会直接将数据直接附加在URL之后，用?分割URL和传输数据，用&来分割多个参数  
2、GET请求可以被缓存，可被保留至浏览器历史纪录中，可被设置被书签  
3、GET请求有长度的限制，最多只能传递1024个字符  
4、因为URL只支持ASCII编码格式，所以GET请求中的所有非ASCII数据都要被浏览器编码后再传输  
5、一般被用来做查询数据的操作  

POST  

1、POST请求的数据会被放置在HTTP请求包的body中，所以安全性强于GET  
2、POST请求的数据不会被浏览器缓存、记录，也无法设置为书签  
3、POST请求没有长度的限制  
4、一般被用来做敏感数据的传输以及数据的更新操作  
  

php文件上传的原理及如何限制文件大小、类型？
-----------
1）文件上传原理  
```
将客户端的文件上传到服务器，再将服务器的临时文件上传到指定目录
客户端的文件，通过表单post到服务器，先存在临时目录中，然后通过代码把他移到指定的目录才能永久保存  
```

2）客户端配置
```
提交表单
表单的发送方式为post
添加enctype="multipart/form-data"
```

3）服务器端配置
```
file_uploads = On，支持HTTP上传
uoload_tmp_dir = ，临时文件保存目录
upload_max_filesize = 2M，允许上传文件的最大值
max_file_uploads = 20 ，允许一次上传到的最大文件数
post_max_size = 8M，post方式发送数据的最大值


一般地，设置好上述四个参数后，在网络正常的情况下，上传<=8M的文件是不成问题
但如果要上传>8M的大体积文件，只设置上述四项还一定能行的通。除非你的网络真有100M/S的上传高速，否则你还得继续设置下面的参数:

max_execution_time = -1，设置了脚本被解析器终止之前允许的最大执行时间，单位为秒，防止程序写的不好而占尽服务器资源。-1代表无穷
max_input_time = 60 ，脚本解析输入数据允许的最大时间，单位为秒
max_input_nesting_level = 64 ，设置输入变量的嵌套深度
max_input_vars_ = 1000，接受多少输入的变量（限制分别应用于$_GET、$_POST和$_COOKIE超全局变量，将会导致E_WARNING的产生，更多的输入变量将会从请求中截断。
memory_limit = 128M，最大单线程的独立内存使用量。也就是一个web请求，给予线程最大的内存使用量的定义
```

4）错误信息说明
```
UPLOAD_ERR_OK：其值为0，没有错误发生，文件上传成功
UPLOAD_ERR_INI_SIZE：其值为1，上传的文件超过了php.ini中upload_max_filesize选项限制的值
UPLOAD_ERR_FORM_SIZE：其值为2，上传文件的大小超过了HTML表单中MAX_FILE_SIZE选项指定的值
UPLOAD_ERR_PARTIAL：其值为3，文件只有部分被上传
UPLOAD_ERR_NO_FILE：其值为4，没有文件被上传
UPLOAD_ERR_NO_TMP_DIR：其值为6，找不到临时文件夹
UPLOAD_ERR_CANT_WRITE：其值为7，文件写入失败
UPLOAD_ERR_EXTENSION：其值为8，上传的文件被PHP扩展程序中断
```

限制上传文件类型
```
在前端可以<input type="file" accept="image/png,image/gif" /> 用accept属性限制，或者通过js监听file input的change事件来限制。

在后端不要简单的用文件后缀来判断，要通过$_FILES['xxx']['type']来判断。
```


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
与 session.save_path，如果session.save_handler=file，则session存储在session.save_path指定的文件中。
如果session.save_handler=user，则存储在用户自定义位置，这时我们通过查看项目代码中的session_set_save_handler函数，来确定session存储在哪里。
  
  
你所知道的缓存技术有哪些，分别做下简单介绍  
------------
浏览缓存

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


http header大小，body大小
-------------
均可在服务端控制  
比如nginx的：  
large_client_header_buffers 这个属性，控制header大小，它默认是4k     
client_max_body_size，这个参数可以限制body的大小，默认是1m    


http缓存机制
------------
本地缓存（强制缓存）  
	expires  
	cache_control  

协商缓存（对比缓存）304    
	一种：    
	Last-Modified：服务器通知浏览器资源的最后修改时间    
	If-Modified-Since：浏览器会将得到资源的最后修改时间通过If-Modified-Since提交到服务器做检查，如果没有修改，返回304状态码    
	   
	另一种：  
	ETag：http1.1推出，文件的指纹标识符，如果文件内容修改，指纹会改变  
	If-None-Match：本地缓存失效，会携带此值去请求服务端，服务端判断该资源是否改变，如果没有改变，直接使用本地缓存，返回304 





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


一个有几千万粉丝的大v, 发一条消息, 如何让这么多的粉丝看到
----------
把消息优先推送给在线人, 其他人等上线之后再推. 如果一下子推给所有粉丝, 那是吃不消的, 而且有很多僵尸粉, 不合理。    



同源策略、跨域解决方案
-----------
1、先来说说什么是源  
源（origin）就是协议、域名和端口号。  
比如一个url中的源就是：http://www.company.com:80  

若地址里面的协议、域名和端口号均相同则属于同源。  
以下是相对于 http://www.a.com/test/index.html 的同源检测
```
•	http://www.a.com/dir/page.html ----成功
•	http://www.child.a.com/test/index.html ----失败，域名不同
•	https://www.a.com/test/index.html ----失败，协议不同
•	http://www.a.com:8080/test/index.html ----失败，端口号不同
```
  
2.什么是同源策略？  
同源策略是浏览器的一个安全功能，不同源的客户端脚本在没有明确授权的情况下，不能读写对方资源。所以a.com下的js脚本采用ajax读取b.com里面的文件数据是会报错的。  

不受同源策略限制的：  
1、页面中的链接，重定向以及表单提交是不会受到同源策略限制的。  
2、跨域资源的引入是可以的。但是js不能读写加载的内容。如嵌入到页面中的<script src="..."></script>，<img>，<link>，<iframe>等。  
  
二、跨域  
1、什么是跨域  
受前面所讲的浏览器同源策略的影响，不是同源的脚本不能操作其他源下面的对象。想要操作另一个源下的对象是就需要跨域。    

2、跨域的实现方式    
  1) JSONP跨域  
JSONP和JSON并没有什么关系！  
JSONP的原理：  

  2) CORS  
CORS是一个W3C标准，全称是"跨域资源共享"（Cross-origin resource sharing）。  
它允许浏览器向跨源服务器，发出XMLHttpRequest请求，从而克服了AJAX只能同源使用的限制。  
在访问的服务器响应头中加上:  
Access-Control-Allow-Origin: http://xxxxx.com   
  
  3) 降域 document.domain  
同源策略认为域和子域属于不同的域，如：  
child1.a.com 与 a.com，  
child1.a.com 与 child2.a.com，  
xxx.child1.a.com 与 child1.a.com  
两两不同源，可以通过设置 document.damain='a.com'，浏览器就会认为它们都是同一个源。  
降域的特点：
1.	只能在父域名与子域名之间使用，且将 xxx.child1.a.com域名设置为a.com后，不能再设置成child1.a.com。
2.	存在安全性问题，当一个站点被攻击后，另一个站点会引起安全漏洞。
3.	这种方法只适用于 Cookie 和 iframe 窗口。










  








