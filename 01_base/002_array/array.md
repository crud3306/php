

常用
-----------
```php
array array_filter ( array $array [, callable $callback [, int $flag = 0 ]] )

array array_map ( callable $callback , array $array1 [, array $... ] )

array array_slice( array $array , int $offset [, int $length = NULL [, bool $preserve_keys = false ]] )

array array_splice ( array &$input , int $offset [, int $length = count($input) [, mixed $replacement = array() ]] )

array array_merge ( array $array1 [, array $... ] )
// 合并一个或多个数组，如果输入的数组中有相同的字符串键名，则该键名后面的值将覆盖前一个值。然而，如果数组包含数字键名，后面的值将不会覆盖原来的值，而是附加到后面。
// 如果只给了一个数组并且该数组是数字索引的，则键名会以连续方式重新索引。

array array_replace ( array $array1 , array $array2 [, array $... ] )

array array_flip ( array $array )
// 交换数组中的键和值，键名变成了值，而值成了键名。

bool array_multisort ( array &$array1 [, mixed $array1_sort_order = SORT_ASC [, mixed $array1_sort_flags = SORT_REGULAR [, mixed $... ]]] )
// 用来一次对多个数组进行排序，或者根据某一维或多维对多维数组进行排序。

array array_fill ( int $start_index , int $num , mixed $value )
// 用 value 参数的值将一个数组填充 num 个条目，键名由 start_index 参数指定的开始。
```



过滤数组 array_filter
---------
```php
// array array_filter ( array $array [, callable $callback [, int $flag = 0 ]] )
// 依次将 array 数组中的每个值传递到 callback 函数。如果 callback 函数返回 true，则 array 数组的当前值会被包含在返回的结果数组中。数组的键名保留不变。
// 如果没有提供 callback 函数， 将删除 array 中所有等值为 FALSE 的条目。更多信息见转换为布尔值。

// 参数：
// ----------------
// array
// 要循环的数组

// callback
// 使用的回调函数
// 如果没有提供 callback 函数， 将删除 array 中所有等值为 FALSE 的条目。更多信息见转换为布尔值。

// flag
// 决定callback接收的参数形式:
// ARRAY_FILTER_USE_KEY - callback接受键名作为的唯一参数
// ARRAY_FILTER_USE_BOTH - callback同时接受键名和键值

// 示例：
// ----------------
$a = [1, 2, 'a', 4];
function _f($v) {
  if ($v < 3) {
    return false;
  }

  return true;
}

var_dump(array_filter($a, "_f"));
// 因数组键名保持不变，所以如果是数字索引会不连接，这时这里再用一次array_values()来处理返回的数组
var_dump(array_values(array_filter($a, "_f")));
```



array_map 快速处理数组，返回外理后的新数组
-------------
```php
// array array_map ( callable $callback , array $array1 [, array $... ] )
// array_map()：返回数组，是为 array1 每个元素应用 callback函数之后的数组。 callback 函数形参的数量和传给 array_map() 数组数量，两者必须一样。

// 比如说你想 trim 数组中的所有元素. 新手可能会:
foreach($arr as $c => $v)  
{  
    $arr[$c] = trim($v);  
} 
// 但使用 array_map 更简单:
$arr = array_map('trim' , $arr); 
// 这会为$arr数组的每个元素都申请调用trim. 另一个类似的函数是 array_walk
  
// 可以对比 array_map 与 array_walk  
// array_map    主要是为了得到你的回调函数处理后的新数组，要的是结果。
// array_walk   主要是对每个参数都使用一次你的回调函数，要的是处理的过程。
```


