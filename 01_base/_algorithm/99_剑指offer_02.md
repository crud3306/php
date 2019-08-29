

30 包含min函数的栈
===============
定义栈的数据结构，请在该类型中实现一个能够得到栈的最小元素的min函数。在该栈中，调用min、push及pop的时间复杂度都是O(1)。



31 栈的压入、弹出序列
===============
输入两个整数序列，第一个序列表示栈的压入顺序，请判断第二个序列是否为该栈的弹出顺序。假设压入栈的所有数字均不相等。
例如：{1,2,3,4,5}，序列{4,5,3,2,1}是该压栈序列对应的一个弹出序列，但{4,3,5,1,2}就不是。

思路：借助辅助栈
```php
$arr1 = [1,2,3,4,5];
$arr2 = [4,5,3,2,1];

function isPopOrder($arr1, $arr2)
{
	$bPossible = false;

	$pop = array_pop($arr2);
	if () {

	}
}
```


32 从上到下打印二叉树
===============
从上到下打印出二叉树的每个节点，同一层的节点按照从左到右的顺序打印。
```

```

33 二叉搜索树的后序遍历
===============
输入一个整数数组，判断该数组是不是某二叉搜索树的后序遍历结果。如果是则返回true，否则返回false。假设输入的数组的任意两个数字都互不相同。

思路：后序遍历得到的序列中，最后一个数字是树的根节点的值。数组中前面的数字分两部分：第一部分是左子树节点的值，它们比根小，第二部分是右子树节点的值，它们都比根节点值大。
```php
$arr = [5,7,6,9,11,10,8];
$arr = [7,4,6,5];
function verifyBST($arr, $start, $length)
{
	$root = $arr[$length - 1];

	// 在二叉搜索树中左子树节点的值小于根节点的值
	$i = $start;
	for (; $<$length-1; ++$i) {
		if ($arr[$i] > $root) {
			break;
		}
	}

	// 在二叉搜索树中右子树节点的值大于根节点的值
	int $j = $i;
	for (; $j<$length-1; ++$j) {
		if ($arr[$j] < $root) {
			return false;
		}
	}

	// 判断左子树是不是二叉搜索树
	bool $left = true;
	if ($i > 0) {
		$left = verifyBST($arr, 0, $i);
	}

	// 判断右子树是不是二叉搜索树
	bool $right = true;
	if ($i < $length - 1) {
		$right = verifyBST($arr, $i, $length - $i - 1);
	}	

	return $left && $right;
}
var_dump(verifyBST($arr, 0, count($arr)));
```


34 二叉树中和为某一值的路径
===============
输入一棵二叉树和一个整数，打印出二叉树中节点值的和为输入整数的所有路径。从树的根节点开始往下一直到叶节点所经过的节点形成一条路径。
```

```


35 复杂链表的复制
===============
请实现函数ComplexListNode* Clone(ComplexListNode* pHead)，复制一个复杂链表。在复杂链表中，每个节点除了有一个m_pNext指针指向下一节点，还有一个m_pSibling指针指向链表中的任意节点或者nullptr。


36 二叉搜索树与双向链表
===============
输入一棵二叉搜索树，将该二叉搜索树转换成一个排序的双向链表。要求不能创建任何新的节点，只能调整树中节点指针的指向。

思路：中序遍历。
```
```


37 序列化二叉树
===============
请实现两个函数，分别用来序列化和反序列化二叉树。



38 字符串的排列
===============
输入一个字符串，打印出该字符串中字符的所有排列。
例如：输入字符串abc，则打印出由字符a、b、c所以排列出来的所有字符串abc、acb、bac、bca、cab和cba。

```

```



39 数组中出现次数超过一半的数字
===============
数组中有一个数字出现的次数超过数组长度的一半，请找出这个数字。
例如：输入一个长度为9的数组{1,2,3,2,2,2,5,4,2}。由于数字2在数组中出现了5次，超过数组长度的一半，因此输出2。

方法1：快排，然后n/2即是

方法2：先遍历一次hash存上次数 及 数组长度，然后再遍历一次，判断次数大于一半的

方法3：一个计数。从第一位开始，相等则加1，不等则减1。要找的数字肯定是最后一次把次数设为1时对应的数字。
```php
$arr = [1,2,3,2,2,2,5,4,2];
function moreThanHalfNum($arr, $length)
{
	$result = $arr[0];
	$times = 1;

	for (int $i = 1; $i < $length; ++$i) {
		if ($times == 0) {
			$result = $arr[$i];
			$times = 1;
		} else if ($arr[$i] == $result) {
			$times++;
		} else {
			$times--;
		}
	}

	// 再做次循环，验证是否超过1半

	return $result;
}
var_dump(moreThanHalfNum($arr, count($arr)));
```




