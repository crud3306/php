地址：
----------
https://laravel-china.org/docs/laravel/5.6 (中文)  
https://docs.golaravel.com/docs/5.6/installation/ （英文）  


创建laravel项目
---------
1) 通过 Laravel 安装器

首先，通过 Composer 安装 Laravel 安装器：  
> composer global require "laravel/installer"  
我们安装的内容在~/.composer目录下，需要在$PATH下配置一下：  
> vi ~/.bash_profile  
> export PATH=$PATH:~/.composer/vendor/bin  
> source ~/.bash_profile  
  
这时候我们就可以使用laravel命令创建项目了，创建的项目包含所有 Laravel 依赖。  
laravel new 项目名  
例：  
> laravel new blog  
注：此种不能指定项目用指定版本的laravel，默认用的最新的。最新的laravel要求php要7.0以上。   
  
如果之前已经安装过旧版本的 Laravel 安装器，通过上面命令安装发现不是最新版的laravel，则需要更新：  
> composer global update    
更新后再重新创建laravel项目  
  
  
2） 通过 Composer Create-Project  
在终端中通过 Composer 的 create-project 命令来安装 Laravel 应用：  
> composer create-project --prefer-dist laravel/laravel blog  
   
如果要下载安装 Laravel 其他版本应用，比如 5.5 版本，可以使用这个命令：  
> composer create-project --prefer-dist laravel/laravel blog 5.5.*   
注意：laravel5.5需要php5.6.4+，laravel5.5需要php7.0+，laravel5.6需要php7.1.3+，否则不能安装。

  
本地开发服务器  
--------- 
如果你在本地安装了 PHP，并且想要使用 PHP 内置的开发环境服务器为应用提供服务，可以使用 Artisan 命令  serve：  
> php artisan serve  
该命令将会在本地启动开发环境服务器，这样在浏览器中通过 http://localhost:8000 即可访问应用  
  
  
  
配置 Laravel  
---------
公共目录  
安装完 Laravel 后，需要将 Web 服务器的 document/web 根目录指向 Laravel 应用的 public 目录，该目录下的 index.php 文件作为前端控制器（单一入口），所有 HTTP 请求都会通过该文件进入应用。  
  
配置文件  
Laravel框架的所有配置文件都存放在config目录下，所有的配置项都有注释，所以你可以轻松遍览这些配置文件以便熟悉所有配置项。  
  
目录权限  
安装完 Laravel 后，需要配置一些目录的读写权限：storage 和 bootstrap/cache 目录对 Web 服务器指定的用户而言应该是可写的，否则 Laravel 应用将不能正常运行。如果你使用 Homestead 虚拟机做为开发环境，这些权限已经设置好了。  
  
应用key  
接下来要做的事情就是将应用的 key（APP_KEY）设置为一个随机字符串，如果你是通过 Composer 或者 Laravel 安装器安装的话，该 key 的值已经通过 php artisan key:generate 命令生成好了。  
通常，该字符串应该是 32 位长，通过 .env 文件中的 APP_KEY 进行配置，如果你还没有将 .env.example 文件重命名为 .env，现在立即这样做。如果应用 key 没有被设置，用户 Session 和其它加密数据将会有安全隐患!  
   
更多配置  
Laravel 几乎不再需要其它任何配置就可以正常使用了，不过，你最好再看看 config/app.php 文件，其中包含了一些基于应用可能需要进行改变的配置，比如 timezone 和 locale（分别用于配置时区和本地化）。

Web 服务器配置
Nginx
如果你使用的是 Nginx，在对应的server中添加：
```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```
  


