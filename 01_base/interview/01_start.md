
需看的地址：  
-----------
http://www.php.cn/php-weizijiaocheng-393701.html  
http://www.php.cn/php-weizijiaocheng-393700.html  
https://blog.csdn.net/Px01Ih8/article/details/80823381  
https://www.cnblogs.com/zypphp/p/8185155.html  
https://blog.csdn.net/dennis_ukagaka/article/details/76911655?ref=myread  




彻底理解session工作机制
-----------
详见：http://www.cnblogs.com/acpp/archive/2011/06/10/2077592.html  
```
1) session_start()
  A、 session_start()是session机制的开始，它有一定概率开启垃圾回收,因为session是存放在文件中，PHP自身的垃圾回收是无效的，SESSION的回收是要删文件的，这个概率是根据php.ini的配置决定的，但是有的系统是 session.gc_probability =0，这也就是说概率是0，而是通过cron脚本来实现垃圾回收。

  　　session.gc_probability =1
 　　 session.gc_divisor =1000
 　　 session.gc_maxlifetime =1440//过期时间 默认24分钟
 　　 //概率是 session.gc_probability/session.gc_divisor 结果 1/1000, 
 　　 //不建议设置过小，因为session的垃圾回收，是需要检查每个文件是否过期的。
  　　session.save_path =//好像不同的系统默认不一样，有一种设置是 "N;/path"
  　　//这是随机分级存储，这个样的话，垃圾回收将不起作用，需要自己写脚本

  B、 session会判断当前是否有$_COOKIE[session_name()];session_name()返回保存session_id的COOKIE键值，
      这个值可以从php.ini找到
      session.name = PHPSESSID //默认值PHPSESSID
             

  C、 如果不存在会生成一个session_id,然后把生成的session_id作为COOKIE的值传递到客户端.相当于执行了下面COOKIE 操作，注意的是，这一步执行了setcookie()操作，COOKIE是在header头中发送的，这之前是不能有输出的，PHP有另外一个函数 session_regenerate_id() 如果使用这个函数，这之前也是不能有输出的。

    setcookie(session_name(),
              session_id(),
              session.cookie_lifetime,//默认0
              session.cookie_path,//默认'/'当前程序跟目录下都有效
              session.cookie_domain,//默认为空
    )

  D、 如果存在那么session_id =$_COOKIE[session_name];然后去session.save_path指定的文件夹里去找名字为'SESS_'.session_id()的文件.读取文件的内容反序列化，然后放到$_SESSION中


2) 为$_SESSION赋值
　　　　比如新添加一个值$_SESSION['test'] ='blah'; 那么这个$_SESSION只会维护在内存中，当脚本执行结束的时候，
　　　　用把$_SESSION的值写入到session_id指定的文件夹中，然后关闭相关资源.      这个阶段有可能执行更改session_id的操作，
　　　　比如销毁一个旧的的session_id，生成一个全新的session_id.一半用在自定义 session操作，角色的转换上，
　　　　比如Drupal.Drupal的匿名用户有一个SESSION的，当它登录后需要换用新的session_id

  　　if (isset($_COOKIE[session_name()])) {
    　　setcookie(session_name(),'',time() -42000,'/');//旧session cookie过期
  　　}
  　　session_regenerate_id();//这一步会生成新的session_id
 　　//session_id()返回的是新的值

3) 写入SESSION操作
　在脚本结束的时候会执行SESSION写入操作，把$_SESSION中值写入到session_id命名的文件中，可能已经存在，
　　　　可能需要创建新的文件。


4) 销毁SESSION
　SESSION发出去的COOKIE一般属于即时COOKIE，保存在内存中，当浏览器关闭后，才会过期，假如需要人为强制过期，比如 退出登录，而不是关闭浏览器，那么就需要在代码里销毁SESSION，方法有很多，
    　　1. setcookie(session_name(),session_id(),time() -8000000,..);//退出登录前执行
    　　2. usset($_SESSION);//这会删除所有的$_SESSION数据，刷新后，有COOKIE传过来，但是没有数据。
    　　3. session_destroy();//这个作用更彻底，删除$_SESSION 删除session文件，和session_id

    当不关闭浏览器的情况下，再次刷新，2和3都会有COOKIE传过来，但是找不到数据
```



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
或
echo date('Y-m-d H:i:s',strtotime('-1 day',time()));
```
3.计算两个日期的时间差
```
$a='2013-12-31';
$b='2016-1-3';
echo floor((strtotime($b)-strtotime($a))/(24*60*60));
```
    
  
require与include 区别  
-----------
在php中 require和include 都是用来引入其它php文件的，后面可以带括号也可以省略。  
如：require("xxx.php"); 或者 require "xxxx.php";  

区别：     
require 这个函数通常放在 PHP 程序的最前面，PHP 程序在执行前，就会先读入 require 所指定引入的文件，使它变成当前PHP程序的一部份；   
include 一般放在程序的流程控制中，当程序执行时碰到才会引用，简化程序的执行流程。  

require 引入的文件有错误时，执行会中断，并返回一个致命错误；  
include 引入的文件有错误时，会继续执行，并返回一个警告。  

include 有返回值，而 require 没有。  
   
加_once后缀(如：require_once，include_once)表示已加载的不加载   
  

简述 php 中的 autoload
-----------
在 PHP 中使用类时，我们必须在使用前加载进来，不管是通过 require 的方式还是 include 的方式，但是会有两个问题影响我们做出加载的决定。首先是不知道这个类文件存放在什么地方，另外一个就是不知道什么时候需要用到这个文件。特别是项目文件特别多时，不可能每个文件都在开始的部分写很长一串的 require … 

Autoload 的加载机制，当通过 new 来实例化一个类时，PHP 会通过定义的 autoload 函数加载相应的文件，如果这个类文件使用了 extends 或者 implements 需要用到其他的类或接口文件，php会重新运行autoload去进行类文件的查找和加载，如果发生了两次对同一类文件的请求，就会报错。


静态变量及有什么优缺点？
------------
静态局部变量的特点：   
1.不会随着函数的调用和退出而发生变化，不过，尽管该变量还继续存在，但不能使用它。倘若再次调用定义它的函数时，它又可继续使用，而且保存了前次被调用后留下的值。

2.静态局部变量只会初始化一次。

3.静态属性只能被初始化为一个字符值或一个常量，不能使用表达式。即使局部静态变量定义时没有赋初值，系统会自动赋初值 0（对数值型变量）或空字符（对字符变量）；静态变量的初始值为 0。

4.当多次调用一个函数且要求在调用之间保留某些变量的值时，可考虑采用静态局部变量。虽然用全局变量也可以达到上述目的，但全局变量有时会造成意外的副作用，因此仍以采用局部静态变量为宜.


strtr 和 str_replace 有什么区别，两者分别用在什么场景下？
-------------
str_replace() 函数以其他字符替换字符串中的一些字符（区分大小写）  
strtr() 函数转换字符串中特定的字符。  

5.6版本 str_replace 比 strtr 效率高10+倍， 7.0 版本效率基本相同， 但 5.6 的 str_replace 竟比 7.0 高 3倍


魔术方法
-------------
__construct()：类的默认构造方法，如果 __construct() 和与类同名的方法共同出现时，默认调用__construct()而不是同类名方法。

__call()：当调用不存在或者不可访问的方法时，会调用 __call ( $name, $arguments )方法。

__toString()：当打印对象时会被直接调用。如 echo $object;

__clone()：当对象被拷贝时直接调用。

__isset()：对不存在或者不可访问的属性使用 isset() 或者 empty() 时，__isset() 会被调用；

__destruct()：类的析构函数，当该对象的所有引用都被删除，或者对象被显式销毁时执行。



如下所示，会输出什么结果？
-------------
```php
$array = [3,6,7,8]
foreach ($array as $key => $item) {
    $array[$key + 1] = $item + 2;
    echo "$item";
}
print_r($array);

