编程题
-----------


写一个函数得到header头信息
-----------
```php
function getHeader()
{
    $headers = [];
    foreach ($SERVER as $key => $value) {
        if(strstr($key, 'HTTP')) {
            $newk = ucwords(strtolower(str_replace('_', '-', substr($key, 5))));
            $headers[$newk] = $value;
        }
    }

    return $headers;
}

var_dump($headers);
```
  
  
遍历目录
-----------
```php
# 主要用到 opendir(string dir)， readdir(string dir)， closedir(string dir)
# 递归调用子级目录

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

冒泡排序
-----------
```php
function bubbleSort($arr)
{
     $len = count($arr);

     //该层循环控制 需要冒泡的轮数
     for ($i=1; $i<$len; $i++) {

          $has_change = false;

          //该层循环用来控制每轮 冒出一个数 需要比较的次数
          for ($k=0; $k<$len-$i; $k++) {
               if($arr[$k] > $arr[$k+1]) {
                    $tmp = $arr[$k+1]; // 声明一个临时变量
                    $arr[$k+1] = $arr[$k];
                    $arr[$k] = $tmp;

                    $has_change = true;
               }
          }

          if (!$has_change) {
            break;
          }
     }
     return $arr;
}
$arr=array(1,43,54,62,21,66,32,78,36,76,39);
var_dump(bubbleSort($arr));
```

   
写个函数用来对二维数组排序
-----------
```php
function array_sort_by_any_column($list, $column_id, $order_type){
    $array_temp = [];
    foreach ($list as $key=>$value) {
        $array_temp[$key] = $value[$column_id];
    }

    if ($order_type === "ASC"){ //顺序
        asort($array_temp);
    } else {
        arsort($array_temp);
    }

    var_dump($array_temp);

    $result = [];
    foreach($array_temp as $key=>$value){
        $result[] = $list[$key];
    }
    return $result;
}

$arr = array(
    array('num'=>5, 'value'=>6),
    array('num'=>2, 'value'=>39),
    array('num'=>36, 'value'=>29)
);
$sortarr = array_sort_by_any_column($arr, 'num', 'ASC');
print_r($sortarr);

// 另一种方法：
/**
 * 二维数组按照子级数组中指定的某个值进行排序
 * @param array $list        二维数组
 * @param string $order_key  指定的某个值
 * @return array
 */
function arraySortByKey($list, $order_key)
{
    $tmp = array();
    foreach ($list as &$ma) {
        $tmp[] = &$ma[$order_key];
    }
    array_multisort($tmp, $list);

    return $list;
}
```
    
PHP 不使用第三个变量实现交换两个变量的值
-----------
```php
//方法一  
list($b,$a) = [$a,$b];  
var_dump($a, $b);

//方法二  
$a = [$a,$b];  
$b = $a[0];
$a = $a[1];

//方法三  
$a .= $b;
$b = strlen($b );
$b = substr($a, 0, (strlen($a) – $b ) );
$a = substr($a, strlen($b) );

//方法四  
$a .= ','.$b;
$a = explode(',', $a);
$b = $a[0];
$a = $a[1];

// 方法五
$a.= $b;
$b = str_replace($b, "", $a);
$a = str_replace($b, "", $a);
```
  
写一个方法获取文件的扩展名
-----------
```php
function extname($path) {
    $path_info = pathinfo($path);
    return $path_info['extension'];
}
var_dump(extname($path));

function extname1($path) {
    return substr(strrchr($path, '.'), 1);
}
var_dump(extname1($path));

function extname2($path) {
    $position = strrpos($path, '.');
    return substr($path, $position+1);
}
var_dump(extname2($path));

function extname3($path) {
    $arr = explode('.', $path);
    return $arr[count($arr) - 1];
}
var_dump(extname3($path));

function extname4($path) {
    return preg_replace('/^[^.]+\.([\w]+)$/', '${1}', basename($path));
}
var_dump(extname4($path));

