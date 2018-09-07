
需看的地址：  
-----------
http://www.php.cn/php-weizijiaocheng-393701.html  
http://www.php.cn/php-weizijiaocheng-393700.html  
https://blog.csdn.net/Px01Ih8/article/details/80823381  
https://www.cnblogs.com/zypphp/p/8185155.html  
https://blog.csdn.net/dennis_ukagaka/article/details/76911655?ref=myread  


php方面的：
-----------
  
echo print print_r 区别  
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
  

isset() 和 empty() 区别
-----------
isset判断变量是否存在，可以传入多个变量，若其中一个变量不存在则返回假  
empty判断变量是否为空为假，只可传一个变量，如果为空为假则返回真。  
   
  
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
  

接口与类有什么区别
-----------  


  
  
  

memcache、redis 区别，及应用场景  
-----------


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




mysql方面的：
-----------

请说出mysql常用存储引擎？memory存储引擎的特点？  
-----------
Myisam、InnoDB、memory  
memory的特点是将表存到内存中，数度快，重启后数据丢失   
  
索引算法Hash与BTree的区别
-----------
https://blog.csdn.net/u011305680/article/details/55520853  






nosql方面的：
-----------





linux：
-----------







