

简述POST 和GET传输的最大容量分别是多少?
------------
2MB(可在php.ini中更改),1024B


PHP输入流php://input 与 $HTTP_RAW_POST_DATA
-------------
```
Coentent-Type仅在取值为application/x-www-data-urlencoded和multipart/form-data两种情况下，PHP才会将http请求数据包中相应的数据填入全局变量$_POST
PHP不能识别的Content-Type类型的时候，会将http请求包中相应的数据填入变量$HTTP_RAW_POST_DATA
只有Coentent-Type不为multipart/form-data的时候，PHP不会将http请求数据包中的相应数据填入php://input，否则其它情况都会。填入的长度，由Coentent-Length指定。
只有Content-Type为application/x-www-data-urlencoded时，php://input数据才跟$_POST数据相一致。
php://input数据总是跟$HTTP_RAW_POST_DATA相同，但是php://input比$HTTP_RAW_POST_DATA更凑效，且不需要特殊设置php.ini
PHP会将PATH字段的query_path部分，填入全局变量$_GET。通常情况下，GET方法提交的http请求，body为空。
```



PHP获取http请求的头信息实现步骤
------------
方法1：
```
foreach (getallheaders() as $name => $value) { 
	echo "$name: $value\n"; 
} 

注意：这个函数只能在apache环境下使用，iis或者nginx并不支持，可以通过自定义函数实现 
```
```
function get_headers() 
{ 
   foreach ($_SERVER as $name => $value) 
   { 
       if (substr($name, 0, 5) == 'HTTP_') 
       { 
           $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value; 
       } 
   } 
   return $headers; 
} 

查看结果:  
Array 
( 
	[Accept] => */* 
	[Accept-Language] => zh-cn 
	[Accept-Encoding] => gzip, deflate 
	[User-Agent] => Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727) 
	[Host] => localhost 
	[Connection] => Keep-Alive 
) 
```


如何在php中获取curl请求的请求头信息及响应头信息
------------
获取请求头信息，可以在curl_exec函数执行前，添加代码curl_setopt($ch,CURLINFO_HEADER_OUT,true);在curl_exec函数执行后，通过 curl_getinfo($ch,CURLINFO_HEADER_OUT) 来获取curl执行请求的请求数据。  

获取响应头信息，可以在curl_exec函数执行前，添加代码 curl_setopt($ch, CURLOPT_HEADER, true);curl_setopt($ch, CURLOPT_NOBODY,true); 之后 通过curl_exec函数来获取响应头信息。获取设置 curl_setopt($ch, CURLOPT_NOBODY,false);然后对curl_exec获取的值通过\r\n\r\n进行分割截取第一部分即为响应头信息。


echo count('abc'); 输出的结果是什么
-------------
count —计算数组中的单元数目或对象中的属性个数  
int count (mixed$var [, int$mode ] ), 如果 var 不是数组类型或者实现了Countable 接口的对象，将返回 1，有一个例外，如果 var 是NULL 则结果是 0。  
```
echo count('abc'); // 结果：1
echo count(NULL); // 结果：0
```


用最少的代码写一个求3值最大值的函数.
-------------
```
function who($a,$b,$c) {
	return $a > $b ? ($a > $c ? $a : $c) : ($b > $c ? $b : $c);
}
echo who(33,53,12);
```


函数实现-字符串“open_door" 转换成 “OpenDoor"、"make_by_id" 转换成 "MakeById"。
-----------
```
function towords($str) {
	$newStr = str_replace("_"," ",$str);
	$newStr = ucwords($str);
	$newStr = str_replace(" ","_",$str);
	return $newStr;
}
echo towords("open_door");
```


error_reporting(2047)什么作用?
-----------
相当于 error_reporting('E_ALL'); 输出所有的错误


php fsockopen
-----------
php fsockopen是一个非常强大的函数，支持socket编程，可以使用fsockopen实现邮件发送等socket程序等等，使用fsockopen需要自己手动拼接出header部分  

