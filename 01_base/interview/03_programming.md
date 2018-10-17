编程题
===========


写一个函数得到header头信息
-----------
```php
function getHeader()
{
    $headers = [];
    foreach ($SERVER as $key => $value) {
        if (strstr($key, 'HTTP')) {
            $newk = ucwords(strtolower(str_replace('_', '-', substr($key, 5))));
            $headers[$newk] = $value;
        }
    }

    return $headers;
}

var_dump($headers);
```

PHP数字金额转大小格式，同时说明思路
------------
```php
// 简单的数字转大写
function daxie($num)
{
    $len_num = strlen($num);
    if(!is_numeric($num) || $len_num < 0){
        return '';
    }

    $da_num = array('零','一','二','三','四','五','六','七','八','九');
    $return = '';

    for($i=0; $i<$len_num; $i++){
        $return .= $da_num[substr($num, $i, 1)];
    }

    return $return;
}

// PHP数字金额转大写格式
function floatohz($value){
    $result = '';
    $v_a = array('分','角','零','块','十',',百','千','万','十','百','千','亿');
    $v_b = array('零','一','二','三','四','五','六','七','八','九','十');
    $v_c = array();
    $value = (string)$value;
    $value = sprintf("%0.2f",$value);
    $len = strlen($value);

    for($i=$len;$i>=0;$i--){
       $val=$value[$i];//$VALUE 不是数组
       if($val!='.'){
           if($val!='0')
              $v_c[]=$v_b[$val].$v_a[$len-$i-1];
       }
    }

    $v_c=array_reverse($v_c);
    foreach($v_c as $val){
       $result.=$val;
    }
    unset($v_a);unset($v_b);unset($v_c);

    return $result;
}
 
//  $value='45123056.78';
$value='23058.54';
print floatohz($value);

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


首先来画个菱形玩玩，思路：多少行for一次，然后每次循环在里面画空格和星号。
-----------
```
// $n 为每个边的*号数量
function lingxing($n) {
    // 上半部分
    for ($i=$n-1; $i>0; $i--){
        for ($j=$i; $j>0;$j--){
            echo " ";
        }

        $k=$n-$i;
        for ($l=$k; $l>0; $l--){
            echo "* ";
        }
        echo PHP_EOL;
    }
    
    // 下半部分
    for ($i=$n; $i>0; $i--){
        for($j=$n-$i;$j>0;$j--){
            echo " ";
        }

        $k=$i;
        for ($l=$k;$l>0;$l--){
            echo "* ";
        }
        echo PHP_EOL;
    }
}


lingxing(5);

输出：
    *
   * *
  * * *
 * * * *
* * * * *
 * * * *
  * * *
   * *
    *
```

杨辉三角，用php写
-----------
杨辉三角：每行端点与结尾的数为1, 除行首与行尾外 每个数等于它上方两数之和。
思路：每一行的第一位和最后一位是1，没有变化，中间是前排一位与左边一排的和，这种算法是用一个二维数组保存，另外有种算法用一维数组也可以实现，一行一行的输出，有兴趣去写着玩下。  
```
结构如下
1 
1 1
1 2 1
1 3 3 1
1 4 6 4 1
1 5 10 10 5 1

//每行的第一个和最后一个都为1，写了6行
for($i=0; $i<6; $i++) 
{
    $a[$i][0]=1;
    $a[$i][$i]=1;
}

//除了第一位和最后一位的值，保存在数组中
for ($i=2; $i<6; $i++)
{
    for($j=1; $j<$i; $j++) 
    {
        $a[$i][$j] = $a[$i-1][$j-1] + $a[$i-1][$j];
    }
} 

//打印
for($i=0; $i<6; $i++)
{

    for($j=0; $j<=$i; $j++) 
    {
        echo $a[$i][$j].'  ';
    }
    echo PHP_EOL; 
}
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

// 使用异或交换2个值，原理：一个值经过同一个值的2次异或后，原值不变
$a = $a^$b;
$b = $a^$b;
$a = $a^$b;
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
function get_ext($url)
{
    $arr = parse_url($url);
    $result = pathinfo($arr['path']);
    return $result['extension'];    
}
$url = 'http://www.sina.com.cn/abc/de/fg.php?id=1';
var_dump(get_ext($url));

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


// 支持中文
function getRev($str, $encoding='utf-8')
{
    $result = '';
    $len = mb_strlen($str);
    for($i=$len-1; $i>=0; $i--){
        $result .= mb_substr($str, $i, 1, $encoding);
    }
    return $result;
}
$string = 'OK你是正确的Ole';
echo getRev($string);
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
```php
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
        }
    }

    return $arr;
} 
```
  
取两个文件的相对路径
-----------
```php
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

// 进行排它型锁定
if (flock($fp, LOCK_EX)) { 
    fwrite($fp, "Write something here\n");

    // 释放锁定
    flock($fp, LOCK_UN);

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


用二分法查找一个长度为10的排好序的线性表，查找不成功时最多需要比较次数是（小米）
--------------
```
function bin_sch($array, $low, $high, $k){
   if ( $low <= $high){
        $mid = round(($low+$high)/2 );
        if ($array[$mid] == $k){
             return $mid;
        } elseif ( $k < $array[$mid]){
             return bin_sch($array, $low, $mid-1, $k);
        } else{
             return bin_sch($array, $mid+ 1, $high, $k);
        }
   }
   return -1;
}

