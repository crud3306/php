

composer
===========
Composer是PHP5.3以上的一个依赖管理工具。它允许你声明项目所依赖的代码库，它会在你的项目中为你安装他们。

Composer 不是一个包管理器。是的，它涉及"packages"和"libraries"，但它在每个项目的基础上进行管理，在你项目的某个目录中（例如vendor）进行安装。默认情况下它不会在全局安装任何东西。因此，这仅仅是一个依赖管理。

Composer是PHP中用来管理依赖（dependency）关系的工具。你可以在自己的项目中声明所依赖的外部工具库（libraries），Composer会帮你安装这些依赖的库文件。


生成composer.json文件
-----------
> composer init   
可以生成composer.json 文件。  

也可以自已手动建一个


自动加载 
----------- 
Composer提供了自动加载的特性，只需在你的代码的初始化部分中加入下面一行： 
> require 'vendor/autoload.php';  
这样就可以使用composer安装在vender目录下的所有资源了。  


依赖的安装方式
===========
安装方式1：
-----------
声明依赖
在项目目录下创建一个composer.json文件，指明依赖，比如，你的项目依赖 monolog：

{
    "require": {
        "monolog/monolog": "1.2.*"
    }
}
如果不需要使用https，可以这么写，以解决有时候因为https造成的问题：

{
    "require": {
        "monolog/monolog": "1.2.*"
    },
    "config": {
        "secure-http": false
    }
}

安装依赖
安装依赖非常简单，只需在项目目录下运行：
> composer install

如果没有全局安装的话，则运行：
> php composer.phar install

更新全部的包（谨慎使用）：
> composer update

注意：使用composer install或者composer update命令将会更新所有的扩展包，项目中使用需谨慎！！！

安装方式2：
-----------
若只安装指定的包推荐在命令行使用：
> composer require monolog/monolog
进行安装。

如果需要指定版本：
composer require "monolog/monolog:1.2.*"

更新某个包：
composer update monolog/monolog

移除某个包：
composer remove monolog/monolog

如果手动更新了composer.json需要更新autoload：
composer dump-autoload


包版本约束
-----------
精确版本：示例： 1.0.2。

范围：使用比较操作符你可以指定包的范围。这些操作符包括：>，>=，<，<=，!=。你可以定义多个范围，使用空格或者逗号,表示逻辑上的与，使用双竖线||表示逻辑上的或。其中与的优先级会大于或。示例：  

>=1.0  
>=1.0 <2.0  
>=1.0 <1.1 || >=1.2  
范围（使用连字符）:  
例子：1.0 - 2.0，等同于>=1.0.0 <2.1（2.0相当于2.0.*）。  

通配符：可以使用通配符去定义版本。1.0.*相当于>=1.0 <1.1。  
例子：1.0.*  

下一个重要版本操作符：使用波浪号~。示例：  
~1.2相当于>=1.2 <2.0.0，而~1.2.3相当于>=1.2.3 <1.3.0。  

折音号^：例如，^1.2.3相当于>=1.2.3 <2.0.0，因为在2.0版本前的版本应该都没有兼容性的问题。而对于1.0之前的版本，这种约束方式也考虑到了安全问题，例如^0.3会被当作>=0.3.0 <0.4.0对待。  



命令汇总：
```
composer list  列出所有可用的命令
composer init   初始化composer.json文件(就不劳我们自己费力创建啦)，会要求输入一些信息来描述我们当前的项目，还会要求输入依赖包
composer install  读取composer.json内容，解析依赖关系，安装依赖包到vendor目录下
composer update   更新最新的依赖关系到compsoer.lock文件，解析最新的依赖关系并且写入composer.lock文件
composer search packagename 搜索包，packagename替换为你想查找的包名称
composer require packagename 添加对packagename的依赖，packagename可修改为你想要的包名称
composer show packagename
composer self-update 更新 composer.phar文件自身
composer dump-autoload --optimize 优化一下自动加载

composer command --help 以上所有命令都可以添加 --help选项查看帮助信息
```