function extname5($path) {
    preg_match_all('/[\w\/\:\-]+\.([\w]+)$/', $path, $out);
    // var_dump($out);
    return $out[1][0];
}
var_dump(extname5($path));
// 输出均为：php
```


写一个函数，尽可能高效的从一个标准url中取出扩展名  
-----------
```php
$arr = parse_url('http://www.sina.com.cn/abc/de/fg.php?id=1');
$result = pathinfo($arr['path']);
var_dump($result['extension']);
```
  
  
编写一段用最小代价实现将字符串完全反序, 如：将“1234567890” 转换成 “0987654321”. 不要使用内置函数strrev(str)。
-----------
```php
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
```


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

写一个递归函数完成以下功能：向函数中传一个多维数组，对数组中所有的值做判断
，如果值是’number’则设置该值为0？(提示：该题考的是递归的应用，因为传入的数组不确定是多少维的，所以需要递归判断)
---------
function recursive_array($arr) {
    if(is_array($arr)) {
        foreach($arr as $key=>$value) {
            if(is_array($value)) {
               $arr[$key] = recursive_array($value);
            } else {
                if($value=='number') {
                    $arr[$key] = '0'; 
                }
            }
        }
    } else {
        if($value == 'number') {
            return 0; 
        } else {}
    }

    return $arr;
} 

  
取两个文件的相对路径
-----------
/** 计算path1 相对于 path2 的路径，即在path2引用paht1的相对路径
* @param  String $path1
* @param  String $path2
* @return String
*/
function getRelativePath($path1, $path2){
    $arr1 = explode('/', $path1);
    $arr2 = explode('/', $path2);
    // 获取相同路径的部分
    $intersection = array_intersect_assoc($arr1, $arr2);
    $depth = 0;
    var_dump($intersection);
    for($i=0,$len=count($intersection); $i<$len; $i++){
        $depth = $i;
        if(!isset($intersection[$i])){
            break;
        }
    }
    // 前面全部匹配
    if($i == count($intersection)){
        $depth++;
    }
    // 将path2的/ 转为 ../，path1获取后面的部分，然后合拼
    // var_dump($depth);
    // 计算前缀
    if (count($arr2)-$depth-1 > 0) {
        $prefix = array_fill(0, count($arr2)-$depth-1, '..');
    } else {
        $prefix = array('.');
    }
    $tmp = array_merge($prefix, array_slice($arr1, $depth));
    $relativePath = implode('/', $tmp);

    return $relativePath;
}

$path1 = '/home/web/lib/img/cache.php';  
$path2 = '/home/web/abc/img/show.php';  
echo getRelativePath($path1, $path2).PHP_EOL; 
// 输出为 ../../lib/img/cache.php
```


写个函数来解决多线程同时读写一个文件的问题。
------------
```php
$fp = fopen("/tmp/lock.txt", "w+");
if (flock($fp, LOCK_EX)) { // 进行排它型锁定
    fwrite($fp,"Write something here\n");
    flock($fp, LOCK_UN);// 释放锁定

} else {
    echo "Couldn't lock the file !";
}
fclose($fp);
```


php写一个双向队列（腾讯）
------------
```php
<?php
class Deque{
    private $queue = array();
    
    function addFirst($item){//头入队
        return array_unshift($this->queue,$item);
    }
    function addLast($item){//尾入队
        return array_push($this->queue,$item);
    }
    function removeFirst(){//头出队
        return array_shift($this->queue);
    }
    function removeLast(){//尾出队
        return array_pop($this->queue);
    }
    function show(){//显示
        echo implode(" ",$this->queue);
    }
    function clear(){//清空
        unset($this->queue);
    }
    function getLength(){
        return count($this->queue);
    }
}
$q = new Deque();
$q->addFirst(1);
$q->addLast(5);
$q->removeFirst();
$q->removeLast();
$q->addFirst(2);
$q->addLast(4);
$q->show();
```