$arr = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10];
$a = bin_sch($arr, 0, count($arr)-1, 1);
var_dump($a);
```
最多为4次



从0,1,2,3,4,5,6,7,8,9，这十个数字中任意选出三个不同的数字，“三个数字中不含0和5”的概率是（小米）  
--------------
7/15



9.一个三角形三个顶点有3只老鼠，一声枪响，3只老鼠开始沿三角形的边匀速运动，请问他们相遇的概率是（小米）  
--------------
75%，每只老鼠都有顺时针、逆时钟两种运动方向，3只老鼠共有8种运动情况，只有当3只老鼠都为顺时针或者逆时钟，它们才不会相遇，剩余的6中情况都会相遇，故相遇的概率为6/8=75%。  



小羊能活5岁，它在2岁，4岁的时候都会生一只小羊，5岁的时候就死亡了。
问：现在有一只刚出生的小羊(0岁),n年后有多少只羊？
--------------
```
推算：
1 1
2 2
3 2
4 4
5 3 第一个5岁了
6 6
7 5 第二个5岁了
8 10 
9 8 第三个（有两个）5岁了
10 16
11 13 第四个（有三个）5岁了 

// 算法1:
// ----------
function t($n) {
    static $num = 1;

    for ($j=1; $j<=$n; $j++) {
        if ($j == 2 || $j == 4) {
            $num++;
            t($n-$j);
        } elseif ($j == 5) {
            $num--;
        }
    }
    return $num;
}
//test
$n = 11;
echo t($n);


// 算法2:
// ----------
function sheep($n)
{
    $y=[
        0=>1,
        1=>0,
        2=>0,
        3=>0,
        4=>0,
        5=>0,
    ];
    for ($i=0; $i<$n; $i++) { 
        for ($j=5; $j>0; $j--){
            $y[$j] = $y[$j-1];
        }

        $born = $y[2]+$y[4];
        $y[0] = $born;
    }    

    print_r($y);
    unset($y[5]);
    // print_r($y);
    return array_sum($y);
}
// $n = 6;
// var_dump(sheep($n), 'n:'.$n);
```


已知一只羊有7岁寿命，且在2、3、5岁时产下1只小羊（不管公母，假设羊是无性繁殖） 
*一开始有1只刚出生的羊 
*写出算法，计算N年后有几只羊
--------------
```
推算：
1 1 第一个1岁了
2 2 第一个2岁了，第二个0岁
3 3 第一个3岁了，第二个1岁，第三个0岁
4 4 第一个4岁了，第二个2岁，第三个1岁
5 7 第一个5岁了，第二个3岁了，第三个1岁，第四个0岁，第5个0岁
6 9

// 算法1
// --------
function t($n) {
    static $num = 1;

    for ($j=1; $j<=$n; $j++) {
        if (in_array($j, [2, 3, 5])) {
            $num++;
            t($n-$j);
        } elseif ($j == 7){
            $num--;
        }
    }

    return $num;
}
//test
echo t(4);

// 算法2
// --------
function sheep($n)
{
    $y=[
        0=>1,
        1=>0,
        2=>0,
        3=>0,
        4=>0,
        5=>0,
        6=>0,
        7=>0
    ];
    for ($i=0; $i<$n; $i++) { 
        for ($j=7; $j>0; $j--){
            $y[$j] = $y[$j-1];
        }

        $born = $y[2]+$y[3]+$y[5];
        $y[0] = $born;
    }    

    print_r($y);
    unset($y[count($y)-1]);
    // print_r($y);
    return array_sum($y);
}
$n = 4;
var_dump(sheep($n), 'n:'.$n);
```


牛年求牛：有一母牛，到4岁可生育，每年一头，所生均是一样的母牛，到15岁绝育，不再能生，20岁死亡，问n年后有多少头牛。
注：同小羊的起算法一样
------------
```
function t($n) {
    static $num = 1
    for($j=1; $j<=$n; $j++){
        if ($j>=4 && $j<15) {
            $num++;
            t($n-$j);

        } elseif ($j == 20) {
            $num--;
        }
    }

    return $num;
}
//test
echo t(8);
```

有1、2、3、4个数字，能组成多少个互不相同且无重复数字的三位数？都是多少？
-------------
```
$num = 0;
$max = 5;
for($i=1; $i<=$max; $i++) {
    for($j=1; $j<=$max; $j++) {
        for($k=1; $k<=$max; $k++) {
            if($i != $j && $j != $k && $i != $k) {
                $num = $num + 1;
            }

        }
    }
}
echo $num;
```

有5个人偷了一堆苹果，准备在第二天分赃。晚上，有一人遛出来，把所有菜果分成5份，但是多了一个，顺手把这个扔给树上的猴了，自己先拿1/5藏了。
没想到其他四人也都是这么想的，都如第一个人一样分成5份把多的那一个扔给了猴，偷走了1/5。
第二天，大家分赃，也是分成5份多一个扔给猴了。最后一人分了一份。问：共有多少苹果？
-------------
```
// 算法1
// --------
function appleRec($people)
{
    $flag=FALSE;
    $people = 5;

    for($i=1;;$i++){
         $j=$people;
         $a=$i;

         for(;$j>=0;$j--){
             if ($a%$people==1) {
                if ($j == 0) {
                    $flag=TRUE;
                } else {
                    $a=$a-round($a/$people)-1;
                }

             } else {
                 $flag=FALSE;
                 break;
             }
         }

        if ($flag){
            break;
        }

    }

    return $i;
}
var_dump(appleRec(5));

