

提高代码质量，四个原则  
--------------
通过所有测试: 以需求为上  
尽可能的消除重复：高内聚低耦合  
尽可能的清晰表达：可读性  
更少代码元素：常量，变量，函数，类，包 …… 都属于代码元素，降低复杂性  

以上四个原则的重要程度依次降低   


1 不要使用相对路径
---------------
常常会看到:
> require_once('../../lib/some_class.php');  

该方法有很多缺点:

它首先查找指定的php包含路径, 然后查找当前目录. 因此会检查过多路径.

如果该脚本被另一目录的脚本包含, 它的基本目录变成了另一脚本所在的目录.

另一问题, 当定时任务运行该脚本, 它的上级目录可能就不是工作目录了.

因此最佳选择是使用绝对路径:
```php
define('ROOT' , '/var/www/project/');  
require_once(ROOT . '../../lib/some_class.php');  
```

我们定义了一个绝对路径, 值被写死了. 我们还可以改进它. 路径 /var/www/project 也可能会改变, 那么我们每次都要改变它吗? 不是的, 我们可以使用__FILE__常量, 如:

```php
//suppose your script is /var/www/project/index.php  
//Then __FILE__ will always have that full path.  

define('ROOT' , pathinfo(__FILE__, PATHINFO_DIRNAME));

require_once(ROOT . '../../lib/some_class.php');
```
现在, 无论你移到哪个目录, 如移到一个外网的服务器上, 代码无须更改便可正确运行.



2 不要直接使用 require, include, include_once, required_once
---------------
在脚本头部引入多个文件, 像类库, 工具文件和助手函数等, 如:  
```php
require_once('lib/Database.php');  
require_once('lib/Mail.php');  
require_once('helpers/utitlity_functions.php'); 
```
这种用法相当原始. 应该更灵活点. 应编写个助手函数包含文件. 例如:
```php
function load_class($class_name)  
{  
 //path to the class file  
 $path = ROOT . '/lib/' . $class_name . '.php');  
 require_once( $path );  
}  
       
load_class('Database');  
load_class('Mail'); 
```
有什么不一样吗? 该代码更具可读性.

將来你可以按需扩展该函数, 如:
```php
function load_class($class_name)  
{  
	//path to the class file  
    $path = ROOT . '/lib/' . $class_name . '.php');  
   
	if (file_exists($path))  
    {  
		require_once( $path );  
    }  
} 
```

还可做得更多:  

为同样文件查找多个目录  

能很容易的改变放置类文件的目录, 无须在代码各处一一修改  

可使用类似的函数加载文件, 如html内容.  



3 为应用保留调试代码
---------------
在开发环境中, 我们打印数据库查询语句, 转存有问题的变量值, 而一旦问题解决, 我们注释或删除它们. 然而更好的做法是保留调试代码.  

在开发环境中, 你可以:  
```php
define('ENVIRONMENT' , 'development');  
   
if(! $db->query( $query )  
{  
   if(ENVIRONMENT == 'development')  
   {  
      echo "$query failed";  
  }  
   else  
    {  
       echo "Database error. Please contact administrator";  
    }  
} 
```
在服务器中, 你只需改一下与置的值即可:
> define('ENVIRONMENT' , 'production');   



4 使用可跨平台的函数执行命令
---------------
system, exec, passthru, shell_exec 这4个函数可用于执行系统命令.   每个的行为都有细微差别. 问题在于, 当在共享主机中, 某些函数可能被选择性的禁用.   大多数新手趋于每次首先检查哪个函数可用, 然而再使用它.  

更好的方案是封成函数一个可跨平台的函数。  

Method to execute a command in the terminal  
Uses :  
1. system  
2. passthru  
3. exec  
4. shell_exec  
```php
function terminal($command)  
{  
    //system  
   if(function_exists('system'))  
   {  
       ob_start();  
       system($command , $return_var);  
       $output = ob_get_contents();  
       ob_end_clean();  
   }  
   //passthru  
   else if(function_exists('passthru'))  
   {  
       ob_start();  
       passthru($command , $return_var);  
       $output = ob_get_contents();  
       ob_end_clean();  
   }  
  
   //exec  
   else if(function_exists('exec'))  
   {  
       exec($command , $output , $return_var);  
       $output = implode("\n" , $output);  
   }  
  
   //shell_exec  
   else if(function_exists('shell_exec'))  
   {  
       $output = shell_exec($command) ;  
   }  
  
   else  
   {  
       $output = 'Command execution not possible on this system';  
       $return_var = 1;  
   }  
  
   return array('output' => $output , 'status' => $return_var);  
}  
   
terminal('ls'); 
```
上面的函数將运行shell命令, 只要有一个系统函数可用, 这保持了代码的一致性. 


