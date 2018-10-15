
php curl操作

参考地址
-----------
http://www.php.cn/php-weizijiaocheng-393305.html （需看）  
https://www.cnblogs.com/manongxiaobing/p/4698990.html  


CURL是一个非常强大的开源库，支持很多协议，包括HTTP、FTP、TELNET等，我们使用它来发送HTTP请求。它给我 们带来的好处是可以通过灵活的选项设置不同的HTTP协议参数，并且支持HTTPS。CURL可以根据URL前缀是“HTTP” 还是“HTTPS”自动选择是否加密发送内容。  

使用CURL发送请求的基本流程
-----------
使用CURL的PHP扩展完成一个HTTP请求的发送一般有以下几个步骤：  
```
初始化连接句柄；  
设置CURL选项；  
执行并获取结果；  
释放VURL连接句柄。  
```