结果示例：
3678 //echo 输出数组内元素的值
Array
(
    [0] => 3  //$key 保持不变
    [1] => 5 //每次的$key + 1, 对应的值加2，
    [2] => 8
    [3] => 9
    [4] => 10
)
```


递归的次数限制
-------------
递归（http:/en.wikipedia.org/wiki/R...）是一种函数调用自身（直接或间接）的一种机制，这种强大的思想可以把某些复杂的概念变得极为简单。  

逻辑上的递归可以无次数限制, 但语言执行器或者程序堆栈会限制递归的次数。  
php 手册注解：但是要避免递归函数／方法调用超过100-200层，因为可能会使堆栈崩溃从而使当前脚本终止。  
无限递归可视为编程错误。  

递归的两个基本条件：
1) 递归的退出条件，这是递归能够正常执行的必要条件，也是保证递归能够正确返回的必要条件。如果缺乏这个条件，递归就会无限进行下去，直到系统给予的资源耗尽。在大多数语言中，都是堆栈空间耗尽），因此，如果你在编程中碰到类似 “stack overflow” (C语言中，即栈溢出)和“max nest level of 100 reached”（php 中，超出递归限制）等错误，多半是没有正确的退出条件，导致了递归深度过大或者无限递归。

2) 递推过程。由一层函数调用进入下一层函数调用的递推。



单引号和双引号的区别
-----------
```
双引号内部变量会解析，单引号则不解析.  
双引号中特殊字符( \r\n 之类)会被转义，单引号中的内容不会被转义。  