5 灵活编写函数
---------------
```php
function add_to_cart($item_id , $qty)  
{  
	$_SESSION['cart']['item_id'] = $qty;  
}  
    
add_to_cart( 'IPHONE3' , 2 ); 
```
使用上面的函数添加单个项目. 而当添加项列表的时候,你要创建另一个函数吗? 不用, 只要稍加留意不同类型的参数, 就会更灵活. 如:
```php
function add_to_cart($item_id , $qty)  
{  
	if (!is_array($item_id)) {  
        $_SESSION['cart']['item_id'] = $qty;  
	} else {  
		foreach($item_id as $i_id => $qty)  
		{  
			$_SESSION['cart']['i_id'] = $qty;  
		}  
	}  
}  

add_to_cart( 'IPHONE3' , 2 );  
add_to_cart( array('IPHONE3' => 2 , 'IPAD' => 5) ); 
```
现在, 同个函数可以处理不同类型的输入参数了. 可以参照上面的例子重构你的多处代码, 使其更智能.



6 有意忽略php关闭标签
---------------
我很想知道为什么这么多关于php建议的博客文章都没提到这点.
```php
<?php 
echo "Hello";  
```
//Now dont close this tag 

这將节约你很多时间. 我们举个例子:

一个 super_class.php 文件
```php
<?php 
class super_class  
{  
    function super_function()  
    {  
        //super code  
    }  
}  
?> 
```
// 闭合标签后面如果加了其它字符 super extra character after the closing tag 

index.php
```php
require_once('super_class.php');  
// 如果在这里 echo an image or pdf , or set the cookies or session data 
```
这样, 你將会得到一个 Headers already send error. 为什么? 因为 “super extra character” 已经被输出了. 现在你得开始调试啦. 这会花费大量时间寻找 super extra 的位置.

因此, 养成省略关闭符的习惯：
```php
<?php 
class super_class  
{  
    function super_function()  
    {  
        //super code  
    }  
}  
//No closing tag 
```
这会更好。



7. 在某地方收集所有输入, 一次输出给浏览器这称为输出缓冲, 假如说你已在不同的函数输出内容:
---------------
```php
function print_header()  
{  
    echo "<div id='header'>Site Log and Login links</div>";  
}  
   
function print_footer()  
{  
    echo "<div id='footer'>Site was made by me</div>";  
}  
   
print_header();  
for($i = 0 ; $i < 100; $i++)  
{  
    echo "I is : $i <br />';  
}  
print_footer(); 
```

替代方案, 在某地方集中收集输出. 你可以存储在函数的局部变量中, 也可以使用ob_start和ob_end_clean. 如下:
```php
function print_header()  
{  
    $o = "<div id='header'>Site Log and Login links</div>";  
    return $o;  
}  
   
function print_footer()  
{  
    $o = "<div id='footer'>Site was made by me</div>";  
    return $o;  
}  
   
echo print_header();  
for($i = 0 ; $i < 100; $i++)  
{  
    echo "I is : $i <br />';  
}  
echo print_footer(); 
```
为什么需要输出缓冲:  

>> 可以在发送给浏览器前更改输出. 如 str_replaces 函数或可能是 preg_replaces 或添加些监控/调试的html内容.  

>> 输出给浏览器的同时又做php的处理很糟糕. 你应该看到过有些站点的侧边栏或中间出现错误信息. 知道为什么会发生吗? 因为处理和输出混合了.


8. 发送正确的mime类型头信息, 如果输出非html内容的话。
---------------
输出一些xml.
```php
$xml = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';  
$xml = "<response> 
  <code>0</code> 
</response>";  
   
// Send xml data  
echo $xml; 
```
工作得不错. 但需要一些改进.
```php
$xml = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';  
$xml = "<response> 
  <code>0</code> 
</response>";  
   
//Send xml data  
header("content-type: text/xml");  
echo $xml; 
```
注意header行. 该行告知浏览器发送的是xml类型的内容. 所以浏览器能正确的处理.   很多的javascript库也依赖头信息.  

