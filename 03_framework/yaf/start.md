地址：
----------
http://www.laruence.com/manual/  (yaf手册)  

  
  
一、Linux系统下安装YAF框架  
-----------
YAF作为一个PHP扩展，与安装其他PHP扩展并没有任何的区别  
   
环境：(测试环境)   
系统：Linux CentOS 6.5  
PHP:php5.4  
  
安装步骤：  
// 下载最新稳定版源码包->官方下载地址：http://pecl.php.net/package/yaf  
> wget http://pecl.php.net/get/yaf-2.3.5.tgz  
> tar -zxvf yaf-2.3.5.tgz  
// 生成配置  
> cd yaf-2.3.5  
> /usr/local/php/bin/phpize  
// 配置  
> ./configure --with-php-config = /usr/local/php/bin/php-config  
// 编译并安装  
> make && make install  
// 修改配置文件php.ini 添加下面这一行  
> extension = yaf.so  
// 重启PHP(如果是nginx重启php-fpm，apache重启apache)  
  

二、检查yaf是否安装成功  
-----------
// 在web目录下建立一个phpinfo.php的文件，打印phpinfo信息  
<?php  
phpinfo();  
在浏览器中访问该文件，看是否有yaf的扩展信息   
  
  
  
三、建立应用目录结构  
-----------
1、通过脚本生成应用目录结构(推荐)  
// 下载工具包(在YAF源码中)：https://github.com/laruence/yaf  
> wget https://github.com/laruence/yaf/archive/master.zip  
// 解压工具包，进入工具包目录运行工具  
> unzip master.zip  
> cd tools  
> cd cg  
> /usr/local/php/bin/php yaf_cg appname //这里的appname换成你的项目目录名称  
> cd output  
> cp -R appname /home/www/appname //将生成的项目复制到你的www目录下  
  
// 在浏览器中访问项目，如：http://localhost/appname/，如果正常则出现“Hello World! I am Stranger”就表示成功了  
  


2、手动建立应用目录结构：  
  
第一步：手动建立目录  
+ public    
  |- index.php //入口文件  
  |- .htaccess //重写规则  
  |+ css   
  |+ img   
  |+ js  
+ conf  
  |- application.ini //配置文件  
+ application  
  |+ controllers  
    |- Index.php //默认控制器  
  |+ views  
    |+ index //控制器  
      |- index.phtml //默认视图  
  |+ modules //其他模块  
  |+ library //本地类库,  
  |+ models //model目录  
  |+ plugins //插件目录  
  
  
第二步：建立配置文件 conf/application.ini，它只有一个必要配置即application.directory(项目应用程序目录)  
注：配置中可使用定义的常量(这些常量一般在加载配置文件前定义的，在入口文件index.php或者启动文件Bootstrap.php中)  
> [common]  
> application.directory = APP_PATH "/application"  
> [product : common]  表示product环境可继承common配置  
  
  
第三步：建立入口文件public/index.php  
```php
<?php
define('APP_PATH', dirname(__DIR__));
$app = new Yaf_Application(APP_PATH.'/conf/application.ini');
$app->run();
```
  
第四步：建立默认控制器文件application/controllers/Index.php  
```php
<?php
class IndexController extends Yaf_Controller_Abstract
{
	public function indexAction()
	{
		$this->getView()->assign("content", "hello world!");
	}
}
```