执行效率：单引号串中的内容总被认为是普通字符，因此单引号中的内容不会被转义从而效率更高。  
```

for 和 foreach 的区别
------------
1、foreach 有的也叫增强 for 循环，foreach 其实是 for 循环的一个特殊简化版。  
2、foreach 适用于只是进行集合或数组遍历， for 则在较复杂的循环中效率更高。  
3、foreach 不能对数组或集合进行修改（添加删除操作），如果想要修改就要用 for 循环。  

所以相比较下来 for 循环更为灵活。



cookie与session区别与联系
-----------
区别：  
1、cookie数据存放在客户的浏览器上，session数据放在服务器上。  
2、cookie不是很安全，别人可以分析存放在本地的COOKIE并进行COOKIE欺骗
考虑到安全应当使用session。  
3、session会在一定时间内保存在服务器上。当服务器访问增多时，可能产生的SESSION 文件会比较多，这时可以设置分级目录进行SESSION文件的保存，效率会提高很多，设置方法为：session.save_path="N;/save_path"，N 为分级的级数，save_path 为开始目录。     
4、单个cookie保存的数据不能超过4K，很多浏览器都限制一个站点最多保存20个cookie。(Session 对象没有对存储的数据量的限制，其中可以保存更为复杂的数据类型)  
  
联系：  
session是通过cookie来工作的，Session数据保存在服务器端的文件（默认保存）或数据库中，客户端通过访问cookie ，而session和cookie之间是通过$_COOKIE['PHPSESSID']来联系的，通过$_COOKIE['PHPSESSID']可以知道session的id，之后通过它来获得服务端的数据。  
如果cookie被禁用，可以通过URL来传递session_id。    


session 锁
------------
php 的 session 默认用文件存储，当请求一个需要操作 session 的 php 文件(session_start())时，这个文件是会被第一个操作 session 的进程锁定，导致其他请求阻塞。其他请求会挂起在 session_start() 直到s ession文件解锁。这样保证了读取-写入，读取-写入的顺序。对数据流来说很理想，但是，对于目前这种页面大量应用ajax的情况，所有请求排队处理，将大大加大页面展现的耗时，甚至出现请求超时等不可用故障。

解决：由于锁定的 session 文件直到脚本执行结束或者 session 正常关闭才会解锁，为了防止大量的 php 请求（需要使用 $_SESSION 数据）被锁定，可以在写完 session 后马上关闭（使用session_write_close()），这样就释放了锁;

Memcache 或者 Redis 做 session 的存储，是能解决“锁定”的问题，但处理不好会导致连接数飙高（在 session 操作后如果有耗时操作，连接是不回收的，可以主动在 session 写操作完成后做 session_write_close() 操作）；



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


post 和 patch 的区别
-----------
POST/PUT方法，都可以用来创建或更新一个资源。
区别是细微但清楚的：

POST方法用来创建一个子资源，如 /api/users，会在users下面创建一个user，如users/1

POST方法不是幂等的，多次执行，将导致多条相同的用户被创建（users/1，users/2 ...而这些用户除了自增长id外有着相同的数据，除非你的系统实现了额外的数据唯一性检查）。


PUT方法用来创建一个URI已知的资源，或对已知资源进行完全替换，比如users/1。  
PUT方法一般会用来更新一个已知资源，除非在创建前，你完全知道自己要创建的对象的URI。  

PATCH方法是新引入的，是对PUT方法的补充，用来对已知资源进行局部更新。  



php 中 error_reporting 函数的作用
-----------
error_reporting() 设置 PHP 的报错级别并返回当前级别。



写出常用的 http 状态码及其作用
-----------
```
Name	Academy	score
200	OK	请求成功，服务器成功返回网页
301	Moved Permanently	永久跳转，请求的网页已永久跳转到新位置。
403	Forbidden	禁止访问,服务器拒绝请求
404	Not Found	服务器找不到请求的页面
500	Internal Server Error	服务器内部错误
502	Bad Gateway	坏的网关,一般是网关服务器请求后端服务时，后端服务没有按照 http 协议正确返回结果。
503	Service Unavailable	服务当前不可用，可能因为超载或停机维护。
504	Gateway Timeout	网关超时,一般是网关服务器请求后端服务时，后端服务没有在特定的时间内完成服务。
```
  

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

mvc 的理解
----------
MVC 是模型( model ) －视图( view )－控制器( controller )的缩写，一种软件设计典范，用一种业务逻辑、数据、界面显示分离的方法组织代码，将业务逻辑聚集到一个部件里面，在改进和个性化定制界面及用户交互的同时，不需要重新编写业务逻辑。


面向对象是什么？
---------- 
面向对象程序设计（Object-Oriented Programmming, OOP）：是一种程序设计范型，同时也是一种程序开发方法。它将对象作为程序的基本单元，将程序和数据封装其中，以提高软件的重用性、灵活性和可扩展性。  


类和对象的区别及关系
----------
类是定义一系列属性和操作的模板，而对象则把属性进行具体化，然后交给类处理。  

对象就是数据，对象本身不包含方法。但是对象有一个“指针”指向一个类，这个类里可以有方法。

类和对象是不可分割的，有对象就必定有一个类和其对应，否则这个对象也就成了没有亲人的孩子（有一个特殊情况存在，就是由标量进行强制类型转换的 object，没有一个类和它对象。此时，PHP 中的一个称为“孤儿”的 stdClass 类就会收留这个对象）。  


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




写一个验证电子邮箱格式是否正确的函数
--------------
```
function check_email($email){

    $preg = "/^\w+([-_.]\w+)*@\w+([-_.]\w+)*(\.\w+){0,3}$/i";
    
    $res = preg_match($preg,$email);
    
    return $res;//匹配成功返回1，匹配失败返回0
}
```


AES 和 RSA 的区别：
---------------
RSA 是非对称加密，公钥加密，私钥解密， 反之亦然。缺点：运行速度慢，不易于硬件实现。常私钥长度有512bit，1024bit，2048bit，4096bit，长度越长，越安全，但是生成密钥越慢，加解密也越耗时。

AES 对称加密，密钥最长只有256个bit，执行速度快，易于硬件实现。由于是对称加密，密钥需要在传输前通讯双方获知。

AES加密数据块分组长度必须为128比特，密钥长度可以是128比特、192比特、256比特中的任意一个（如果数据块及密钥 长度不足时，会补齐）

总结：采用非对称加密算法管理对称算法的密钥，然后用对称加密算法加密数据，这样我们就集成了两类加密算法的优点，既实现了加密速度快的优点，又实现了安全方便管理密钥的优点。  



XSS、CSRF、SSRF、SQL 注入原理
-------------
XSS：跨站脚本（Cross-site scripting，通常简称为 XSS）是一种网站应用程序的安全漏洞攻击，是代码注入的一种。它允许恶意用户将代码注入到网页上，其他用户在观看网页时就会受到影响。这类攻击通常包含了 HTML 以及用户端脚本语言。防御：页面上直接输出的所有不确定(用户输入)内容都进行 html 转义；对用户输入内容格式做校验；script 脚本中不要使用不确定的内容；

CSRF:跨站请求伪造（英语：Cross-site request forgery），也被称为 one-click attack 或者 session riding，通常缩写为 CSRF 或者 XSRF， 是一种挟制用户在当前已登录的 Web 应用程序上执行非本意的操作的攻击方法;防御：验证 HTTP Referer 字段；在请求地址中（或 HTTP 头中）添加 token 并验证；

SSRF：模拟服务器对其他服务器资源进行请求，没有做合法性验证。构造恶意内网IP做探测，或者使用其余所支持的协议对其余服务进行攻击。防御：禁止跳转，限制协议，内外网限制，URL 限制。绕过：使用不同协议，针对IP，IP 格式的绕过，针对 URL，恶意 URL 增添其他字符，@之类的。301跳转 + dns rebindding。

SQL注入：就是通过把 SQL 命令插入到Web表单提交或输入域名或页面请求的查询字符串，最终达到欺骗服务器执行恶意的 SQL 命令。防御：过滤特殊符号特殊符号过滤或转义处理（addslashes函数）；绑定变量，使用预编译语句；










