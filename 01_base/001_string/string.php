<?php

// 常用函数整理
// =================

// string strrev(str) //反转字符串顺序

// mixed strpos(string,find,start)	
// 返回字符串在另一字符串中第一次出现的位置(区分大小写)，没找到返回FALSE。
// mixed stripos(string,find,start)	
// 返回字符串在另一字符串中第一次出现的位置(不区分大小写)，没找到返回FALSE。
// strrpos(string,find,start)
// 返回字符串在另一字符串中最后一次出现的位置(区分大小写)，如果没有找到字符串则返回 FALSE。
// strripos(string,find,start)
// 返回字符串在另一字符串中最后一次出现的位置(不区分大小写)，如果没有找到字符串则返回 FALSE。

// string strtolower(str)	把字符串转换为小写字母。
// string strtoupper(str)	把字符串转换为大写字母。
// string lcfirst(str)  把字符串中的首字符转换为小写
// string ucfirst(str)  把字符串中的首字符转换为大写
// string ucwords(str)  把字符串中每个单词的首字符转换为大写

// string substr(string,start,length)
// 参数start：
// 正数 - 在字符串的指定位置开始
// 负数 - 在从字符串结尾开始的指定位置开始，最后一位是-1。
// 0 - 在字符串中的第一个字符处开始
// 参数length：
// 正数 - 从 start 参数所在的位置返回的长度
// 负数 - 从字符串末端返回的长度，最后一位是-1。


// string str_split(string,length)	把字符串分割到数组中，
// 参数length：规定每个数组元素的长度，默认是 1。
// 如果 length 小于 1，则 str_split() 函数将返回 FALSE。
// 如果 length 大于字符串的长度，则整个字符串将作为数组的唯一元素返回。


// string str_pad(string,length,pad_string,pad_type) 把字符串填充为新的长度
// length	必需。规定新的字符串长度。如果该值小于字符串的原始长度，则不进行任何操作。
// pad_string	可选。规定供填充使用的字符串。默认是空白。
// pad_type	可选。规定填充字符串的哪边。
// pad_type可能的值：
// STR_PAD_BOTH - 填充字符串的两侧。如果不是偶数，则右侧获得额外的填充。
// STR_PAD_LEFT - 填充字符串的左侧。
// STR_PAD_RIGHT - 填充字符串的右侧。默认。

// string str_repeat(string,repeat) 把字符串重复指定的次数，repeat必须大于等于0。


// string implode(separator, array)	 返回由数组元素组合成的字符串。
// string join(separator, array)     是implode()的别名。


// string trim(string,charlist)  
// string ltrim(string,charlist)   
// string rtrim(string,charlist)  
// 参数charlist：	
// 可选。规定从字符串中删除哪些字符。如果省略，则移除下列所有字符：
// "\0" - NULL
// "\t" - 制表符
// "\n" - 换行
// "\x0B" - 垂直制表符
// "\r" - 回车
// " " - 空格

// string strip_tags(string,allow)  剥去字符串中的 HTML、XML 以及 PHP 的标签
// 参数allow	：可选，规定允许的标签。这些标签不会被删除。

// string htmlspecialchars(string,flags,character-set,double_encode)
// 把预定义的字符转换为 HTML 实体
// 预定义的字符是：
// & （和号）成为 &
// " （双引号）成为 "
// ' （单引号）成为 '
// < （小于）成为 <
// > （大于）成为 >

// htmlspecialchars_decode(string,flags) 函数把预定义的 HTML 实体转换为字符。



// parse_str(string,array) 把查询字符串解析到变量中
// 参数array：可选，规定存储变量的数组的名称。该参数指示变量将被存储到数组中。
// 如果不设array参数，则由该函数设置的变量将覆盖已存在的同名变量
// 例：
parse_str("name=Bill&age=60");
echo $name."<br>";
echo $age;

parse_str("name=Bill&age=60", $myArray);
print_r($myArray);



// int ord(string) 返回字符串的首个字符的ASCII 值。
// string chr(ascii)
// 例：
echo ord("S")."<br>";
echo ord("Shanghai")."<br>";

$str = chr(43);
$str2 = chr(61);
echo("2 $str 2 $str2 4");














/**
==========================
字符串长度 strlen() mb_strlen()
==========================

在php中，函数strlen()返回字符串的长度，实际上是计算得到字符串所占的字节长度，在不同的编码下，字符串所占的字节长度是不同的。

列举几个常用的字符编码占用字节情况：
ASCII码：一个ASCII码就是一个字节
UTF8编码：一个英文字符占用一个字节，一个中文（含繁体）占用三个字节
Unicode编码：一个英文字符占用两个字节，一个中文（含繁体）占用两个字节
GBK和GBK2312编码：一个中文（含繁体）占用两个字节

在php中计算字符串长度时会出现一些问题，strlen()函数并不能准确的返回字符串的实际长度，当字符串中含有中文，全角符号等情形时，函数实际返回的不是字符串的字符长度，而是字符串所占的字节长度。在一些场景下，这并不符合我们的需求，为了解决这个问题，我们可以使用mb_strlen()函数来协调字符串字符长度和字节长度，真实的计算出其字符长度。

*/

$str = '国人。';
var_dump(strlen($str), mb_strlen($str));
/*  
输出：
===========================
11 、7

结果分析：
===========================
PHP内置的字符串长度函数strlen无法正确处理中文字符串，它得 到的只是字符串所占的字节数。
GB2312编码下，一个汉字占2个字节；
UTF-8编码下，一个汉字占3个字节

采用mb_strlen函数可以较好地解决这个问题。mb_strlen的用法和strlen类似，只不过它有第二个可选参数用于指定字符编码。例如得到UTF-8的字符串$str长度，可以用 mb_strlen($str,'UTF-8')。指定编码UTF-8，则会将一个中文字符当作长度1。
如果省略第二个参数，则会使用PHP的内部编码。内部编码可以通过 mb_internal_encoding()函数得到。

注意：mb_strlen并不是PHP核心函数，使用前需要确保在php.ini中加载了php_mbstring.dll，即确保“extension=php_mbstring.dll”这一行存在并且没有被注释掉，否则会出现未定义函 数的问题。


延伸:
===========================
字符和字节是有区别的。
字符：表示一个不可继续拆分的符号标识；
字节：表示的是存储单位；

一个汉字占一个字符，而具体一个汉字占几个字节要视编码而定，gbk编码下，一个汉字占两个字节，utf-8编码下，一个汉字占三个字节。

关于数据存储单位的简单说明：
计算机数据存储基本单位是字节（Byte，简称B），数据传输的基本单位是“位”（bit，简称b），一个字节等于8位二进制，一个位表示一个0或1。

一个字节的取值范围为0~255 （2^8）。
1B=8b
1KB=1024B=2^10B
1MB=1024KB=2^20B
1GB=1024MB=2^30B
1TB=1024GB=2^40B
1PB=1024TB=2^50B

*/

/*
substr(string,start,length)
参数	描述
string	必需。规定要返回其中一部分的字符串。
start	
必需。规定在字符串的何处开始。

正数 - 在字符串的指定位置开始
负数 - 在从字符串结尾开始的指定位置开始
0 - 在字符串中的第一个字符处开始
length	
可选。规定被返回字符串的长度。默认是直到字符串的结尾。

正数 - 从 start 参数所在的位置返回的长度
负数 - 从字符串末端返回的长度
*/










