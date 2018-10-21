

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


你觉得在PV10W的时候, 同等配置下,LUNIX 比WIN快多少?
------------
不做优化的情况下一样


简述POST 和GET传输的最大容量分别是多少?
------------
2MB(可在php.ini中更改),1024B




