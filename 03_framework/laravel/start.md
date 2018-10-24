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
  


laravel目录结构
==========
```
app
bootsrap
config
database
public
Resources
Routes
Storage
tests
vender
```

App目录
----------
app 目录包含了应用的核心代码，注意不是框架的核心代码，框架的核心代码在 /vendor/laravel/framework 里面，此外你为应用编写的代码绝大多数也会放到这里，当然，如果你基于 Composer 做了 PHP 组件化开发的话，这里面存放的恐怕也只有一些入口性的代码了；


Bootstrap目录
----------
bootstrap 目录包含了少许文件，用于框架的启动和自动载入配置，还有一个 cache 文件夹，里面包含了框架为提升性能所生成的文件，如路由和服务缓存文件；


Config目录
----------
config 目录包含了应用所有的配置文件，建议通读一遍这些配置文件以便熟悉 Laravel 所有默认配置项；


Database目录
----------
database 目录包含了数据库迁移文件及填充文件，如果有使用 SQLite 的话，你还可以将其作为 SQLite 数据库存放目录；


Public目录
----------
public 目录包含了应用入口文件 index.php 和前端资源文件（图片、JavaScript、CSS等），该目录也是 Apache 或 Nginx 等 Web 服务器所指向的应用根目录，这样做的好处是隔离了应用核心文件直接暴露于 Web 根目录之下，如果权限系统没做好或服务器配置有漏洞的话，很可能导致应用敏感文件被黑客窃取，进而对网站安全造成威胁；


Resources目录
----------
resources 目录包含了应用视图文件和未编译的原生前端资源文件（LESS、SASS、JavaScript），以及本地化语言文件；


Routes目录
----------
routes 目录包含了应用定义的所有路由。Laravel 默认提供了四个路由文件用于给不同的入口使用：web.php、api.php、 console.php 和 channels.php。

web.php 文件包含的路由都位于 RouteServiceProvider 所定义的 web 中间件组约束之内，因而支持 Session、CSRF 保护以及 Cookie 加密功能，如果应用无需提供无状态的、RESTful 风格的 API，那么路由基本上都要定义在 web.php 文件中。

api.php 文件包含的路由位于 api 中间件组约束之内，支持频率限制功能，这些路由是无状态的，所以请求通过这些路由进入应用需要通过 token 进行认证并且不能访问 Session 状态。

console.php 文件用于定义所有基于闭包的控制台命令，每个闭包都被绑定到一个控制台命令并且允许与命令行 IO 方法进行交互，尽管这个文件并不定义 HTTP 路由，但是它定义了基于控制台的应用入口（路由）。

channels 文件用于注册应用支持的所有事件广播频道。


Storage目录
----------
storage 目录包含了编译后的 Blade 模板、基于文件的 Session、文件缓存，以及其它由框架生成的文件，该目录被细分为成 app、framework 和 logs 子目录，app 目录用于存放应用生成的文件，framework 目录用于存放框架生成的文件和缓存，最后，logs 目录存放的是应用的日志文件。

storage/app/public 目录用于存储用户生成的文件，比如可以被公开访问的用户头像，要达到被 Web 用户访问的目的，你还需要在 public （应用根目录下的 public 目录）目录下生成一个软连接 storage 指向这个目录。你可以通过 php artisan storage:link 命令生成这个软链接。


Tests目录
----------
tests 目录包含自动化测试文件，其中默认已经提供了一个开箱即用的PHPUnit 示例；每一个测试类都要以 Test 开头，你可以通过 phpunit 或 php vendor/bin/phpunit 命令来运行测试。


Vendor目录
----------
vendor 目录包含了应用所有通过 Composer 加载的依赖。



