  
http缓存机制  
-------------
缓存分类:   
http缓存模型中，如果请求成功会有三种情况   
200 from cache：直接从本地缓存中获取响应，最快速，最省流量，因为根本没有向服务器发送请求。  
这咱方式速度最快    
  
304 not modified：协商缓存，浏览器在本地缓存没有命中(比如本地缓存失效了)的情况下，在请求头中发送一定的校验数据到服务端，如果服务端数据没有改变，浏览器从本地缓存响应，返回304   
这种方式快速，发送的数据很少，只返回一些基本的响应头信息，数据量很小，不发送实际响应体。  
   
200 ok：以上两咱缓存全都失败，服务器返回完整响应。没有用到缓存，相对最慢。  
  
  
  
本地缓存   
  
浏览器认为本地缓存可以使用，则不会去请求服务端。   
  
相关header：  
pragma：http1.0时代的遗留产物，该字段被设置为no-cache时，会告诉浏览器禁用本地缓存，即每次都向服务器发送请求。  
  
  
expires：http1.0时代用来启用本地缓存的字段，expires值对应一个形如thu,31 dec 2037 23:55:55 GMT 的格林威治时间，告诉浏览器缓存实现的时刻，标明缓存有效，无需发送请求。  
这里面存在一个问题：浏览器与服务器的时间无法保持一致，如果时间差距大，就会影响缓存结果。  
  
  
Cache-Control：http1.1针对expires时间不一致的解决方案，运用cache-control告知浏览器缓存过期的时间间隔而不是时刻，即使具体时间不一致，也不影响缓存的管理。  
  
Cache-Control可设的值有  
no-store：禁止浏览器缓存响应  
no-cache：不允许直接使用本地缓存，先发起请求和服务器协商  
max-age=delta-seconds：告知浏览器该响应本地缓存有效的最长期限，以秒为单位。  
  
优先级  
pragma > cache-control > expires  
  
  
协商缓存   
  
当浏览器没有命中本地缓存，如本地缓存过期或者响应中声明不允许直接使用本地缓存，那么浏览器肯定会发起服务端请求；服务端会验证数据是否修改，如果没有修改，则通知浏览器使用本地缓存  
  
相关header：  
一种：
Last-Modified：通知浏览器资源的最后修改时间  
If-Modified-Since：浏览器会将得到资源的最后修改时间通过If-Modified-Since提交到服务器做检查，如果没有修改，返回304状态码  
  
另一种：
ETag：http1.1推出，文件的指纹标识符，如果文件内容修改，指纹会改变  
If-None-Match：本地缓存失效，会携带此值去请求服务端，服务端判断该资源是否改变，如果没有改变，直接使用本地缓存，返回304  
   
  

适合本地缓存的内容  
不变的图像，如logo，图标等  
js、css静态文件等  
可下载的内容，媒体文件  
    
  
适合使用协商缓存的内容  
html文件  
经常替换的图片  
经常修改的js、css文件  
  
  
不适合缓存的内容  
用户隐私等敏感数据  
经常改变的api数据接口  


last-modified示例
```php
<?php
$since = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
$lifetime = 3600;
if (strtotime($since) + $lifetime > time())
{
	header('HTTP/1.1 304 Not Modified');
	exit;
}

header('Last-Modified：'.gmdate('D, d M Y H:i:s', time()). ' GMT');
echo time();

```
访问该页面，并刷新会发现304状态，同时能看到响应头中的Last-Modified 与请求头中的If-Modified-Since  
  
  
nginx配置缓存策略  
  
本地缓存配置  
  
add_header指令：添加状态码为2xx和3xx的响应头信息  
add_header name value [always];  
可以设置Pragma/Expires/Cache-Control，可以继承  
  
expires提令：通知浏览器过期时长  
expires tiem;  如 expires 30d;  expires 12h; 等  
如果为负值时，表示Cache-Control:no-cache;  
如果为正或者0时，表示Cache-Control:max-age=指定的时间;  
  
nginx配置
```nginx
location ~ .*\.(js|css)?$
{
	expires 12h;
	#add_header cache-control max-age=3600;
}
```
  
  
  
   
前端代码和资源的压缩    
  
javascript代码压缩  
js压缩的原理：  
一般是去掉多余的空格和回车、替换长变量名、简化一些代码写法等。  
  
常用压缩工具：  
UglifyJS，YUI Compressor，Closure Compiler  
  
一个在线压缩地址：  
https://www.css-js.com/  
  
  
css压缩的原理：  
同js压缩类似，同样是去掉多余的空白符、注释并且优化一些css语义规则等。  
常用压缩工具：   
YUI Compressor，CSS Compressor  
  
  
图片压缩  
除了代码的压缩外，有时对图片的压缩也是很有必要，一般情况下图片在web系统的比重都比较大。  
压缩工具：  
tinypng，jpegmini，imageoption  
地址：
https://tinypng.com/
  
  
html代码压缩  
不建议使用代码压缩，有时会破坏代码结构，可以使用gzip压缩，当然也可以使用htmlcompressor工具，不过转换后一定要检查代码结构。  
在线地址：  
http://htmlcompressor.com/compressor/  
   
  


gzip压缩   
gzip on|off; 				# 是否开启gzip  
gzip_buffers 32 4k | 16 8k 	# 缓冲（在内存中缓冲几块？每块多大？）  
gzip_comp_level [1-9] 		# 推荐6 压缩级别（级别越高，压的越小，越浪费cpu计算资源）   
gzip_disable "MSIE [1-6]\."  # 正则匹配UA, 什么样的uri不进行gzip  
gzip_min_length 200 		# 开始压缩的最小长度  
gzip_http_version 1.0|1.1   # 开始压缩的http协议版本  
gzip_proxied				# 设置请求者代理服务器，该如何缓存内容  
gzip_types text/plain application/xml #对哪些类型的文件用压缩 如txt,xml,html,css  
gzip_vary on|off			# 是否传输gzip压缩标志  































  


