类似的有 javascript , css, jpg image, png image:  

JavaScript
```
header("content-type: application/x-javascript");  
echo "var a = 10"; 
```

CSS
```
header("content-type: text/css");  
echo "#div id { background:#000; }"; 
```


9. 为mysql连接设置正确的字符编码曾经遇到过在mysql表中设置了unicode/utf-8编码, phpadmin也能正确显示, 但当你获取内容并在页面输出的时候,会出现乱码. 这里的问题出在mysql连接的字符编码.
---------------
```php
// Attempt to connect to database  
$c = mysqli_connect($this->host , $this->username, $this->password);  
   
//Check connection validity  
if (!$c)&nbsp;  
{  
    die ("Could not connect to the database host: <br />". mysqli_connect_error());  
}  
   
//Set the character set of the connection  
if(!mysqli_set_charset ( $c , 'UTF8' ))  
{  
    die('mysqli_set_charset() failed');  
} 
```
一旦连接数据库, 最好设置连接的 characterset. 你的应用如果要支持多语言, 这么做是必须的.  


10. 使用 htmlentities 设置正确的编码选项php5.4前, 字符的默认编码是ISO-8859-1, 不能直接输出如À â等.
---------------
> $value = htmlentities($this->value , ENT_QUOTES , CHARSET);  

php5.4以后, 默认编码为UTF-8, 这將解决很多问题. 但如果你的应用是多语言的, 仍然要留意编码问题,.  


11. 不要在应用中使用gzip压缩输出, 让apache处理考虑过使用 ob_gzhandler 吗? 不要那样做. 毫无意义.   
php只应用来编写应用. 不应操心服务器和浏览器的数据传输优化问题.  
---------------
使用apache的mod_gzip/mod_deflate 模块压缩内容。  



12. 使用json_encode输出动态javascript内容时常会用php输出动态javascript内容:
---------------
```php
$images = array(  
	'myself.png' , 'friends.png' , 'colleagues.png'  
);  

$js_code = '';  
foreach($images as $image)  
{  
	$js_code .= "'$image' ,";  
}  
$js_code = 'var images = [' . $js_code . ']; ';  
echo $js_code;  

//Output is var images = ['myself.png' ,'friends.png' ,'colleagues.png' ,]; 
```

更聪明的做法, 使用 json_encode:
```php
$images = array(  
	'myself.png' , 'friends.png' , 'colleagues.png'  
);  
$js_code = 'var images = ' . json_encode($images);  
echo $js_code;  

//Output is : var images = ["myself.png","friends.png","colleagues.png"] 
优雅乎?
```


13. 写文件前, 检查目录写权限写或保存文件前, 确保目录是可写的, 假如不可写, 输出错误信息. 这会节约你很多调试时间. linux系统中, 需要处理权限, 目录权限不当会导致很多很多的问题, 文件也有可能无法读取等等.
---------------
确保你的应用足够智能, 输出某些重要信息.
```php
$contents = "All the content";  
$file_path = "/var/www/project/content.txt";  

file_put_contents($file_path , $contents); 
```
这大体上正确. 但有些间接的问题. file_put_contents 可能会由于几个原因失败:

>> 父目录不存在
>> 目录存在, 但不可写
>> 文件被写锁住?

所以写文件前做明确的检查更好.
```php
$contents = "All the content";  
$dir = '/var/www/project';  
$file_path = $dir . "/content.txt";  

if(is_writable($dir))  
{  
    file_put_contents($file_path , $contents);  
}  
else  
{  
    die("Directory $dir is not writable, or does not exist. Please check");  
} 
```
这么做后, 你会得到一个文件在何处写及为什么失败的明确信息.


14. 更改应用创建的文件权限在 linux环境中, 权限问题可能会浪费你很多时间. 从今往后, 无论何时, 当你创建一些文件后, 确保使用chmod设置正确权限. 否则的话, 可能文件先是由"php"用户创建, 但你用其它的用户登录工作, 系统將会拒绝访问或打开文件, 你不得不奋力获取root权限, 更改文件的权限等等.
---------------
// Read and write for owner, read for everybody else  
chmod("/somedir/somefile", 0644);    
   