40 最小的k个数
===============
输入n个整数，找出其中最小的k个数。
例如：输入4、5、1、6、2、7、3、8这8个数字，则最小的4个数字是1、2、3、4。

方法1：排序后，取最小的k位数。 时间复度：O(nlgn)

方法2：最大堆。时间复度：O(nlgk)



41 数据流中的中位数
===============
如何得到一个数据流中的中位数？如果从数据流中读出奇数个数值，那么中位数就是所有数值排序之后位于中间的数值。如果从数据流中读出偶数个数值，那么中位数就是所有数值排序之后中间两个数的平均值。

```
```



42 连续子数组的最大和
===============
输入一个整型数组，数组里有正数也有负数。数组中的一个或连续多个整数组成一个子数组。求所有子数组的和的最大值。
要求时间复杂度为O(n)。
例如：数组{1,-2,3,10,-4,7,2,-5}，和最大的子数组为{3,10,-4,7,2}，因此输出为该子数组的和18。

```php
$arr = [1,-2,3,10,-4,7,2,-5];
$length = count($arr);

function findSubArrayWithMaxSum($arr, $length)
{
	if (empty($arr) || $length < 1) {
		return false;
	}

	$nCurSum = 0;
	$nGreatestSum = 0;

	for ($i = 0; $i < $length; ++$i) {
		if ($nCurSum <= 0 && $arr[$i] != 0) {
			$nCurSum = $arr[$i];
		} else {
			$nCurSum += $arr[$i];
		}

		if ($nCurSum > $nGreatestSum) {
			$nGreatestSum = $nCurSum;
		}
	}

	return $nGreatestSum;
}
var_dump(findSubArrayWithMaxSum($arr, $length));
```




43 1到n整数中1出现的次数
===============
输入一个整数n，求1到n这n个整数的十进制表示中1出现的次数。例如：输入12，1到12这些整数中包含1的数字有1、10、11和12，1一共出现了5次。


44 数字序列中某一位的数字
===============
数字以0123456789101112131415...的格式序列化到一个字符序列中。在这个序列中，第5位(从0开始计数)是5，第13位是1，第19位是4，等等。请写一个函数，求任意第n位对应的数字。



45 把数组排成最小的数
===============
输入一个正整数数组，把数组中所有数字拼接起来排成一个数，打印能拼装出的所有数字中最小的一个。
例如：输入数组{3,32,321}，则打印出这3个数字能排成的最小数字321323。

思路：数字转成字串，两两拼装，按字符串大小的比较规则。
```

```


46 把数字翻译成字符串
===============



47 礼物的最大价值
===============
在一个 m x n 的横盘的每一格都放一个礼物，每个礼物都有一定的价值(价值大于0)。你可以从横盘的左上角开始拿格子里的礼物，并每次向左或向下移动一条，直到到达横盘的右下角。给定一个横盘及其上面的礼物，请计算你最多能拿到多少价值的礼物。



48 最长不含重复字符的子字符串
===============
请从字符串中找出一个最长的不包含重复字符的子字符串，计算该最长子字符串的长度。假设字符串中只包含a..z字符。
例如：在字符串"arabcacfr"中，最长的不含重复字符的子字符串是"acfr"，长度为4。

方法1：蛮力法，循环嵌套，时间复杂度O(n平方)

方法2：动态规划


49 丑数
===============
我们把只含因子2、3、5的数称为丑数(ugly number)。
求按从小到大的顺序的第1500个丑数。
例如：6、8都是丑数，但14不是，因为它包含因子7.习惯上我们把1当作第一个丑数。

先写一个判断是不是丑数的方法，然后循环判断

```php
function isUgly($number)
{
	while ($number % 2 == 0) {
		$number = intval($number/2);
	}
	while ($number % 3 == 0) {
		$number = intval($number/3);
	}
	while ($number % 5 == 0) {
		$number = intval($number/5);
	}

	return ($number == 1) ? true : false;
}
```

```php
function getUglyNumber($foundIndex)
{
	if ($foundIndex <= 0) {
		return 0;
	}

	$number = 0;
	$index = 0;
	
	while ($index < $foundIndex) {
		$number++;

		if (isUgly($number)) {
			++$index;
		}
	}

	return $number;
}
```