第五步：建立默认视图文件application/views/index/index.phtml
```html
<!DOCTYPE html>
<html>
<head>
	<title>hello</title>
</head>
<body>
	<?php echo $content;?>
</body>
</html>
```
  
  
四、入口文件内容分析：  
-----------
```php
<?php
//指向public的上一级
define("APP_PATH", realpath(dirname(__FILE__) . '/../'));

//加载框架的配置文件
$app = new Yaf\Application(APP_PATH . "/conf/application.ini");

//加载bootstrap配置内容启动  
$app->bootstrap()->run();  
```
  
  
五、application.ini配置文件默认内容：  
-----------
此文件用于定义自己的常量，具体使用接下来用到会进行说明。  
```shell
[yaf]
;APP_PATH is the constant defined in index.php
application.directory=APP_PATH "/app"
application.ext="php"
application.view.ext="phtml"
application.modules="Index,Admin"
application.library=APP_PATH "/lib"
application.library.directory=APP_PATH "/lib"
application.library.namespace=""
application.bootstrap=APP_PATH "/app" "/Bootstrap.php"
application.baseUri=""
application.dispatcher.defaultRoute=""
application.dispatcher.throwException=1
application.dispatcher.catchException=1
application.dispatcher.defaultModule="index"
application.dispatcher.defaultController="index"
application.dispatcher.defaultAction="index"
;custom settings
application.layout.directory=APP_PATH "/app" "/views" "/layouts"
application.protect_from_csrf=1
application.encoding=UTF-8
;product section inherit from yaf section
[product:yaf]
; user configuartions list here
database.mysql.host=localhost
database.mysql.port=3306
database.mysql.user=
database.mysql.password=
database.mysql.database=
database.mysql.charset=utf8
```
  
  
六、bootstrap文件内容：  
========================
实际的初始化方法按照自己的实际需要进行添加：
```php
<?php
use Yaf\Bootstrap_Abstract;
use Yaf\Dispatcher;
/**
* 所有在Bootstrap类中, 以_init开头的方法, 都会被Yaf调用,
* 这些方法, 都接受一个参数:Yaf_Dispatcher $dispatcher
* 调用的次序, 和申明的次序相同
*/

class Bootstrap extends Bootstrap_Abstract {

	//加载应用初始化配置
	public function _initConfig() {
		$config = Yaf\Application::app()->getConfig();
		Yaf\Registry::set("config", $config);
	}

	//定义应用默认模块和默认的控制器及方法
	public function _initDefaultName(Dispatcher $dispatcher) {
		$dispatcher->setDefaultModule("Index")->setDefaultController("index")->setDefaultAction("index");
	}

	//初始化应用的总的路由配置
	public function _initRoute(Dispatcher $dispatcher)
	{
		$config = new Yaf\Config\Ini(APP_PATH . '/conf/routing.ini');
		$dispatcher->getRouter()->addConfig($config);
	}

	//初始化模块自己专属的配置
	public function _initModules(Yaf\Dispatcher $dispatcher)
	{
		$app = $dispatcher->getApplication();

		$modules = $app->getModules();
		foreach ($modules as $module) {
			if ('index' == strtolower($module)) continue;

			require_once $app->getAppDirectory() . "/modules" . "/$module" . "/_init.php";
		}
	}
}
```
相应的，模块新增方法如下：  
//往conf/application.ini文件中加入下列代码  
> application.modules="index,admin,test"  
  
这里表明应用采用index模块，admin模块和test模块。相应的，需要添加对应的目录如下：
```txt
+ public
 |- index.php //入口文件
 |- .htaccess //重写规则
 |+ css
 |+ img
 |+ js

+ conf
 |- application.ini //配置文件

+ application
 |+ controllers
   |+Backend
     |-Index.php //控制器文件
   |- Index.php //默认控制器

 |+ views
   |+ index //控制器
     |- index.phtml //默认视图

 |+ modules //其他模块
   |+Admin
     |+config
       |-routes.ini //模块路由规则
     |+controller //模块控制器文件目录
       |-Index.php
       |-Test.php
     |+views //模块视图文件目录
     |-_init.php //加载模块的路由规则
  |+Test
    |+config
      |-routes.ini
    |+controller
    |+views
    |-_init.php

 |+ library //本地类库
 |+ models //model目录
 |+ plugins //插件目录
```
  
七、路由配置：  
========================
配置默认模块路由：  
  
;默认模块中的backend目录的路由配置
```shell
backend_index.type="rewrite"
backend_index.match="/(backend|backend/)$"
backend_index.route.module="index"
backend_index.route.controller="backend_index"
backend_index.route.action="index"
backend_post_index.type="rewrite"
backend_post_index.match="/Backend/(posts|posts/)$"
backend_post_index.route.module="index"
backend_post_index.route.controller="backend_posts"
backend_post_index.route.action="index"
```
此时可以通过访问下面的url，访问index模块的backend目录下的Index.php的Backend_IndexController控制器中的index方法(默认方法)。  
http://www.yaftest.io/backend/  
  
错误示范：刚开始把backend_index这个控制器改为index，结果每次都会跑index默认模块目录下的index控制器而不是backend目录下的index控制器。原因在于这些控制器都没有使用命名空间，因此，改成index时，只能被判断成是访问前者。  
  
  
配置其他模块路由：  
----------------------------
//第一步：编辑routes.ini文件，配置路由规则  
> ;Admin routes  
> admin.admin_index.type="rewrite"  
> admin.admin_index.route.module="admin"    
  