// Everything for owner, read and execute for others  
chmod("/somedir/somefile", 0755);   



15. 不要依赖submit按钮值来检查表单提交行为
---------------
```php
if($_POST['submit'] == 'Save')  
{  
	//Save the things  
} 
```

上面大多数情况正确, 除了应用是多语言的. 'Save' 可能代表其它含义. 你怎么区分它们呢. 因此, 不要依赖于submit按钮的值.
```php
if( $_SERVER['REQUEST_METHOD'] == 'POST' and isset($_POST['submit']) )  
{  
    //Save the things  
} 
```
现在你从submit按钮值中解脱出来了.  



16. 为函数内总具有相同值的变量定义成静态变量
---------------
```php
//Delay for some time  
function delay()  
{  
    $sync_delay = get_option('sync_delay');  
   
    echo "<br />Delaying for $sync_delay seconds...";  
    sleep($sync_delay);  
    echo "Done <br />";  
} 
```

用静态变量取代:
```php
//Delay for some time
function delay() {
    static $sync_delay = null;

    if ($sync_delay == null) {
        $sync_delay = get_option('sync_delay');
    }

    echo "Delaying for $sync_delay seconds...";
    sleep($sync_delay);
    echo "Done ";
}
```


17. 不要直接使用 $_SESSION 变量
---------------
某些简单例子:
```php
$_SESSION['username'] = $username;  
 
$username = $_SESSION['username'];  
```
这会导致某些问题. 如果在同个域名中运行了多个应用, session 变量可能会冲突. 两个不同的应用可能使用同一个session key. 例如, 一个前端门户, 和一个后台管理系统使用同一域名.

从现在开始, 使用应用相关的key和一个包装函数:
```php
define('APP_ID' , 'abc_corp_ecommerce');  

//Function to get a session variable  
function session_get($key)  
{  
    $k = APP_ID . '.' . $key;  
   
    if(isset($_SESSION[$k]))  
    {  
        return $_SESSION[$k];  
    }  
    
    return false;  
}  

//Function set the session variable  
function session_set($key , $value)  
{  
    $k = APP_ID . '.' . $key;  
    $_SESSION[$k] = $value;  
   
    return true;  
} 
```


18. 將工具函数封装到类中
---------------
假如你在某文件中定义了很多工具函数:
```php
01  function utility_a()  
02  {  
03      //This function does a utility thing like string processing  
04  }  
05     
06  function utility_b()  
07  {  
08      //This function does nother utility thing like database processing  
09  }  
10     
11  function utility_c()  
12  {  
13      //This function is ...  
14  }
``` 
这些函数的使用分散到应用各处. 你可能想將他们封装到某个类中:
```php
01  class Utility  
02  {  
03      public static function utility_a()  
04      {  
05     
06      }  
07     
08      public static function utility_b()  
09      {  
10     
11      }  
12     
13      public static function utility_c()  
14      {  
15     
16      }  
17  }  
18     
19  //and call them as  

$a = Utility::utility_a();  
$b = Utility::utility_b(); 
```
显而易见的好处是, 如果php内建有同名的函数, 这样可以避免冲突.

另一种看法是, 你可以在同个应用中为同个类维护多个版本, 而不导致冲突. 这是封装的基本好处，无它。



19. Bunch of silly tips
---------------
>>使用echo取代print

>>使用str_replace取代preg_replace, 除非你绝对需要

>>不要使用 short tag

>>简单字符串用单引号取代双引号

>>head重定向后记得使用exit

>>不要在循环中调用函数

>>isset比strlen快

>>始中如一的格式化代码

>>不要删除循环或者if-else的括号

不要这样写代码:
```php
if($a == true) $a_count++;
```
这绝对WASTE.

写成:
```php
if($a == true)  
{  
    $a_count++;  
}
```
不要尝试省略一些语法来缩短代码. 而是让你的逻辑简短.

>>使用有高亮语法显示的文本编辑器. 高亮语法能让你减少错误.


20. 使用array_map快速处理数组
---------------
比如说你想 trim 数组中的所有元素. 新手可能会:
```php
foreach($arr as $c => $v)  
{  
    $arr[$c] = trim($v);  
} 
```
但使用 array_map 更简单:
> $arr = array_map('trim' , $arr);   