// 算法2
// ---------
for($i=1; ;$i++) {
    //第一次
    if ($i % 5 == 1) {
        //第一次
        $t = $i - round($i/5) - 1;
        if($t % 5 == 1) {
            //第二次
            $r = $t - round($t/5) - 1;
            if($r % 5 == 1) {
                //第三次
                $x = $r - round($r/5) - 1;
                if($x % 5 == 1) {
                    //第四次
                    $y = $x - round($x/5) - 1;
                    if($y % 5 == 1) {
                        //第五次
                        $s = $y - round($y/5) - 1;
                        if($s % 5 == 1) {
                            echo $i;
                            break;
                        }
                    }
                }
            }
        }
    }
}
```


我们希望开发一款扑克游戏，请给出一套洗牌算法，公平的洗牌并将洗好的牌存储在一个整形数组里。（鑫众人云）
-------------
```
$card_num = 54;//牌数
function wash_card($card_num){
    $cards = $tmp = array();
    for($i = 0; $i < $card_num; $i++) {
        $tmp[$i] = $i;
    }

    for($i = 0; $i < $card_num; $i++){
        $index = rand(0, $card_num-$i-1);
        $cards[$i] = $tmp[$index];
        unset($tmp[$index]);
        $tmp = array_values($tmp);
    }

    return $cards;
}

// 测试：
print_r(wash_card($card_num));
```



一瓶啤酒 2 元钱, 2 个空瓶能换 1 瓶, 4 个瓶盖能换一瓶, 现在有 100 元, 我能买到多少瓶啤酒.
--------------
```
1 方程求解法
// --------
设一瓶酒中酒的价格为x元，瓶子的价格为y元，瓶盖的价格为z元

x+y+z=2
2y=x+y+z
4z=x+y+z

方程组求解可得x=0.5.

所以，10元钱可以喝20瓶酒。

2 推算法
// --------
10元 -- 5瓶盖 + 5空瓶 ：五份啤酒
换1+2瓶 -- 4瓶盖 + 4空瓶 ： 八份啤酒
换1+2瓶 -- 3瓶盖 + 3空瓶 ： 十一份啤酒
换0+1瓶 -- 4瓶盖 + 2空瓶： 十二份啤酒
换1+1瓶 -- 2瓶盖 + 2空瓶： 十四份啤酒
换0+1瓶 -- 3瓶盖 + 1空瓶 ： 十五份啤酒
// --------
赊账
赊账5瓶 -- 8瓶盖 + 6空瓶 ： 二十份啤酒
换回2+3瓶=5瓶，还给老板娘
10元钱喝了20瓶啤酒。
```


一块钢锭可以铸成25个机器零件的毛坯，每加工5个机器零件的毛坯所剩的脚料又可以铸成一个机器零件的毛坯。现在有这种钢锭10块，最多可以加工多少个机器零件毛坯？
--------------



斐波那契数列的定义如下：第一个和第二个数字都是1，而后续的每个数字是其前两个数字之和，例如，数列中前几个数字是1，1，2，3，5，8，13，…
--------------
有一对兔子，从出生后第三个月起每个月都生一对兔子，小兔子长到第三个月后又生一对兔子，假如兔子都不死，每个月兔子对数为多少？   
思考这道题的时候，如果你简单的推算一下，会发现兔子每个月的对数就是斐波那契数列。   
第一个月：1对；   
第二个月：1对；   
第三个月：2对；   
第四个月：3对：   
第五个月：5对：   
第六个月：8对；   
...

```
$n = 4; 

// 方式一： 递归
function fun($n){
   if ($n > 2) {
       return fun($n-1)+fun($n-2);
   } else {
       return 1;
   } 
}

var_dump(fun($n));

// 方式二
function getResult($month){
  $one = 1; //第一个月兔子的对数
  $two = 1; //第二个月兔子的对数
  $sum = 0; //第$month个月兔子的对数

  if($month < 3) {
     return 1;
  }

  for($i = 2; $i < $month; $i++){
     $sum = $one + $two;
     $one = $two;
     $two = $sum;
  }
  echo $month.'个月后共有'.$sum.'对兔子';
}
var_dump(getResult($n));
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

