 
文档地址：
-----------
https://docs.phpcomposer.com/00-intro.html  
  
    
下载安装composer.phar  
-------------  
> curl -s http://getcomposer.org/installer | php  
> #curl -sS https://getcomposer.org/installer | php  
  
把composer.phar移动到环境下让其变成可执行  
> mv composer.phar /usr/local/bin/composer  
  
# 测试  
> composer -V  
# 输出：Composer version 1.0-dev (e64470c987fdd6bff03b85eed823eb4b865a4152) 2015-05-28 14:52:12  
  
  
  
查看composer远程镜像
-------------
> composer config -gl  
  
更改为国内的，因外部的方问速度太慢  
> composer config -g repo.packagist composer https://packagist.phpcomposer.com  
> //composer config -g repo.packagist composer https://pkg.phpcomposer.com  
  
注：使用composer来安装依赖，需要当前目录下有composer.json文件。  
可以通过composer init 命令来自动生成composer.json，执行后，按提示操作来走即可。  
  
  
查找某依赖的完整包名  
> composer search monolog  
  
查看依赖拥有哪些版本  
> composer show --all monolog/monolog  
  
安装依赖  
> composer require monolog/monolog  
  
  
如果版本过旧，想升级最新的，执行如下命令  
> composer selfupdate  
  
  
composer使用手册  
https://pkg.phpcomposer.com/#how-to-install-composer  
http://docs.phpcomposer.com/00-intro.html#Dependency-management  
  
  
Composer.json说明  
http://blog.csdn.net/hel12he/article/details/46503875  
  
  
常见错误分析：  
-------------------------------
1 执行composer init时报错  
> [Symfony\Component\Process\Exception\RuntimeException]  
> The Process class relies on proc_open, which is not available on your PHP installation.  
原因： 
是php禁用了proc_open，开启这些函数：  
proc_open,proc_close,proc_nice,proc_terminate,leak,proc_get_status  
同时也要开启 putenv  
  
  
2 ...  