写一个发红包程序
----------
思路：总金额，红包数，每个红包最低金额
```php
 
function sendRedpack($total, $num, $min)
{
    for ($i=1;$i<$num;$i++)  
    {  
      // $safe_total=($total - ($num-$i)*$min) / ($num-$i);//随机安全上限  
      $safe_total=($total - $min) / ($num-$i);//随机安全上限  

      $money=mt_rand($min*100, $safe_total*100) / 100;  
      $total=$total-$money; 
       
      echo '第'.$i.'个红包：'.$money.' 元，余额：'.$total.' 元 '.PHP_EOL;  
    }
    echo '第'.$num.'个红包：'.$total.' 元，余额：0 元'.PHP_EOL;    
}

$total=10; //红包总金额  
$num=10; // 分成10个红包，支持10人随机领取  
$min=0.01; //每个人最少能收到0.01元  
sendRedpack($total, $num, $min);
```
  
约瑟夫环:
一群猴子排成一圈，按1,2,…,n依次编号。然后从第1只开始数，数到第m只,把它踢出圈，从它后面再开始数， 再数到第m只，在把它踢出去…，如此不停的进行下去， 直到最后只剩下一只猴子为止，那只猴子就叫做大王。要求编程模拟此过程，输入m、n, 输出最后那个大王的编号。
---------------
```php
$n = 9;  // 总数
$m = 4;  // 数到第几被踢出

// 方法1
function yusehuan($n, $m)
{
    $s = 0;
    for($i=2; $i<=$n; $i++) {
        $s = ($s+$m)%$i;
        // var_dump($i, $s);
    }

    return $s+1;    
}
echo yusehuan($n, $m).PHP_EOL;

// 方法2
function monkey($n ,$m){
    $arr = range(1,$n);     //构造数组  array(1,2,3,4,5,6,7,8);
    $i = 0;                 //设置数组指针
    while(count($arr)>1){
        //遍历数组，判断当前猴子是否为出局序号，如果是则出局，否则放到数组最后
        if(($i+1) % $m == 0) {
            unset($arr[$i]);
            
        } else {
            //array_push() 函数向第一个参数的数组尾部添加一个或多个元素（入栈），然后返回新数组的长度。
            array_push($arr ,$arr[$i]); //本轮非出局猴子放数组尾部
            unset($arr[$i]);   //删除
        }
        $i++;

        // var_dump($i, $arr);
    }
    return array_pop($arr);
}
print_r(monkey($n, $m));

// 方法3
function killMonkey($monkeys , $m , $current = 0){
    $number = count($monkeys);
    $num = 1;

    if (count($monkeys) == 1){
        // echo $monkeys[0]."成为猴王了";
        return $monkeys[0];
    }
    
    while($num++ < $m){
        $current = ++$current%$number;
    }
    // echo $monkeys[$current]."的猴子被踢掉了".PHP_EOL;
    array_splice($monkeys , $current , 1);
    return killMonkey($monkeys , $m , $current);
}

$monkeys = range(1, $n); //monkeys的编号
var_dump(killMonkey($monkeys , $m));
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
  
匹配中文字符的正则表达式： [\u4e00-\u9fa5]  
匹配双字节字符(包括汉字在内)：[^\x00-\xff]  
匹配空行的正则表达式：\n[\s| ]\r  
匹配HTML标记的正则表达式：/<(.)>.</\1>|<(.) />/  
匹配首尾空格的正则表达式：(^\s)|(\s$)  
匹配Email地址的正则表达式：\w+([-+.]\w+)@\w+([-.]\w+).\w+([-.]\w+)*  
匹配网址URL的正则表达式：^[a-zA-z]+://(\w+(-\w+))(\.(\w+(-\w+)))(\?\S)?$  
匹配帐号是否合法(字母开头，允许5-16字节，允许字母数字下划线)：^[a-zA-Z][a-zA-Z0-9_]{4,15}$  
匹配国内电话号码：(\d{3}-|\d{4}-)?(\d{8}|\d{7})?  
匹配腾讯QQ号：^[1-9][1-9][0-9]$  
    

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
  

x
-----------