这会为$arr数组的每个元素都申请调用trim. 另一个类似的函数是 array_walk. 请查阅文档学习更多技巧.


21. 使用php filter验证数据
---------------
你肯定曾使用过正则表达式验证 email , ip地址等. 是的,每个人都这么使用. 现在, 我们想做不同的尝试, 称为filter.  

php的filter扩展提供了简单的方式验证和检查输入.  



22. 强制类型检查
---------------
```php
$amount = intval( $_GET['amount'] );  
$rate = (int) $_GET['rate'];  
```
这是个好习惯。



23. 如果需要,使用profiler如xdebug
---------------
如果你使用php开发大型的应用, php承担了很多运算量, 速度会是一个很重要的指标. 使用profile帮助优化代码. 可使用  

xdebug和webgrid.  



24. 小心处理大数组
---------------
对于大的数组和字符串, 必须小心处理. 常见错误是发生数组拷贝导致内存溢出,抛出Fatal Error of Memory size 信息:  
```php
$db_records_in_array_format; 
// This is a big array holding 1000 rows from a table each having 20 columns , every row is atleast 100 bytes , so total 1000 * 20 * 100 = 2MB   
  
$cc = $db_records_in_array_format; //2MB more  
```
some_function($cc); //Another 2MB ? 
当导入或导出csv文件时, 常常会这么做.  

不要认为上面的代码会经常因内存限制导致脚本崩溃. 对于小的变量是没问题的, 但处理大数组的时候就必须避免.

确保通过引用传递, 或存储在类变量中:
```php
$a = get_large_array();  
pass_to_function(&$a);  
```
这么做后, 向函数传递变量引用(而不是拷贝数组). 查看文档.
```php
class A  
{  
    function first()  
    {  
        $this->a = get_large_array();  
        $this->pass_to_function();  
    }  
   
    function pass_to_function()  
    {  
        //process $this->a  
    }  
} 
```
尽快的 unset 它们, 让内存得以释放,减轻脚本负担.



25. 由始至终使用单一数据库连接
---------------
确保你的脚本由始至终都使用单一的数据库连接. 在开始处正确的打开连接, 使用它直到结束, 最后关闭它. 不要像下面这样在函数中打开连接:  
```php
function add_to_cart()  
{  
    $db = new Database();  
    $db->query("INSERT INTO cart .....");  
}  
   
function empty_cart()  
{  
    $db = new Database();  
    $db->query("DELETE FROM cart .....");  
} 
```
使用多个连接是个糟糕的, 它们会拖慢应用, 因为创建连接需要时间和占用内存.  

特定情况使用单例模式, 如数据库连接.  



26. 避免直接写SQL，抽象之
---------------
不厌其烦的写了太多如下的语句:  
```php
$query = "INSERT INTO users(name , email , address , phone) VALUES('$name' , '$email' , '$address' , '$phone')";  
$db->query($query); //call to mysqli_query()
```
这不是个建壮的方案. 它有些缺点:

>>每次都手动转义值  

>>验证查询是否正确  

>>查询的错误会花很长时间识别(除非每次都用if-else检查)  

>>很难维护复杂的查询  

因此使用函数封装:
```php
function insert_record($table_name , $data)  
{  
    foreach($data as $key => $value)  
    {  
	    //mysqli_real_escape_string  
        $data[$key] = $db->mres($value);  
    }  
   
    $fields = implode(',' , array_keys($data));  
    $values = "'" . implode("','" , array_values($data)) . "'";  
   
    //Final query  
    $query = "INSERT INTO {$table}($fields) VALUES($values)";  
   
    return $db->query($query);  
}  

$data = array('name' => $name , 'email' => $email  , 'address' => $address , 'phone' => $phone);  

insert_record('users' , $data);
```
看到了吗? 这样会更易读和扩展. record_data 函数小心的处理了转义.

最大的优点是数据被预处理为一个数组, 任何语法错误都会被捕获.

该函数应该定义在某个database类中, 你可以像 $db->insert_record这样调用.

查看本文, 看看怎样让你处理数据库更容易.

类似的也可以编写update,select,delete方法. 试试吧.



