

上传文件限制
--------------
php.ini
```
post_max_size = 100M
upload_max_filesize = 100M

memory_limit = 1280M 
```

nginx.conf
```
client_max_body_size 100m;
```


php的fcrypt扩展
---------------


php进程信号
---------------
SIGCHLD     子进程退出成为僵尸进程会向父进程发送此信号
SIGHUP      进程挂起
SIGTEM      进程终止
...         // 其他请在手册中查看


curl
----------------
```
// 1. 初始化
$ch = curl_init();
// 2. 设置选项，包括URL
curl_setopt($ch,CURLOPT_URL,$url);
curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch,CURLOPT_HEADER,0);
// 3. 执行并获取HTML文档内容
$output = curl_exec($ch);
var_dump($output);
if($output === FALSE ){
    echo "CURL Error:".curl_error($ch);
}
// 4. 释放curl句柄
curl_close($ch);exit;
```


设置常用的content-type
----------------
```
//定义编码  
header( 'Content-Type:text/html;charset=utf-8 ');  
  
//Atom  
header('Content-type: application/atom+xml');  
  
//CSS  
header('Content-type: text/css');  
  
//Javascript  
header('Content-type: text/javascript');  
  
//JPEG Image  
header('Content-type: image/jpeg');  
  
//JSON  
header('Content-type: application/json');  
  
//PDF  
header('Content-type: application/pdf');  
  
//RSS  
header('Content-Type: application/rss+xml; charset=ISO-8859-1');  
  
//Text (Plain)  
header('Content-type: text/plain');  
  
//XML  
header('Content-type: text/xml');  
  
// ok  
header('HTTP/1.1 200 OK');  
  
//设置一个404头:  
header('HTTP/1.1 404 Not Found');  
  
//设置地址被永久的重定向  
header('HTTP/1.1 301 Moved Permanently');  
  
//转到一个新地址  
header('Location: http://www.example.org/');  
  
//文件延迟转向:  
header('Refresh: 10; url=http://www.example.org/');  
print 'You will be redirected in 10 seconds';  
  
//当然，也可以使用html语法实现  
// <meta http-equiv="refresh" content="10;http://www.example.org/ />  
  
// override X-Powered-By: PHP:  
header('X-Powered-By: PHP/4.4.0');  
header('X-Powered-By: Brain/0.6b');  
  
//文档语言  
header('Content-language: en');  
  
//告诉浏览器最后一次修改时间  
$time = time() - 60; // or filemtime($fn), etc  
header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT');  
  
//告诉浏览器文档内容没有发生改变  
header('HTTP/1.1 304 Not Modified');  
  
//设置内容长度  
header('Content-Length: 1234');  
  
//设置为一个下载类型  
header('Content-Type: application/octet-stream');  
header('Content-Disposition: attachment; filename="example.zip"');  
header('Content-Transfer-Encoding: binary');  
// load the file to send:  
readfile('example.zip');  
  
// 对当前文档禁用缓存  
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');  
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past  
header('Pragma: no-cache');  
  
//设置内容类型:  
header('Content-Type: text/html; charset=iso-8859-1');  
header('Content-Type: text/html; charset=utf-8');  
header('Content-Type: text/plain'); //纯文本格式  
header('Content-Type: image/jpeg'); //JPG***  
header('Content-Type: application/zip'); // ZIP文件  
header('Content-Type: application/pdf'); // PDF文件  
header('Content-Type: audio/mpeg'); // 音频文件  
header('Content-Type: application/x-shockw**e-flash'); //Flash动画  
  
//显示登陆对话框  
header('HTTP/1.1 401 Unauthorized');  
header('WWW-Authenticate: Basic realm="Top Secret"');  
print 'Text that will be displayed if the user hits cancel or ';  
print 'enters wrong login data';
```




