PHPRPC 是一个轻型的、安全的、跨网际的、跨语言的、跨平台的、跨环境的、跨域的、支持复杂对象传输的、支持引用参数传递的、支持内容输出重定向的、支持分级错误处理的、支持会话的、面向服务的高性能远程过程调用协议。了解更多请访问 http://www.phprpc.com。  
点击上面的链接去官网下载的php版本，把压缩包解压到网站根目录（我的是phprpc）。在根目录下创建两个测试文件server.php与client.php。  
  
server.php代码：  
```php
include ("php/phprpc_server.php");

$server = new PHPRPC_Server();
$server->add('HelloWorld');
$server->start();

function HelloWorld() {
    return 'Hello World!';
}
```
  
client.php代码：  
```php
include ("php/phprpc_client.php");
$client = new PHPRPC_Client('http://localhost/server.php');
echo $client->HelloWorld();
```
用浏览器访问http://127.0.0.1/client.php，正常输出 "Hello World!"。

  
注意：
如果报如下错误：Fatal error: Cannot redeclare gzdecode() in xxxx   
  
原因是php在5.4版本后启用PHPRPC模式时，已经自包含了gzdecode()函数，开发者自己定义的gzdecode()函数会与其冲突。  
   
解决方法：打开phprpc\compat.php，在第72行（可能有差异）找到function gzdecode($data, &$filename = '', &$error = '', $maxlength = null) ，把这个函数用下面的代码包括起来即可。
```php
if (! function_exists('gzdecode')) {
    //将gzdecode函数包括进来
}  
```