```
resource fsockopen  ( string $hostname  [, int $port  = -1  [, int &$errno  [, string &$errstr  [, float $timeout  = ini_get("default_socket_timeout")  ]]]] )

参数：
hostname 如果安装了OpenSSL，那么你也许应该在你的主机名地址前面添加访问协议ssl://或者是tls://，从而可以使用基于TCP/IP协议的SSL或者TLS的客户端连接到远程主机。 
port 端口号。如果对该参数传一个-1，则表示不使用端口，例如unix://。 
errno 如果errno的返回值为0，而且这个函数的返回值为 FALSE ，那么这表明该错误发生在套接字连接（connect()）调用之前，导致连接失败的原因最大的可能是初始化套接字的时候发生了错误。 
errstr 错误信息将以字符串的信息返回。 

timeout 设置连接的时限，单位为秒。

返回值：
fsockopen() 将返回一个文件句柄，之后可以被其他文件类函数调用（例如： fgets() ， fgetss() ， fwrite() ， fclose() 还有 feof() ）。如果调用失败，将返回 FALSE 。 

示例：
http://www.manongjc.com/article/1463.html
```


关于PHP重定向 
------------
```
方法一：
Header("HTTP/1.1 303 See Other");
// Header("Location: $url");
header("Location: index.php"); 

方法二：echo "<script>window.location ='".$url."';</script>"; 

方法三：echo '<meta http-equiv="refresh" content="0; url='.$url.'">'; 
```

header()函数主要的功能有哪些？使用过程中注意什么？
------------------
1、重定向   
Header("Location: $url");  

2、指定内容：  
header('Content-type: application/pdf');

3、附件：
	header('Content-type: application/pdf');  

	//指定内容为附件，指定下载显示的名字  
	header('Content-Disposition: attachment; filename="downloaded.pdf"');  

	//打开文件，并输出  
	readfile('original.pdf');  
	以上代码可以在浏览器产生文件对话框的效果  

4、让用户获取最新的资料和数据而不是缓存  
	header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1  
	header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");   // 设置临界时间  

```
<?php
header('HTTP/1.1 200 OK'); // ok 正常访问
header('HTTP/1.1 404 Not Found'); //通知浏览器 页面不存在
header('HTTP/1.1 301 Moved Permanently'); //设置地址被永久的重定向 301
header('Location: http://www.ithhc.cn/'); //跳转到一个新的地址
header('Refresh: 10; url=http://www.ithhc.cn/'); //延迟转向 也就是隔几秒跳转
header('X-Powered-By: PHP/6.0.0'); //修改 X-Powered-By信息
header('Content-language: en'); //文档语言
header('Content-Length: 1234'); //设置内容长度
header('Last-Modified: '.gmdate('D, d M Y H:i:s', $time).' GMT'); //告诉浏览器最后一次修改时间
header('HTTP/1.1 304 Not Modified'); //告诉浏览器文档内容没有发生改变
 
###内容类型###
header('Content-Type: text/html; charset=utf-8'); //网页编码
header('Content-Type: text/plain'); //纯文本格式
header('Content-Type: image/jpeg'); //JPG、JPEG 
header('Content-Type: application/zip'); // ZIP文件
header('Content-Type: application/pdf'); // PDF文件
header('Content-Type: audio/mpeg'); // 音频文件 
header('Content-type: text/css'); //css文件
header('Content-type: text/javascript'); //js文件
header('Content-type: application/json'); //json
header('Content-type: application/pdf'); //pdf
header('Content-type: text/xml'); //xml
header('Content-Type: application/x-shockw**e-flash'); //Flash动画
 
######
 
###声明一个下载的文件###
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="ITblog.zip"');
header('Content-Transfer-Encoding: binary');
readfile('test.zip');
######
 
###对当前文档禁用缓存###
header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
######
 
###显示一个需要验证的登陆对话框### 
header('HTTP/1.1 401 Unauthorized'); 
header('WWW-Authenticate: Basic realm="Top Secret"'); 
######
 
 
###声明一个需要下载的xls文件###
header('Content-Disposition: attachment; filename=ithhc.xlsx');
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Length: '.filesize('./test.xls')); 
header('Content-Transfer-Encoding: binary'); 
header('Cache-Control: must-revalidate'); 
header('Pragma: public'); 
readfile('./test.xls'); 
######
```