array_slice
------------
```php
// array array_slice( array $array , int $offset [, int $length = NULL [, bool $preserve_keys = false ]] )
// array_slice() 返回根据 offset 和 length 参数所指定的 array 数组中的一段序列。
// 参数 
// array
// 输入的数组。
// offset
// 如果 offset 非负，则序列将从 array 中的此偏移量开始。如果 offset 为负，则序列将从 array 中距离末端这么远的地方开始。
// length
// 如果给出了 length 并且为正，则序列中将具有这么多的单元。如果给出了 length 并且为负，则序列将终止在距离数组末端这么远的地方。如果省略，则序列将从 offset 开始一直到 array 的末端。
// preserve_keys
// 注意 array_slice() 默认会重新排序并重置数组的数字索引。你可以通过将 preserve_keys 设为 TRUE 来改变此行为。

// 返回值
// 返回其中一段。 如果 offset 参数大于 array 尺寸，就会返回空的 array。

$input = array("a", "b", "c", "d", "e");

$output = array_slice($input, 2);      // returns "c", "d", and "e"
$output = array_slice($input, -2, 1);  // returns "d"
$output = array_slice($input, 0, 3);   // returns "a", "b", and "c"
```



array_splice 去掉数组中的某一部分并用其它值取代
------------
```php
// array array_splice ( array &$input , int $offset [, int $length = count($input) [, mixed $replacement = array() ]] )
// 把 input 数组中由 offset 和 length 指定的单元去掉，如果提供了 replacement 参数，则用其中的单元取代。

// 注意 input 中的数字键名不被保留。

$input = array("red", "green", "blue", "yellow");
array_splice($input, 2);
var_dump($input);
// $input is now array("red", "green")

$input = array("red", "green", "blue", "yellow");
array_splice($input, 1, -1);
var_dump($input);
// $input is now array("red", "yellow")

$input = array("red", "green", "blue", "yellow");
array_splice($input, 1, count($input), "orange");
var_dump($input);
// $input is now array("red", "orange")

$input = array("red", "green", "blue", "yellow");
array_splice($input, -1, 1, array("black", "maroon"));
var_dump($input);
// $input is now array("red", "green", "blue", "black", "marnoon")
```



array_flip — 交换数组中的键和值
---------
```php
// array array_flip ( array $array )
// array_flip() 返回一个反转后的 array，例如 array 中的键名变成了值，而 array 中的值成了键名。
$arr = [
    'a' => '1',
    'b' => '2'
];
array_flip($arr);
var_dump($arr);
```


array array_intersect ( array $array1 , array $array2 [, array $... ] )
---------
```php
// array_intersect() 返回一个数组，该数组包含了所有在 array1 中也同时出现在所有其它参数数组中的值。注意键名保留不变。
// array_intersect()函数是求两个数的交集，返回一个交集共有元素的数组（只是数组值得比较）
// array_intersect_assoc()函数是将键和值绑定，一起比较交集部分
// array_intersect_key()函数是将两个数组的键值进行比较，返回的并不只有键值，而是键值和对应的数组值

$array1 = array("a" => "green", "red", "blue");
$array2 = array("b" => "green", "yellow", "red");
$result = array_intersect($array1, $array2);
$result1 = array_intersect_assoc($array1, $array2);
$result2 = array_intersect_key($array1, $array2);
var_dump($result, $result1, $result2);
// 以上例程会输出
Array
(
    [a] => green
    [0] => red
)
```



