  
防盗链工作原理  
-------------  
通过referer或者签名，网站可以检测目标网页访问的来源网页，如果是资源文件，则可以跟踪到显示它的网页地址。一旦检测到来源不是本站即进行阻止或者返回指定的页面。  
  
  
方式一：通过referer  
-------------
nginx模块ngx_http_referer_module用于阻挡来源非法的域名请求，nginx指令valid_referers，全局变量$invalid_referer  
要使用该模块，先确保nginx已安装ngx_http_referer_module模块  
  
例：  
```nginx
location ~ .*\.(gif|jpg|png|flv|swf|rar|zip)$
{
  valid_referers none blocked imooc.com *.imooc.com;
  # 如果上面的判不合法，则下面的变量$invalid_referer值为1
  if ($invalid_referer)
  {
    #return 403;
    rewrite ^/ http://www.imooc.com/403.jpg;
  }
}
```
传统防盗链遇到的问题：伪造referer，可以使用加密签名解决    
  
  
方式二：使用加密签名解决  
-------------
使用第三方模块HttpAccessKeyModule实现nginx防盗链  
> accesskey on|off 模块开关  
> accesskey_hashmethod md5 | sha-1 签名的加密方式  
> accesskey_arg GET参数名称  
> accesskey_signature 加密规则，例："mypass$remote_addr"  
  
例:   
```nginx
location ~ .*\.(gif|jpg|png|flv|swf|rar|zip)$
{
	accesskey on;
	accesskey_hashmethod md5; #加密方式md5
	accesskey_arg "sign"; #指定get参数为sign
	accesskey_signature "abcd123$remote_addr"; #假设mypass为aced123
}
```
  
php端  
```php
<?php
//同nginx中配置的规则一致
$sign = md5('abcd123'.$_SERVER['REMOTE_ADDR']);
echo '<img src="./xxx.jpg?sign='.$sign.'" />';
```





  