50 第一个只出现一次的字符
================
字符串中第一个只出现一次的字符
----------------
在字符串中找出第一个只出现一次的字符。如输入"abaccdeff"，则输出"b"。

方法1：蛮力法，循环嵌套，时间复杂度O(n平方)

方法2：借助hash结构，算法每个字符出现的次数，然后再次循环 时间时间复杂度O(n)。


51 数组中的逆序对
================
在数组中的两个数字，如果前面一个数字大于后面的数字，则两个数字组成一个逆序对。  
输入一个数组，求出这个数组中的逆序对的总数。
例如：数组{7,5,6,4}中,一其存在5个逆序对，分别是{7,6} {7,5} {7,4} {6,4} {5,4}

方法1：蛮力法，循环嵌套，时间复杂度O(n平方)





52 两个链表的第一个公共节点
================
输入两个链表，找出它们的第一个公共节点。链表定义如下
```c++
struct ListNode
{
	int m_nKey;
	ListNode* m_pNext;
}
```

方法1：循环嵌套，时间O(mn)

方法2：因为是单向链表，所以每个节点只有一个next，所以从某一点重合后，两链表后面数字全部一样。
但助两个辅助栈，时间复杂度O(m+n)，空间复杂度O(m+n)

方法3：先遍历两个链表得到各自长度；然后两次遍历时，长链表先走若干步(长度的差值)，接着同时遍历两链表，找到的每一个相同的节点就是它们的第一个公共节点。时间复杂度O(m+n)




53 在排序数组中查找数字
================
数字在排序数组中出现的次数
----------------
统计一个数字在排序数组中出现的次数。
例如：输入排序数组{1,2,3,3,3,3,4,5}和数字3，由于3在这个数组中出现4次，因此输出4。

方法1：顺序查找，时间为O(n)

方法2：二分查找左右边界值，差值+1即为数量
```

```


0到n-1间缺失的数字
----------------
一个长度为n-1的递境排序数组中所有数字唯一，并且数字在范围0至n-1之内。在范围0至n-1内的n个数字中有且只有一个数字不在该数组中，请找出这个数字。

方法1：分别求和然后做差值，求合公式n(n+1)/2，因只有n-1个数，所以(n-1)n/2
> n(n-1)/2 - for($i)计算出的sum

方法2：借助二分查找思想  
找出第一个下标不等于元素的值即可。
```
function 
```




56 数组中	数字出现的次数
================
数组中只出现一次的两个数字
----------------
一个整型数组里除两个数字之外，其他数字都出现了两次。请写程序找出这两个只出现一次的数字。要求时间复杂度O(n),空间复杂度O(1)。






57 和为s的数字
================
和为s的两个数字
----------------
输入一个递增排序的数组和一个数字s，在数组中查找两个数，使得它们的和正好是s。如果有多对数字的和等于s，则输出任意一对即可。

例如：输入数组{1, 2, 4, 7, 11, 15}和数字15,由于4+11=15，因此输出4和11。

方法1：循环嵌套，每个数依次和其它的n-1个数字的和。O(n的平方)

方法2：两个索引分别指向头尾，然后拿相加后的值与sum比较，再分别移动头尾。O(n)
```php
$arr = [1, 2, 4, 7, 11, 15];

function findNumbersWithSum($arr, $length, $sum)
{
	if ($length < 1) {
		return false;
	}

	$ahead = 0;
	$behind = $length - 1;

	while ($ahead < $behind) {
		$currSum = $arr[$ahead] + $arr[$behind];

		if ($currSum == $sum) {
			return array($arr[$ahead], $arr[$behind]);
		} elseif ($currSum > $sum) {
			$behind--;
		} else {
			$ahead++;
		}
	}

	return false;
}
var_dump(findNumbersWithSum($arr, count($findNumbersWithSum), 15));
```

上一题，如果要输入和为s的所有组合对。 
-----------------
借助hash，与差值 (和 - 其中数)；时间复杂度 O(n)
```php
function findNumbersArrWithSum($arr, $length, $sum)
{
	$numbersArr = array();
	$map = array();
	$i = 0;
	for (isset($arr[$i])) {
		$curr = $arr[$i];
		if (!isset($map[$curr])) {
			$map[$curr] = 1;	
		}

		$left = $sum - $curr;
		if (isset($map[$left]) && $map[$left] > 0) {
			$map[$left]--;
			$map[$curr]--;

			var_dump($map[$curr], $map[$left]);
			$numbersArr = [
				[$map[$curr], $map[$left]],
			];
		}
		
	}

	return array();
}
```


