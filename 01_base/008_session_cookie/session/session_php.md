

参考地址：  
---------------
https://blog.csdn.net/hai_qing_xu_kong/article/details/52262182  


PHP session 变量用于存储有关用户会话的信息，或更改用户会话的设置。Session 变量保存的信息是单一用户的，并且可供应用程序中的所有页面使用。  

PHP Session 变量  
当您运行一个应用程序时，您会打开它，做些更改，然后关闭它。这很像一次会话。计算机清楚你是谁。它知道你何时启动应用程序，并在何时终止。但是在因特网上，存在一个问题：服务器不知道你是谁以及你做什么，这是由于 HTTP 地址不能维持状态。  

通过在服务器上存储用户信息以便随后使用，PHP session 解决了这个问题（比如用户名称、购买商品等）。不过，会话信息是临时的，在用户离开网站后将被删除。如果您需要永久储存信息，可以把数据存储在数据库中。  



Session 的工作机制是：
---------------
为每个访问者创建一个唯一的 id (UID)，并基于这个 UID 来存储变量。UID 存储在 cookie 中，亦或通过 URL 进行传导。  



开始 PHP Session
----------------
在您把用户信息存储到 PHP session 中之前，首先必须启动会话。

注意：session_start() 函数前不能有输出
> session_start();   

上面的代码会向服务器注册用户的会话，以便您可以开始保存用户信息，同时会为用户会话分配一个 UID。




存储 Session 变量
----------------
```php
// 存储和取回 session 变量的正确方法是使用 PHP $_SESSION 变量：
session_start();
// store session data
$_SESSION['views']=1;

//retrieve session data
echo "Pageviews=". $_SESSION['views'];
// 输出：
// Pageviews=1
```

// 在下面的例子中，我们创建了一个简单的 page-view 计数器。isset() 函数检测是否已设置"views" 变量。如果已设置 "views" 变量，我们累加计数器。如果 "views" 不存在，则我们创建 "views" 变量，并把它设置为 1：
```php
session_start();

if(isset($_SESSION['views'])) {
	$_SESSION['views']=$_SESSION['views']+1;
} else {
	$_SESSION['views']=1;
}
echo "Views=". $_SESSION['views'];
```



终结 Session
----------------
如果您希望删除某些 session 数据，可以使用 unset() 或 session_destroy() 函数。  

unset() 函数用于释放指定的 session 变量：  
> unset($_SESSION['views']);  

您也可以通过 session_destroy() 函数彻底终结 session
session_destroy() 将重置 session，您将失去所有已存储的 session 数据
> session_destroy();  


其它函数
----------------
```
session_name() //设置获取session_id 的键 (SESSID)  
session_id() //设置获取session_id 的值
session_set_cookie_params(expire, path, domain, secure, httponly) // 仅仅设置的是session_id在cookie中的存储参数
```



如果cookie关闭后，基于url的session的实现
----------------
　　我们知道cookie是客户端功能 当然客户可以自由的开启和关闭，那么引出一个问题，客户端关闭cookie、后session是不是就失效了，答案是肯定的。那该如何实现用户跟踪呢，我们可以为session正常的使用设立第二道防线，那就是利用url、在各个脚本间传递session_id的值。具体实现如下。


一、方式1：手动在每个url地址后拼装session_id  

1.使用两个函数 session_name()获取当前会话的名称、session_id()获取和设置当前会话的ID 拼接为请求字符串连接在各个url连接后面。  
如：http://www.baidu.com/index.php?sname=sid  

2.在其他页面接收这个sid 注册为当前session_id    
如：  
```
if ( isset( $_GET[session_name()] ) ){
	session_id( $_GET[session_name());    //注册为当前的session_id
}
//不要忘记注册session_id后开启session 开启已有会话 否则创建新会话
session_start(); 
```

按照这样操作是不是感觉太繁琐 哪个页面页至少也有几十上百个链接吧，难免会有疏忽。值得高兴的是php已经为我们提供了更为简单的方式，不过原理还是那个原理。  
具体实现如下  



二、方式2：通过配置让每个url地址后自动带上session_id  

1.设置配置项  

开启session_use_trans_sid = 1 //开启自动追加session_id  
关闭session.use_only_cookies = 0  //关闭仅使用cookie项  
  
后系统会在cookie关闭之后自动的为每个链接加上session_id 数据(链接上会有显示)。  
  

2.依然需要在其它页面进行判断接收这个session_id 进行注册。(语法如上不变).  
```php
if ( isset( $_GET[session_name()] ) ){
	session_id( $_GET[session_name());    //注册为当前的session_id
}
session_start(); //不要忘记注册session_id后开启session 开启已有会话 否侧创建新会话
```




php.ini中关于session和cookie的配置
------------------
session是基于cookie实现的，session-id 存储在cookie中，所以 这个session-id的属性决定了session的属性。  

配置项中有对应设置  
```
对于终端session_id属性的设置 ：
	session.cookie_life = 0;    //有效期(默认浏览器关闭)

	session.cookie_path = '/';  //默认跟路径

	session.cookie_domain = ; //有效域名

	session.cookie_secure = ; //是否安全传输 HTTPS

	session.cookie_httponly = ; //是否只http传输
```
推荐使用  session_set_cookie_params(expire, path, domain, secure, httponly) 来设置这些属性。  



接下来看看服务器端配置：
```
　　session.save_path =    //session数据存储路径

　　session.name = PHPSESSID; //保存在cookie中sessionid的键

　　session.auto_start = 0; //是否自动开启session

　　session.use_trans_sid ; //是否开启自动传递SID 功能

　　session.use_only_cookie = 1; //是否只依赖cookie传递SID

　　session.gc_maxlifetime = 1440;  //session数据及文件生存周期 24分钟后视为垃圾

　　session.gc_probability = 1;  
　　session.gc_dirisor = 100;    
```
以上两个配置组合成了session的垃圾回收机制回收的概率，默认100次请求触发1次垃圾回收。session垃圾回收机制是惰性删除 过期之后不会马上删除而是等待回收概率的触发。  

session_save_handler = files; // session默认的保存介质 文件  





