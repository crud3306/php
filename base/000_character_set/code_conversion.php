<?php

// iconv (iconv有bug，碰到一些生僻字就会无法转换)
// =====================
string iconv ( string $in_charset , string $out_charset , string $str )
// 参数：
// 第一个参数：内容原的编码
// 第二个参数：目标编码
// 第三个参数：要转的字符串
// 返回：成功返回字符串，失败时返回 FALSE

// 注意：iconv有bug ，碰到一些生僻字就会无法转换.
// 为了防止转换失败，我们可以这样写，在第二个参数后面加//IGNORE，这样可以忽略生僻字的转换
// iconv("UTF-8", "GB2312//IGNORE", $data); 

// 例：
$instr = '测试';
// GBK转UTF-8
$outstr = iconv('GBK','UTF-8',$instr);
var_dump($instr);



// mb_convert_encoding 
// =====================
// 为了确保转换的成功率，我们可以用另一个转换函数 mb_convert_encoding，这个函数效率不是很高，另外这个函数还可以省略第三个参数，自动识别内容编码，不过最好不要用，影响效率。但为了保证成功率，还是推荐用这个。
// 注意：mb_convert_encoding和iconv参数顺序不一样
string mb_convert_encoding ( string $str , string $to_encoding [, mixed $from_encoding ] )
// 第一个参数：要处理的字符串
// 第二个参数：目标编码
// 第三个参数：内容原编码，它可以是一个 array 也可以是逗号分隔的枚举列表

// 例：
$instr = '测试';
// GBK转UTF-8
$outstr = mb_convert_encoding($instr,'UTF-8','GBK');
$str = mb_convert_encoding($instr, "UCS-2LE", "JIS, eucjp-win, sjis-win");




// mb_internal_encoding — 设置/获取内部字符编码
// ================
// mixed mb_internal_encoding ([ string $encoding = mb_internal_encoding() ] )
// 参数 ：
// encoding 字符编码名称使用于 HTTP 输入字符编码转换、HTTP 输出字符编码转换、mbstring 模块系列函数字符编码转换的默认编码。 
// 返回值 ：
// 如果设置了 encoding，则成功时返回 TRUE， 或者在失败时返回 FALSE。 In this case, the character encoding for multibyte regex is NOT changed. 如果省略了 encoding，则返回当前的字符编码名称。

/* 设置内部字符编码为 UTF-8 */
mb_internal_encoding("UTF-8");

/* 显示当前的内部字符编码*/
echo mb_internal_encoding();



// mb_detect_encoding — 检测字符的编码
// ================
string mb_detect_encoding ( string $str [, mixed $encoding_list = mb_detect_order() [, bool $strict = false ]] )
// 检测字符串 str 的编码。
// 参数 
// str    待检查的字符串。
// encoding_list   是一个字符编码列表。 编码顺序可以由数组或者逗号分隔的列表字符串指定。
// 如果省略了 encoding_list 将会使用 detect_order。
// strict    strict 指定了是否严格地检测编码。 默认是 FALSE。
// 返回值
// 检测到的字符编码，或者无法检测指定字符串的编码时返回 FALSE。
echo mb_detect_encoding("哈哈123");





// 下面一个例子，获取字符串编码方式
// ----------------
function getcode($str)
{
    $s1 = iconv('utf-8','gbk//IGNORE',$str);
    $s0 = iconv('gbk','utf-8//IGNORE',$s1);
    if($s0 == $str){
        return 'utf-8';
    }else{
        return 'gbk';
    }
}