和为s的连续正数序列
----------------
输入一个正数s，打印出所有和为s的连续正数序列（至少含有两个数）。
例如：输入15，由于1+2+3+4+5 = 4+5+6 = 7+8 = 15，所以打印出3个连续序列1-5，4-6，7-8。
```php
$num = 15;

function findNumbersWithSum($num)
{
	if ($sum < 3) {
		return false;
	}

	$small = 1;
	$big = 2;

	$mid = intval((1+$sum)/2);

	$currSum = $small + $big;

	while ($small < $middle) {
		if ($currSum == $sum) {
			var_dump(1, array_range($small, $big));
		}

		while ($currSum > $sum && $small < $mid) {
			$currSum -= $small;
			$small++;

			if ($currSum == $sum) {
				var_dump(1, array_range($small, $big));
			}
		}

		$big++;
		$currSum += $big;
	}

	return false;
}
var_dump(findNumbersWithSum(15));
```



58 翻转字符串
================
翻转单词顺序
----------------
输入一个英文句子，翻转句子中单词的顺序，但单词内字符的顺序不变。为简单起见，标点符号和普通字母一样处理。例如输入字符串"I am a student."，则输出"student. a am I"。

分两步：
第一步 翻转句子中的所有字符；
第二步 翻转句子中的每个单词；
这两步的顺序可以颠倒，结果是一样的。复杂度最快为O(n)。

封装一个翻转字符串的方法，然后就是单独两次遍历。


左旋字符串
----------------
字符串的左旋操作是把字符串前面的若干个字符转移到字符串的尾部。请定义一个函数实现字符串左旋转操作的功能。
比如：输入字符串"abcdefg"和数字2，该函数将返回左旋转两位得到的结果"cdefgab"。

可以用上面一题的思想，如果把前2个字符左旋到后面，则可以前2个字符是一个单词，后面字符是另一个单词。
第一步：翻转所有单词
第二步：翻转整个字符串


另一种方法：直接翻转
```php
$str = 'abcdefg';
$num = 2;

//直接用函数
$newStr = substr($str, $num).substr($str, 0, $num);

//自定义函数
function changeStr($str, $num) {
	$length = strlen($str);
	if ($num > $length) {
		return false;
	}

	$frontStr = '';
	$newStr = '';
	for ($i = 0; $i < $length; $i++) {
		if ($i < $num) {
			$frontStr .= $str[$i];
		} else {
			$newStr .= $str[$i];
		}
	}

	for ($i = 0; $i < $num; $i++) {
		$newStr .= $frontStr[$i];
	}

	return $newStr;
}
var_dump(changeStr($str, $num));
```



59 队列的最大值
================
滑动窗口的最大值
----------------
给定一个数组和滑动窗口的大小，请找出所有滑动窗口里的最大值。

例如：如果输入数组{2，3，4，2，6，2，5，1}及滑动窗口的大小3，那么一共存在6个滑动窗口，它们的最大值分别为{4，4，6，6，5}

思路1：
蛮力法，扫描每个滑动窗口的所有数字并找出其中的最大值。如果滑动窗口的大小为k，则需要O(k)时间找滑动窗口最大值。对于长度为n的数组，这种算法的总时间复杂度是O(nk)。

思路2：



队列的最大值
----------------
请定义一个队列并实现函数max得到队列里的最大值，要求函数max、push_back和pop_front的时间复杂度都是O(1)。





60 n个骰子的点数
================
把n个骰子扔在地上，所有骰子朝上一面的点数之和为s。输入n，打印出s的所有可能的值出现在概率。

分析：一个骰子共6个面，每个面上都有一种点数，对应1到6之间的一个数字。所以n个骰子点数和的最小值为n，最大值为6n。另外，据排列组合的知识，我们还知道n个骰子的所有点数的排列数为6的n次方。要解此问题，我们需要先统计出每个点数出现的次数，然后把每个点数出现的次数除以6的n次方，就能求出每个点数出现的概率。

方法1：递归求骰子点数，时间效率不高
```

```


61 扑克牌中的顺子
================
从扑克牌中随机抽5张牌，判断是不是一个顺子，即这5张牌是不是连续的。2到10为数字本身，A为1，J为11，Q为12，K为13，而大、小王可以看成任意数字。 

