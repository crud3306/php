编程题
-----------

遍历目录
-----------
```php
# 主要用到 opendir(string dir)， readdir(string dir)， closedir(string dir)
# 递归调用子级目录

# ----------------
# 遍历返回
# ----------------
function listDir($dir){
  $arr = [];

  if (is_dir($dir) && $handle = opendir($dir)) {
    while (($file= readdir($handle)) !== false){
      if ($file == "." || $file == "..") {
        continue;
      }

      $curr_file = $dir.DIRECTORY_SEPARATOR.$file;

      if (is_dir($curr_file)) {
        // 当前目录
        // $arr[] = $curr_file;
        // 递归取下级目录
        // $arr[] = listDir($curr_file);
        $arr[$curr_file] = listDir($curr_file);

      } else{
        $arr[] = $curr_file;
      }
    }

    closedir($handle);
  }

  return $arr;
}
$dir = '/data/my_web/test';
var_dump(listDir($dir));
```
    
PHP 不使用第三个变量实现交换两个变量的值
-----------
```php
//方法一
list($b,$a) = [$a,$b];
var_dump($a, $b);

// 方法二
$a.= $b;
$b = str_replace($b, "", $a);
$a = str_replace($b, "", $a);
```
  
写一个方法获取文件的扩展名
-----------
```php
function get_extension($file) {
	// 方法一   
	$path_info = pathinfo($file);
	return $path_info['extension'];

    // 方法二   
    return  substr(strrchr($file,'.'), 1);   

    // 方法三   
    return  end(explode('.', $file));
}
echo get_extension('fangzhigang.png'); //png
```
  
  
编写一段用最小代价实现将字符串完全反序, 如：将“1234567890” 转换成 “0987654321”. 不要使用内置函数strrev(str)。
-----------
function rev_string($str)
{
	$o = '';
	$i = 0;
	while(isset($str[$i])) {
	    $o = $str[$i++].$o;
	}
}
$s = '1234567890';
var_dump(rev_string($s));


请用递归实现一个阶乘求值算法 F(n): n=5;F(n)=5!=5*4*3*2*1=120
-----------
```php
function F($n){    
	if ($n == 1) {
		return 1;      
	} else {         
		return $n * F($n-1);      
	}
}
var_dump(F(5));
```

将字符长fang-zhi-gang 转化为驼峰法的形式：FangZhiGang
-----------
```php
// 方法1
function up_words($str)
{
	$str = ucwords(str_replace('-', ' ', $str));
	return str_replace(' ', '', $str);
}
var_dump(up_words('fang-zhi-gang'));

// 方法2
function up_words2($str)
{
	$arr = array_map(function($v){
		return ucfirst($v);
	}, explode('-', $str));
	return join($arr, '');
}
var_dump(up_words2('fang-zhi-gang'));
```
  
获取指定月的天数
-----------
```php
// date_default_timezone_set('Asia/Shanghai'); // 使用前需设置好时区
function month_days_count($year, $month){
	echo date("t", strtotime($year."-".$month."-1"));
}
month_days_count(2018, 2);
```

session 存到redis中  
-----------
默认情况下php.ini中session.save_handler = files，也就是session是以文件形 式存储的。
如果想更改为数据库或其它存储方式，那么需要更改设置，让 session.save_handler = user。
除了在php.ini中配置外，还可以在PHP页面中单独配置，用
ini_set('session.save_handler, 'user')来设置session的存储方式，设置为用户自定义存储方式。但需注意：需要session.auto_start = 0, 不然设置ini_set(‘session.save_handler’, ‘user’);会引起报错。  
  
然后session_set_save_handler()函数。  
该函数是设置用户级别的session保存过程的函数。该函数有6个参数，这6个参数其实是6个自定义函数的名称，分别代表对session的开启，关闭，读，写 ，销毁，gc（垃圾回收）。  
示例代码如下：  
function open () { }  
function close() { }  
function read () { }  
function write () {}  
function destroy () {}  
function gc () {}  
session_set_save_handler ("open", "close", "read", "write", "destroy", "gc");
session_start();  
现在你就可以象往常一样地使用session了。  
```php
class SessionManager{

    private $redis;
    private $sessionExpireTime=30;//redis，session的过期时间为30s

    public function __construct(){
        $this->redis = new Redis();//创建phpredis实例
        $this->redis->connect('127.0.0.1', 6379);//连接redis
        // $this->redis->auth("107lab"); //授权

        $session_name = ini_get('session.name');
        // fix for swf ie.swfupload
        if (!empty($_POST[$session_name])) {
            self::$flash = true;
            session_id($_POST[$session_name]);
        }

        session_set_save_handler(
            array($this,"open"),
            array($this,"close"),
            array($this,"read"),
            array($this,"write"),
            array($this,"destroy"),
            array($this,"gc")
        );

        session_start();
    }

    public function open($path,$name){
        return true;
    }

    public function close(){
        return true;
    }

    public function read($id){
        $value = $this->redis->get($id);//获取redis中的指定记录
        if($value){
            return $value;
        }else{
            return '';
        }
    }

    public function write($id,$data){
    	// 以session ID为键，存储
        if($this->redis->set($id,$data)){
        	// 设置redis中数据的过期时间，即session的过期时间
            $this->redis->expire($id, $this->sessionExpireTime);
            return true;
        }

        return false;
    }

    public function destroy($id){
        if($this->redis->delete($id)){//删除redis中的指定记录
            return true;
        }
        return false;
    }

    public function gc($maxlifetime){
        return true;
    }

    public function __destruct(){
        session_write_close();
    }

}

// 使用
// ------------------
// a.php
include('SessionManager.php');
new SessionManager();
$_SESSION['username'] = 'captain';


// b.php
include('SessionManager.php');
new SessionManager();
echo $_SESSION['username'];
```
  
  
      
常用正写一个email的正则表达式  
-----------
中文：/^[\u4E00-\u9FA5]+$/  
手机号码：/^(86)?0?1\d{10}$/  
  
EMAIL：  
/^[\w-]+[\w-.]?@[\w-]+\.{1}[A-Za-z]{2,5}$/  
/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/  
  
密码（安全级别中）：  
/^(\d+[A-Za-z]\w*|[A-Za-z]+\d\w*)$/  
  
密码（安全级别高）：  
/^(\d+[a-zA-Z~!@#$%^&(){}][\w~!@#$%^&(){}]*|[a-zA-Z~!@#$%^&(){}]+\d[\w~!@#$%^&(){}]*)$/  
  

x
-----------

