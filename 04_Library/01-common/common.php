<?php

/**
 * 计算字符总数，一个中文相当于2个字母或数字
 * 此函数作用：比如 发微博时只能只能发140个汉字
 * @param string str
 * @return int
 */
function getFontNum($str)
{
    $str_new = preg_replace('/[\x{4e00}-\x{9fa5}]/u', '99', $str);
    return ceil(strlen($str_new)/2);
}

/**
 * 二维数组按照子级数组中指定的某个值进行排序
 * @param array $list        二维数组
 * @param string $order_key  指定的某个值
 * @return array
 */
function arraySortByKey($list, $order_key)
{
    $tmp = [];
    foreach ($list as $v) {
        $tmp[] = $v[$order_key];
    }
    // array_multisort()
    // 这个函数可以对多个PHP数组进行排序，排序结果是所有的数组都按第一个数组的顺序进行排列，每个数组的长度要一致
    array_multisort($tmp, $list);

    return $list;
}

/**
 * <br /> 标签换成\n 
 * 注：与内置函数nl2br($str)功能相反
 */
function br2nl($text) {    
    return preg_replace('/<br\\s*?\/??>/i', "\n", $text);   
} 