思路：  
假设大小王为数字0；  
先对5张牌排序；   
其次统计数组中0的个数；  
最后统计排序之后的数组中相邻数字之间的空缺总数。如果空缺的总数小于或等于0的个数，那么这个数组就是连续的；反之则不连续。  
最后还要注意：如果数组中非0的数字重复出现，则该数组不是连续的。也就是说如果牌里含对子，则不可能是顺子。
```php
/*
排序算法：
1 快排；
2 因这个数本身是0-13，也可以借助hash来排；

假设$arr是已排好序的。
5张牌，$length = 5;
*/

function checkArr() 
{
	// 统计数组中0的个数
	$zeroNumber = 0;
	for ($i = 0; $i < $length && $arr[$i] == 0; $i++) {
		$zeroNumber++;
	}

	// 统计数组中的间隔数目
	$gapNumber = 0;
	$small = $zeroNumber;
	$big = $small + 1;

	while ($big < $length) {
		// 如果有对子，则该牌不可能为顺子
		if ($arr[$small] == $arr[$big]) {
			return false;
		}

		$gapNumber += $arr[$big] - $arr[$small] - 1;
		$small = $big;
		++$big;
	}

	return ($gapNumber > $zeroNumber) ? false : true;	
}
var_dump(checkArr($arr, $length));
```


62 圆圈中最后剩下的数字
================
把0,1,...,n-1这n个数字排成一个圆圈，从数字0开始，每次从这个圆圈里删除第m个数字。求出这个圆圈里剩下的最后一个数字。
可看出是有名的约瑟夫环问题。

解法1：较容易理解的
这种解法每删除一个数字需要m步运算，共有n个数字，因此总的时间复杂度是O(mn)。同时这种转路还需要一个辅助链表来模拟圆圈，其空间复杂度是O(n)。
```php
$n = 11; // 总数
$m = 3; // 数到第几被踢出

function yuesefu($n, $m) {
  if ($n < 1 || $m < 1) {
  	return -1;
  }

  $arr = range(0, $n-1);

  $i = 0;
  while (isset($arr[1])) {
    $i++;
    $num = array_shift($arr);

    if ($i % $m != 0) {
      array_push($arr, $num);
    }
  }

  return $arr[0];
}

var_dump(yuesefu($arr, $n, $m));
```

解法2：理解较困难
这种算法的时间复杂度是O(n)，空间复杂度是O(1)。
```php
$n = 11;  // 总数
$m = 3;  // 数到第几被踢出

function yuesefuhuan($n, $m)
{
    if ($n < 1 || $m < 1) {
    	return -1;
    }

    $s = 0;
    for ($i = 2; $i <= $n; $i++) {
        $s = ($s + $m) % $i;
        // var_dump($i, $s);
    }

    return $s;
}
echo yuesefuhuan($n, $m).PHP_EOL;

//注意，如果数字不是从0到n-1，而是从1到n，则返回值需加1。
```



63 求股票最大的利率
================
假设把某股票的价格按照时间先后顺序存储在数组中，请问买卖该股票一次可能获得的最大利润是多少？
例如，一只股票在某些时间点的价格为{9, 11, 8, 5, 7, 12, 16, 14}。如果我们能在价格为5的时候买入并在价格为16时卖出，则能收获的最大利润为11。

方法1：较差的方法，循环嵌套，依次比较前面数字与后面数字的差。O(n的平方)

方法2：记录一个最小值，和一个差值，从第三位开始循环一次。下面的算法复杂度O(n)
```php
$arr = [9, 11, 8, 5, 7, 12, 16, 14];

function findMaxDiff($arr, $length) {
  $min = $arr[0];
  $maxDiff = $arr[1] - $min;

  for ($i = 2; $i < $length; $i++) {
    if ($arr[$i-1] < $min) {
      $min = $arr[$i-1];
    }

    $currentDiff = $arr[$i] - $min;
    if ($currentDiff > $maxDiff) {
      $maxDiff = $currentDiff;
    }

  }

  return $maxDiff;
}
var_dump(findMaxDiff($arr, count($arr)));
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



针对几亿的文章设计一套存储体系
=============
问一下文章大概字段：标题、分类、作者、发布者、时间、内容

分表


求数组中和为s的两个数字，求出所有可能的
=============
法1：借助辅助空间hash，判断是否和的差值，如果有则为一对，一次遍历即可。

法2：先排序，然后头尾两指针。






