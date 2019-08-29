
单向链表翻转问题
==================
head -> 1 -> 2 -> 3 -> null;

算法思路：头结点暂存；从非头结点至最后一个节点遍历，交换指针；暂存的头结点指向空；head重新赋值返回。
```php
<?php

class Node {
    private $value = null;
    private $next = null;
    public function _construct($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }

    public function getNext()
    {
        return $this->next;
    }

    public function setNext($next)
    {
        $this->next = $next;
    }
}

function reverse($head)
{
    if ($head == null) {
        return $head;
    }

    $pre = $head; //头结点暂存
    $cur = $head->getNext();
    //从第二个节点遍历 交换指针方向
    while ($cur) {
        $nextTmp = $cur->getNext();
        $cur->setNext($pre);
        $pre = $cur;
        $cur = $nextTmp;
    }

    $head->setNext(null);
    $head = $pre;

    return $head;
}

// 或者
function reverseList($pHead)
{
    $pre = null;
    while($pHead) {
        $tmp = $pHead->next; //
        $pHead->next = $pre; //1、将$pre变量里面记录的地址给$pHead的next域
        $pre = $pHead; //2、将$pHead的值（一个地址）给$pre变量
        $pHead = $tmp;
    }
    return $pre;
}
```

快速幂 a的b次方
==================
```php
//非递归版：
function f($a, $b){
  $total = 1;
  $y = $a;

  while ($b != 0){
    if ($b&1 == 1)
      $total = $total*$y;

    $y = $y*$y;
    $b = $b>>1;
  }

  return $total;
}

//防结果过大，每次乘积求余
function f($a, $b, $n){
  $total = 1; 
  $y = $a;

  while ($b != 0){
    if ($b&1 == 1) 
      $total = $total*$y%$n;

    $y = $y*$y%$n; 
    $b = $b>>1;
  }

  return $total;
}
```

写一个通用的二维数组排序
==================
```php
<?php
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

    // asort或arsort排序后，数据会重新排序，但原下标值不会变。正是因该下标不变，才可利用下标对二维数组排序
    //var_dump($array_temp);

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
```



使对象可以像数组一样进行foreach循环，要求属性必须是私有
==================
Iterator模式的PHP5实现，写一类实现Iterator接口（腾讯）
```php
<?php
class Test implements Iterator
{
     private $item = ['id'=>1,'name'=>'php'];

     public function rewind(){
          reset($this->item);
     }

     public function current(){
          return current($this->item);
     }

     public function key(){
          return key($this->item);
     }

     public function next(){
          return next($this->item);
     }

     public function valid(){
          return ($this->current() !== false);
     }
}

// 测试
$t = new Test();
foreach($t as $k=>$v){
     echo$k,'--->',$v,'<br/>';
}
```


php写一个双向队列（腾讯）
=====================
地址：https://www.cnblogs.com/muziyun1992/p/6724028.html  
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
  function getLength(){
    return count($this->queue);
  }
  function getFirst(){
    return array_shift($this->queue);
  }
  function getLast(){
    return array_pop($this->queue);
  }
  /*
  function removeFirst(){//头出队
    return array_shift($this->queue);
  }
  function removeLast(){//尾出队
    return array_pop($this->queue);
  }
  */
  function show(){//显示
    echo implode(" ",$this->queue);
  }
  function clear(){//清空
    unset($this->queue);
  } 
}
$q=new Deque();
$q->addFirst(1);
$q->addLast(5);
$q->getFirst();
$q->getLast();
$q->addFirst(2);
$q->addLast(4);
$q->show();
```




求先递增在递减数组中的最大值
================
法1：做一次遍历，可以依次遍历整个数组如果array[i]满足array[i] > array[i-1] && array[i] > array[i+1],那么i就是最大元素的下标，但是这样做的时间复杂度为O(n)。

法2：因先递增后递减，借助二分查找的思想来做，时间复杂度O(lgn)。

注意：最小堆在这里并不适合，最小堆适合找topK。而top1其实同方法1一样，就是一次遍历。
```php
$arr = [1, 2, 3, 4, 5, 7, 6, 3, 2, 1];

function findMiddle($arr) {
	$length = count($arr);

	$left = 0;
	$right = $length - 1;
	$mid = intval(($left+$right)/2);

	while ($mid > 0 && $mid < $length - 1) {

		var_dump($left.' '.$right.' '.$mid);

		if ($arr[$mid] > $arr[$mid - 1] && $arr[$mid] > $arr[$mid+1]) {
			return $arr[$mid];

		} elseif ($arr[$mid] > $arr[$mid-1]) {
			$left = $mid+1; //也可不加1，该例中因最终结果始终会判断mid与mid+1及mid-1，所以left与right的变化可+1或-1
			$mid = intval(($left+$right)/2);

		} else {
			$right = $mid-1; //也可不减1
			$mid = intval(($left+$right)/2);
		}
	}

	return -1;
}

var_dump(findMiddle($arr));
```


输出一个数组的所有排列组合
=============
输出一个字符串的全部排列情况
```
$str = 'abc';
$a =str_split($str);
perm($a, 0, count($a)-1);

function perm(&$ar, $k, $m) {
    if($k == $m){ 
        echo join('',$ar), PHP_EOL;
    }else {
        for($i=$k; $i<=$m; $i++) {
            swap($ar[$k], $ar[$i]);
            perm($ar, $k+1, $m);
            swap($ar[$k], $ar[$i]);
        }
    }
}

function swap(&$a, &$b) {
    $c = $a;
    $a = $b;
    $b = $c;
}
```


求一个数组的所有子集
===========
二进制求法:
```
<?php
//1.0 用数组模拟一个非空集合
$arr = array(1,2,3);
$arr = array_unique($arr);

//2.0 求出这个集合的子集,并将子集存放至数组
$n = count($arr);
$sub_n = pow(2,$n);
$sub_array = array();

for($i=0; $i<$sub_n; $i++){
	$m = sprintf('%0+'.$n.'b',$i);
	$t_arr = array();
	for($j=0;$j<$n;$j++) {
	  if($m{$j}==1 && $j!=$n) {
	    $t_arr[] = $arr[$j];
	  }
	}

	$sub_array[] = '{'.implode(',', $t_arr).'}';
}
//3.0输出
var_dump($sub_array);
```

直接遍历(递归):
```
<?php
function set($part='',$s=0) 
{ 
	$arr= array(1,2,3);
	echo '{'.trim($part,',').'}<br>'; 
	for($start=$s;$start<count($arr);$start++) 
	{ 
	   set($part.','.$arr[$start],$start+1); 
	} 
}
set();
```


针对几亿的文章设计一套存储体系
=============
问一下文章大概字段：标题、分类、作者、发布者、时间、内容

分表


求数组中和为s的两个数字，求出所有可能的
=============
法1：借助辅助空间hash，判断是否和的差值，如果有则为一对，一次遍历即可。

法2：先排序，然后头尾两指针。