27. 將数据库生成的内容缓存到静态文件中
---------------
如果所有的内容都是从数据库获取的, 它们应该被缓存. 一旦生成了, 就將它们保存在临时文件中. 下次请求该页面时, 可直接从缓存中取, 不用再查数据库.

好处:

>>节约php处理页面的时间, 执行更快

>>更少的数据库查询意味着更少的mysql连接开销



28. 在数据库中保存session
---------------
基于文件的session策略会有很多限制. 使用基于文件的session不能扩展到集群中, 因为session保存在单个服务器中. 但数据库可被多个服务器访问, 这样就可以解决问题.

在数据库中保存session数据, 还有更多好处:

>>处理username重复登录问题. 同个username不能在两个地方同时登录.

>>能更准备的查询在线用户状态.



29. 避免使用全局变量
---------------
>>使用 defines/constants

>>使用函数获取值

>>使用类并通过$this访问



30. 在head中使用base标签
---------------
没听说过? 请看下面:
```
<head> 
<base href="http://www.domain.com/store/"> 
</head> 
<body> 
<img src="happy.jpg" /> 
</body> 
</html> 
```
base 标签非常有用. 假设你的应用分成几个子目录, 它们都要包括相同的导航菜单.

www.domain.com/store/home.php

www.domain.com/store/products/ipad.php

在首页中, 可以写:
```
<a href="home.php">Home</a> 
<a href="products/ipad.php">Ipad</a> 
```
但在你的ipad.php不得不写成:
```
<a href="../home.php">Home</a> 
<a href="ipad.php">Ipad</a>
```
因为目录不一样. 有这么多不同版本的导航菜单要维护, 很糟糕啊.

因此, 请使用base标签.
```
<html>
<head> 
  <base href="http://www.domain.com/store/"> 
</head> 
<body> 
  <a href="home.php">Home</a> 
  <a href="products/ipad.php">Ipad</a> 
</body> 
</html>
```
现在, 这段代码放在应用的各个目录文件中行为都一致.



31. 永远不要將error_reporting设为0
---------------
关闭不相要的错误报告. E_FATAL 错误是很重要的.
```
ini_set('display_errors', 1);   
error_reporting(~E_WARNING & ~E_NOTICE & ~E_STRICT);
```


32. 注意平台体系结构
---------------
integer在32位和64位体系结构中长度是不同的. 因此某些函数如 strtotime 的行为会不同.

在64位的机器中, 你会看到如下的输出.
```
$ php -a  
Interactive shell  
   
php > echo strtotime("0000-00-00 00:00:00");  
-62170005200  

php > echo strtotime('1000-01-30');  
-30607739600  

php > echo strtotime('2100-01-30');  
4104930600
```
但在32位机器中, 它们將是bool(false). 



33. 不要过分依赖set_time_limit
---------------
如果你想限制最小时间, 可以使用下面的脚本:

> set_time_limit(30);   
      
//Rest of the code
高枕无忧吗? 注意任何外部的执行, 如系统调用,socket操作, 数据库操作等, 就不在set_time_limits的控制之下.

因此, 就算数据库花费了很多时间查询, 脚本也不会停止执行. 视情况而定.



34. 使用扩展库
---------------
一些例子:

>>mPDF -- 能通过html生成pdf文档

>>PHPExcel -- 读写excel

>>PhpMailer -- 轻松处理发送包含附近的邮件

>>pChart -- 使用php生成报表

使用开源库完成复杂任务, 如生成pdf, ms-excel文件, 报表等.



35. 使用MVC框架
---------------
是时候使用像 codeigniter 这样的MVC框架了. MVC框架并不强迫你写面向对象的代码. 它们仅將php代码与html分离.  

>>明确区分php和html代码. 在团队协作中有好处, 设计师和程序员可以同时工作.

>>面向对象设计的函数能让你更容易维护

>>内建函数完成了很多工作, 你不需要重复编写

>>开发大的应用是必须的

>>很多建议, 技巧和hack已被框架实现了



36. 时常看看 phpbench
---------------
phpbench 提供了些php基本操作的基准测试结果, 它展示了一些徽小的语法变化是怎样导致巨大差异的.  

查看php站点的评论, 有问题到IRC提问, 时常阅读开源代码, 使用Linux开发。