//此处可以匹配到admin模块中的Index控制器    
> admin.admin_index.match="/(admin|admin/)$"    
> admin.admin_index.route.controller="index"  
> admin.admin_index.route.action="index"  
   
//此处可以匹配到admin模块中的Test控制器   
//错误写法：admin.admin_index.match="/admin/test"（无法匹配到test控制器里面的方法） 
admin.admin_index.match="/(admin/test/)$"  
admin.admin_index.route.controller="test"  
admin.admin_index.route.action="index"  
  
//第二步：配置_init.php文件，将当前模块的路由规则加入应用生效  
```php
<?php
$dis=Yaf\Dispatcher::getInstance();

//Initialize Routes for Admin module
$routes = new Yaf\Config\Ini(__DIR__ . "/config" . "/routes.ini");
$dis->getRouter()->addConfig($routes->admin);
```
此时可以通过访问下面的url，访问test控制器中的test方法，其中后一个test为控制器的方法名，直接通过修改即可更改对方法的访问。  
  http://www.yaftest.io/admin/test/test
  
  
八、控制器简单模板：  
-----------
编辑application/controllers/backend/index.php文件：  
```php
<?php
use Yaf\Controller_Abstract;
use Yaf\Dispatcher;
class Backend_IndexController extends Controller_Abstract
{
public function indexAction()
{//默认Action
$this->getView()->assign("content", "I am in application/controllers/Backend/Index/indexAction");
}

public function testAction(){
// $this->getView()->assign("testcontent", "test hello");
Dispatcher::getInstance()->disableView(0);
echo 'Great,It Works!';
}
}
```
  
九、视图文件模板：  
-----------
默认模块的视图文件位于application/views下的对应文件目录中，而其他模块对应的视图文件位于模块各自的views文件目录中。  
```html
<html>
<head>
<title>My first yaf app</title>
</head>
<body>
<?php echo $content;?>
</body>
</html>
```
   
  
  
十、nginx配置：  
-----------
该文件实现对路由的重写功能,每个url请求都会经过index.php入口文件。  
```nginx
server {
  listen ****;
  server_name  domain.com;
  root   document_root;
  index  index.php index.html index.htm;

  if (!-e $request_filename) {
    rewrite ^/(.*)  /index.php/$1 last;
  }
}
```
  
  
  
   
  
  
yaf在php.ini中可以加一些配置项：  
-----------
比较典型的是：yaf.name_suffix，yaf.name_separator    
  
选项名称	默认值	可修改范围	更新记录  
yaf.environ	product	PHP_INI_ALL	环境名称, 当用INI作为Yaf的配置文件时, 这个指明了Yaf将要在INI配置中读取的节的名字  
  
yaf.library	NULL	PHP_INI_ALL	全局类库的目录路径  
  
yaf.cache_config	0	PHP_INI_SYSTEM	是否缓存配置文件(只针对INI配置文件生效), 打开此选项可在复杂配置的情况下提高性能  
  
yaf.name_suffix	1	PHP_INI_ALL	在处理Controller, Action, Plugin, Model的时候, 类名中关键信息是否是后缀式, 比如UserModel, 而在前缀模式下则是ModelUser  
  
yaf.name_separator	""	PHP_INI_ALL	在处理Controller, Action, Plugin, Model的时候, 前缀和名字之间的分隔符, 默认为空, 也就是UserPlugin, 加入设置为"_", 则判断的依据就会变成:"User_Plugin", 这个主要是为了兼容ST已有的命名规范  
  
yaf.forward_limit	5	PHP_INI_ALL	forward最大嵌套深度  
  
yaf.use_namespace	0	PHP_INI_SYSTEM	开启的情况下, Yaf将会使用命名空间方式注册自己的类, 比如Yaf_Application将会变成Yaf\Application  
  
yaf.use_spl_autoload	0	PHP_INI_ALL	开启的情况下, Yaf在加载不成功的情况下, 会继续让PHP的自动加载函数加载, 从性能考虑, 除非特殊情况, 否则保持这个选项关闭  
  
  
注：  
在项目的library、models目录下的类文件会被自动加载  
  







