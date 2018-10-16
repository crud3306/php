

参考地址：
-----------------
http://blog.jobbole.com/99314/  
https://blog.csdn.net/u013308496/article/details/54633792  



Nginx，PHP-fpm和FastCGI是怎么的运行流程呢？
-----------------
Nginx不支持对外部程序的直接调用或者解析，所有的外部程序（包括PHP）必须通过FastCGI接口来调用。FastCGI接口在Linux下是socket（这个socket可以是文件socket，也可以是ip socket）。

1)、php的FastCGI进程管理器php-fpm自身初始化，启动主进程php-fpm和启动start_servers个CGI 子进程。  
主进程php-fpm主要是管理fastcgi子进程，监听9000（这个根据配置文件的监听端口改变而变）端口。
fastcgi子进程等待来自Web Server的连接。  

2)、当客户端请求到达Web Server Nginx是时，Nginx通过location指令，将所有以php为后缀的文件都交给127.0.0.1:9000来处理，即Nginx通过location指令，将所有以php为后缀的文件都交给127.0.0.1:9000来处理。

3）FastCGI进程管理器PHP-FPM选择并连接到一个子进程CGI解释器。Web server将CGI环境变量和标准输入发送到FastCGI子进程。

4)、FastCGI子进程完成处理后将标准输出和错误信息从同一连接返回Web Server。当FastCGI子进程关闭连接时，请求便告处理完成。

5)、FastCGI子进程接着等待并处理来自FastCGI进程管理器（运行在 WebServer中）的下一个连接。



```
     www.example.com        
            |
        |
      Nginx        
         |
        |
    路由到www.example.com/index.php        
          |
        |
    加载nginx的fast-cgi模块        
          |
        |
   nginx通过fast-cgi将www.example.com/index.php请求转达到127.0.0.1:9000
        |
        |
  php-fpm 监听127.0.0.1:9000
        |
        |
  php-fpm 接收到请求，启用worker进程处理请求        
           |
        |
   php-fpm 处理完请求，返回给nginx        
           |
        |
  nginx将结果通过http返回给浏览器

```



PHP的核心架构
----------------





