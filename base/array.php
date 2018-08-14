<?php


// 使用array_map快速处理数组
// ===============
// 比如说你想 trim 数组中的所有元素. 新手可能会:
foreach($arr as $c => $v)  
{  
    $arr[$c] = trim($v);  
} 
// 但使用 array_map 更简单:
$arr = array_map('trim' , $arr); 
// 这会为$arr数组的每个元素都申请调用trim. 另一个类似的函数是 array_walk

// 
// ===============