array_multisort — 对多个数组或多维数组进行排序
------------
```php
// bool array_multisort ( array &$array1 [, mixed $array1_sort_order = SORT_ASC [, mixed $array1_sort_flags = SORT_REGULAR [, mixed $... ]]] )

// array_multisort() 可以用来一次对多个数组进行排序，或者根据某一维或多维对多维数组进行排序。

// 注意：多个要排序的数组的长度要一致，因后面的数组是以前面数组排序索引位置，来做自已的排序

// 关联（string）键名保持不变，但数字键名会被重新索引。
// http://php.net/manual/zh/function.array-multisort.php

// 对二维数组排序
$list = [
    ['id'=>1,'name'=>'aA','cat'=>'aa'],
    ['id'=>2,'name'=>'aa','cat'=>'dd'],
    ['id'=>4,'name'=>'bb','cat'=>'cc'],
    ['id'=>3,'name'=>'bb','cat'=>'bb'],
    ['id'=>3,'name'=>'aa','cat'=>'aa']
];

/**
 * 用该函数封装一个 (二维数组按照子级数组中指定的某个值进行排序) 的函数
 * @param array $list        二维数组
 * @param string $order_key  指定的某个值
 * @return array
 */
function array_sort_by_key($list, $order_key)
{
    $tmp = array();
    foreach ($list as $ma) {
        $tmp[] = $ma[$order_key];
    }
    array_multisort($tmp, $list);

    return $list;
}
$arr1 = array_sort_by_key($list, 'id');
$arr2 = array_sort_by_key($list, 'cat');
var_dump($arr1, $arr2);


// 或者按多个字段排序，比如下面：优先id，其次cat，类似数据库中的多字段排序
// 取得列的列表
foreach ($data as $key => $row) {
    $ids[$key]  = $row['id'];
    $cats[$key] = $row['cat'];
}
// 将数据根据 id 降序排列，根据 cat 升序排列
// 把 $data 作为最后一个参数，以通用键排序
array_multisort($ids, SORT_DESC, $cats, SORT_ASC, $list);
var_dump($data)
```


sizeof($arr) 是 count($arr)的别名，取数组长度
-----------
```php
$arr = [1, 2, 3, 4];
var_dump(sizeof($arr), count($arr));
```



array_rand 随机取指定个数的数组元素
-----------
```php
// mixed array_rand ( array $array [, int $num = 1 ] )
// 从数组中取出一个或多个随机的单元，并返回随机条目的一个或多个键。 它使用了伪随机数产生算法，所以不适合密码学场景，

// 参数
// array 输入的数组。
// num 指明了你想取出多少个单元。

// 返回值
// 如果只取出一个，array_rand() 返回随机单元的键名。 否则就返回包含随机键名的数组。 完成后，就可以根据随机的键获取数组的随机值。 取出数量如果超过 array 的长度，就会导致 E_WARNING 错误，并返回 NULL。

$arr = [1, 2, 3, 4];
var_dump(array_rand($arr, 2));
// 注意只取一个时，只返回键名；取多个时才返回的是数组
var_dump(array_rand($arr, 1));
```


array_replace — 使用传递的数组替换第一个数组的元素
------------
```php
// array array_replace ( array $array1 , array $array2 [, array $... ] )
// array_replace() 函数使用后面数组元素相同 key 的值替换 array1 数组的值。如果一个键存在于第一个数组同时也存在于第二个数组，它的值将被第二个数组中的值替换。如果一个键存在于第二个数组，但是不存在于第一个数组，则会在第一个数组中创建这个元素。如果一个键仅存在于第一个数组，它将保持不变。如果传递了多个替换数组，它们将被按顺序依次处理，后面的数组将覆盖之前的值。
$base = array("orange", "banana", "apple", "raspberry");
$replacements = array(0 => "pineapple", 4 => "cherry");
$replacements2 = array(0 => "grape");

$basket = array_replace($base, $replacements, $replacements2);
print_r($basket);
```


PHP中array_merge 函数与 array+array的区别   
-----------
区别如下：   
当下标为数值时，array_merge()不会覆盖掉原来的值，但array＋array合并数组则会把最先出现的值作为最终结果返回，而把后面的数组拥有相同键名的那些值“抛弃”掉（不是覆盖）.    
  
当下标为字符时，array＋array仍然把最先出现的值作为最终结果返回，而把后面的数组拥有相同键名的那些值“抛弃”掉，但array_merge()此时会覆盖掉前面相同键名的值.   



array_product — 计算数组中所有值的乘积
------------
// number array_product ( array $array )


array_sum() 将数组中的所有值相加，并返回结果。
------------
// number array_sum ( array $array )






