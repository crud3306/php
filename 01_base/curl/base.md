
curl是什么
===========
PHP supports libcurl, a library created by Daniel Stenberg, that allows you to connect and communicate to many different types of servers with many different types of protocols. libcurl currently supports the http, https, ftp, gopher, telnet, dict, file, and ldap protocols. libcurl also supports HTTPS certificates, HTTP POST, HTTP PUT, FTP uploading (this can also be done with PHP's ftp extension), HTTP form based upload, proxies, cookies, and user+password authentication.

这是PHP对于curl的一个解释，简单地说就是，curl是一个库，能让你通过URL和许多不同种的服务器进行勾搭、搭讪和深入交流，并且还支持许多协议。并且人家还说了curl可以支持https认证、http post、ftp上传、代理、cookies、简单口令认证等等功能啦。<

在正式讲怎么用之前啊，先提一句，你得先在你的PHP环境中安装和启用curl模块，具体方式我就不讲了，不同系统不同安装方式，可以google查一下，或者查阅PHP官方的文档，还挺简单的。


先试试手
===========
工具到手，先要把玩，试试顺不顺手，不然一拿来就用，把你自己的代码搞得乌烟瘴气还怎么去撩服务器呢？
比如我们以著名的“测试网络是否连接”的网站——百度为例，来尝试下curl

```php
// create curl resource 
$ch = curl_init(); 

// set url 
curl_setopt($ch, CURLOPT_URL, "baidu.com"); 

//return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

// $output contains the output string 
$output = curl_exec($ch); 

//echo output
echo $output;

// close curl resource to free up system resources 
curl_close($ch); 
 ```  

当你在本地环境浏览器打开这个php文件时，页面出现的是百度的首页？
上面的代码和注释已经充分说明了这段代码在干啥。
```sh
#创建了一个curl会话资源，成功返回一个句柄；
$ch = curl_init()，  
#设置待请求的URL
curl_setopt($ch, CURLOPT_URL, "baidu.com");  

#上面两句可以合起来变一句 
$ch = curl_init("baidu.com")；  

#这是设置是否将响应结果存入变量，1是存入，0是直接echo出； 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
#执行，然后将响应结果存入$output变量，供下面echo；
$output = curl_exec($ch);

#关闭这个curl会话资源
curl_close($ch)
```

PHP中使用curl大致就是这么一个形式，其中第二步，通过curl_setopt方法来设置参数是最复杂也是最重要的，感兴趣可以去看官方的关于可设置参数的详细参考，长地让你看得想吐，还是根据需要熟能生巧吧。

小结一下，php中curl用法就是：  
- 创建curl会话 
- 配置参数 
- 执行 
- 关闭会话。


下面我们来看一些常用的情景，我们需要如何“打扮自己”（配置参数）才能正确正确撩到服务器。


打个招呼——GET和POST请求以及HTTPS协议处理
===========
先和服务器打个招呼吧，给服务器发个Hello看她怎么回，这里最方便的方式就是向服务器发出GET请求，当然POST这种小纸条也OK咯。

2.1 GET请求
-----------
```php
// create curl resource 
$ch = curl_init(); 

// set url 
curl_setopt($ch, CURLOPT_URL, "https://github.com/search?q=react"); 

//return the transfer as a string 
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

// $output contains the output string 
$output = curl_exec($ch); 

//echo output
echo $output;

// close curl resource to free up system resources 
curl_close($ch);    
```  

好像和之前那个例子没啥差别，但这里有2个可以提的点：   
1.默认请求方式是GET，所以不需要显式指定GET方式；  

2.https请求，非http请求，可能有人在各个地方看到过HTTPS请求需要加几行代码绕过SSL证书的检查等方式来成功请求到资源，但是这里好像并不需要，原因是什么？   
```sh
#curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
#curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

The two Curl options are defined as:
CURLOPT_SSL_VERIFYPEER - verify the peer's SSL certificate  
CURLOPT_SSL_VERIFYHOST - verify the certificate's name against host
They both default to true in Curl, and shouldn't be disabled unless you've got a good reason. Disabling them is generally only needed if you're sending requests to servers with invalid or self-signed certificates, which is only usually an issue in development. Any publicly-facing site should be presenting a valid certificate, and by disabling these options you're potentially opening yourself up to security issues.
```
即，除非用了非法或者自制的证书，这大多数出现在开发环境中，你才将这两行设置为false以避开ssl证书检查，否者不需要这么做，这么做是不安全的做法。


2.2 POST请求
-----------
那如何进行POST请求呢？为了测试，先在某个测试服务器传了一个接收POST的脚本：
```php
$phpInput=file_get_contents('php://input');
echo urldecode($phpInput);
```

发送普通数据
然后在本地写一个请求：
```php
$data = [
	"name" => "Lei",
	"msg" => "Are you OK?"
];

$ch = curl_init(); 

curl_setopt($ch, CURLOPT_URL, "http://测试服务器的IP/xxx.php"); 
curl_setopt($ch, CURLOPT_POST, 1);
//The number of seconds to wait while trying to connect. Use 0 to wait indefinitely.
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); 
curl_setopt($ch, CURLOPT_POSTFIELDS , http_build_query($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

$output = curl_exec($ch); 

echo $output;

curl_close($ch);     
```

浏览器运行结果是：
```sh
name=Lei&amp;msg=Are you OK?
```

这里我们是构造了一个数组作为POST数据传给服务器：
```sh
#表明是POST请求；
curl_setopt($ch, CURLOPT_POST, 1);

#设置一个最长的可忍受的连接时间，秒为单位，总不能一直等下去变成木乃伊吧；
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);

#设置POST的数据域，因为这里是数组数据形式的（等会来讲json格式），所以用http_build_query处理一下。
curl_setopt($ch, CURLOPT_POSTFIELDS , http_build_query($data);
```

对于json数据呢，又怎么进行POST请求呢？
```php
$data='{"name":"Lei","msg":"Are you OK?"}';

$ch = curl_init(); 

curl_setopt($ch, CURLOPT_URL, "http://测试服务器的IP马赛克/testRespond.php"); 
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); 
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-Length:' . strlen($data)));
curl_setopt($ch, CURLOPT_POSTFIELDS , $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

$output = curl_exec($ch); 

echo $output;

curl_close($ch); 
```

浏览器执行，显示：
```sh
{"name":"Lei","msg":"Are you OK?"}
```


如何上传和下载文件
===========

3.1 POST上传文件
-----------
同样远程服务器端我们先传好一个接收脚本,接收图片并且保存到本地，注意文件和文件夹权限问题，需要有写入权限：
```php
    if($_FILES){
        $filename = $_FILES['upload']['name'];
          $tmpname = $_FILES['upload']['tmp_name'];
          //保存图片到当前脚本所在目录
          if(move_uploaded_file($tmpname,dirname(__FILE__).'/'.$filename)){
            echo ('上传成功');
          }
    }
```
然后我们再来写我们本地服务器的php curl部分：

```php
$data = array('name'='boy', "upload"="@boy.png");

$ch = curl_init(); 

curl_setopt($ch, CURLOPT_URL, "http://远程服务器地址/testRespond.php"); 
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); 
curl_setopt($ch, CURLOPT_POSTFIELDS , $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

$output = curl_exec($ch); 

echo $output;

curl_close($ch);         
```

浏览器中运行一下，什么都米有，去看一眼远程的服务器，还是什么都没有，并没有上传成功。

为什么会这样呢？

上面的代码应该是大家搜索curl php POST图片最常见的代码，这是因为我现在用的是PHP5.6以上版本，@符号在PHP5.6之后就弃用了，PHP5.3依旧可以用，所以有些同学发现能执行啊，有些发现不能执行，大抵是因为PHP版本的不同，而且curl在这两版本中实现是不兼容的，上面是PHP5.3的实现。

下面来讲PHP5.6及以后的实现：
```php
$data = array('name'='boy', "upload"="");
$ch = curl_init(); 

$data['upload']=new CURLFile(realpath(getcwd().'/boy.png'));

curl_setopt($ch, CURLOPT_URL, "http://115.29.247.189/test/testRespond.php");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); 
curl_setopt($ch, CURLOPT_POSTFIELDS , $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

$output = curl_exec($ch); 

echo $output;

curl_close($ch);         
```

这里引入了一个CURLFile对象进行实现，关于此的具体可查阅文档进行了解。这时候再去远程服务器目录下看看，发现有了一张图片了，而且确实是我们刚才上传的图片。


3.2 获取远程服务器的图片 —— 抓取图片
---------------
服务器妹子也挺实诚的，看了照骗觉得我长得挺慈眉善目的，就大方得拿出了她自己的照片，但是有点害羞的是，她不愿意主动拿过来，得我们自己去取。
远程服务器在她自己的目录下存放了一个图片叫girl.jpg，地址是她的web服务器根目录/girl.jpg，现在我要去获取这张照片。

```php
$ch = curl_init(); 

$fp=fopen('./girl.jpg', 'w');

curl_setopt($ch, CURLOPT_URL, "http://远程服务器地址马赛克/girl.jpg"); 
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60); 
curl_setopt($ch, CURLOPT_FILE, $fp); 

$output = curl_exec($ch); 
$info = curl_getinfo($ch);

fclose($fp);

$size = filesize("./girl.jpg");
if ($size != $info['size_download']) {
    echo "下载的数据不完整，请重新下载";
} else {
    echo "下载数据完整";
}

curl_close($ch);    
```

现在，在我们当前目录下就有了一张刚拿到的照片啦，是不是很激动呢！
这里值得一说的是curl_getinfo方法，这是一个获取本次请求相关信息的方法，对于调试很有帮助，要善用。


HTTP认证
===========
这个时候呢，服务器的家长说这个我们女儿还太小，不能找对象，就将她女儿关了起来，并且上了一个密码锁，所谓的HTTP认证，服务器呢偷偷托信鸽将HTTP认证的用户名和密码给了你，要你去见她，带她私奔。
那么拿到了用户名和密码，我们怎么通过PHP CURL搞定HTTP认证呢？
PS:这里偷懒就不去搭HTTP认证去试了，直接放一段代码，我们分析下。

```php
function curl_auth($url, $user, $passwd){
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_USERPWD = $user.':'.$passwd,
        CURLOPT_URL     = $url,
        CURLOPT_RETURNTRANSFER = true
    ]);
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

$authurl = 'http://要请求HTTP认证的地址';

echo curl_auth($authurl, 'zhangsan','passwdxxx');
```

这里有一个地方注意：  
curl_setopt_array 这个方法可以通过数组一次性地设置多个参数，防止有些需要多处设置的出现密密麻麻的curl_setopt方法。



利用cookie模拟登陆
===========

分两步，
- 一是 去登陆界面通过账号密码登陆，然后获取cookie;
- 二是 去利用cookie模拟登陆到信息页面获取信息，大致的框架是这样的。

```php
//设置post的数据  
$post = array ( 
'email' = '账户', 
'pwd' = '密码'
); 
//登录地址  
$url = "登陆地址";  
//设置cookie保存路径  
$cookie = dirname(__FILE__) . '/cookie.txt';  

//登录后要获取信息的地址  
$url2 = "登陆后要获取信息的地址";  

//模拟登录 
login_post($url, $cookie, $post);  

//获取登录页的信息  
$content = get_content($url2, $cookie);  

//删除cookie文件 
@unlink($cookie);
 
var_dump($content);    
```

然后我们思考下下面两个方法的实现：  
login_post($url, $cookie, $post)  
get_content($url2, $cookie)  


```php
//模拟登录  
function login_post($url, $cookie, $post) { 
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 0);
    curl_setopt($curl, CURLOPT_COOKIEJAR, $cookie);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post));
    curl_exec($curl); 
    curl_close($curl);
} 

function get_content($url, $cookie) { 
    $ch = curl_init(); 
    curl_setopt($ch, CURLOPT_URL, $url); 
    curl_setopt($ch, CURLOPT_HEADER, 0); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie); 
    $rs = curl_exec($ch); 
    curl_close($ch); 
    return $rs; 
} 
```

至此，总算是模拟登陆成功，一切顺利啦，通过php CURL“撩”服务器就是这么简单。  
当然，CURL的能力远不止于此，本文仅希望就后端PHP开发中最常用的几种场景做一个整理和归纳。最后一句话，具体问题具体分析。
