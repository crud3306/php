  
php 常见错误
------------
notice  
warning  
fatal error  
  
notice与warning的后续代码仍会执行，且可以用@符屏蔽错误  
fatal error 后续代码不会执行，不可以用@符屏蔽错误  



php错误日志在哪看，可以去php配置文件中查找
--------------
php.ini  （php运行核心配置文件）  
php-fpm.conf  （是 php-fpm 进程服务的配置文件）  
php-fpm.d/www.conf  （进程服务的扩展配置文件）  
  

注：php.ini配置文件在哪里？
--------------
通常php.ini的位置在：  
/etc目录下  
或  
/usr/local/lib目录下。   
  
如果你还是找不到php.ini或者找到了php.ini修改后不生效(其实是没找对)，请使用如下办法：  

新建文件php_test.php
```php
<?php
echo phpinfo();
```
执行/usr/local/php php_test.php  
然后在显示结果中查找：Configuration File  


display_errors
--------------
错误回显，一般常用语开发模式，但是很多应用在正式环境中也忘记了关闭此选项。错误回显可以暴露出非常多的敏感信息，为攻击者下一步攻击提供便利。推荐关闭此选项。  

display_errors = On  
开启状态下，若出现错误，则报错，出现错误提示  
  
dispaly_errors = Off  
关闭状态下，若出现错误，则提示：服务器错误。但是不会出现错误提示  

log_errors  
在正式环境下用这个就行了，把错误信息记录在日志里。正好可以关闭错误回显。  

对于PHP开发人员来说，一旦某个产品投入使用，那么第一件事就是应该将display_errors选项关闭，以免因为这些错误所透露的路径、数据库连接、数据表等信息而遭到黑客攻击。  

某个产品投入使用后，难免会有错误信息，那么如何记录这些对开发人员非常有用的信息呢？
将PHP的log_errors开启即可，默认是记录到WEB服务器的日志文件里，比如Apache的error.log文件。  

当然也可以记录错误日志到指定的文件中。  

配置php错误日志  
-----------------
1.修改php-fpm.conf中配置 没有则增加  
> catch_workers_output = yes  
> error_log = log/error_log  

2.修改php.ini中配置，没有则增加  
> display_errors = Off  
> log_errors = On  
> error_log = "/usr/local/php/var/log/error_log"  
> error_reporting = E_ALL&~E_NOTICE  
  
注意：以上设置需配合error_reporting的设置，error_reporting设置错误回报级别  
如果error_reporting=0，不回报任何错误，上面error_log不会记录错误。  




php.ini中display_errors = Off 失效的解决  
问题：  
PHP设置文件php.ini中明明已经设置display_errors = Off，但是在运行过程中，网页上还是会出现错误信息。  
------------------
解决：  
经查log_errors = On，据官方的说法，当这个log_errors设置为On，那么必须指定error_log文件，如果没指定或者指定的文件没有权限写入，那么照样会输出到正常的输出渠道，那么也就使得display_errors 这个指定的Off失效，错误信息还是打印了出来。所以请按上面检查即可。  



错误级别：error_reporting 设定错误讯息回报的等级
------------------
错误报告是位字段。可以将数字加起来得到想要的错误报告等级。  

error_reporting可以设置的参数，如下：  
```
value constant
1 E_ERROR
2 E_WARNING
4 E_PARSE
8 E_NOTICE
16 E_CORE_ERROR
32 E_CORE_WARNING
64 E_COMPILE_ERROR
128 E_COMPILE_WARNING
256 E_USER_ERROR
512 E_USER_WARNING
1024 E_USER_NOTICE
2047 E_ALL
2048 E_STRICT
```


设置错误报告级别的方法：
==============
1）修改PHP的配置文件php.ini
--------------
这种方式设置error_reporting后，重启web服务器，就会永久生效。  

打开配置文件php.ini，查看错误报告级别error_reporting的默认值，如下：  

error_reporting = E_COMPILE_ERROR|E_ERROR|E_CORE_ERROR ; 仅显示编译时致命性错误  
error_reporting = E_ERROR :只会报告致命性错误  

error_reporting=E_ALL & ~E_DEPRECATED & ~E_STRICT  
意思是报告所有的错误，但除了E_DEPRECATED和E_STRICT这两种。  

error_reporting=E_ALL & ~E_NOTICE  
意思是报告所有的错误，但除了E_NOTICE这一种。这也是最常用的错误报告级别，它不会报告注意类（如：使用了未定义的变量）的错误。  

保存，重启web服务器后生效。  


2）使用error_reporting()函数
--------------
这种方式设置后，可以立即生效。但仅限于在当前脚本中的error_reporting()函数调用的后面区域。  

int error_reporting ([ int $level ] )  
参数可以是整型或对应的常量标识符，推荐使用常量的形式。返回值为当前位置处起作用的错误报告级别的值（整型值）。  

error_reporting(E_ALL ^ E_NOTICE); // 除了E_NOTICE之外，报告所有的错误  
error_reporting(E_ERROR); // 只报告致命错误  
echo error_reporting(E_ERROR | E_WARNING | E_NOTICE); // 只报告E_ERROR、E_WARNING 和 E_NOTICE三种错误  

注意：配置文件php.ini中display_errors的默认值为On，代表显示错误提示，如果设置为Off，就会关闭所有的错误提示。  

```php
<?php
// 禁用错误报告
error_reporting(0);

error_reporting(7);
// 注：7=1+2+4
//就是出错时显示，注：1 E_ERROR 2 E_WARNING 4 E_PARSE

// 报告运行时错误
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// 报告所有错误
error_reporting(E_ALL);
```




