



一．实现文件读取和写入的基本思路：
-----------
1．通过fopen方法打开文件：$fp =fopen(/*参数，参数*/)，fp为Resource类型  
2．进行文件读取或者文件写入操作（这里使用的函数以1中返回的$fp作为参数）  
3. 调用fclose($fp)关闭关闭文件  



二：使用fopen方法打开文件
------------
fopen(文件路径[string],打开模式[string])

// 打开模式有以下几种：
// “r”:只能读取文件，不能写入文件（写入操作被忽略）
// “w”:只能写入文件，不能读取文件（读取操作被忽略）
// “a”:只追加文件，与“w”类似，区别是“w”删除原有的内容，“a”不删除原有内容，只追加内容

// r+，w+，a+ 的意义：
// r+，w+，a+都是可读可写的，读取时的方式是一样的，关键在于写入方式的不同:
// r+: 从文件[头部][覆盖]原有内容 （[不删除]原有内容）；
// a+: 从文件[尾部][追加]内容 （[不删除]原有内容）；
// w+: [完全删除]原有内容，然后[再添加]新的内容




三．文件读取和文件写入操作
------------
// 先说说几个比较重要的函数：
// • file_exists()：判断文件是否存在，返回布尔值
// • filesize():获取一个文件大小，返回文件的字节数，为整型数字
// • unlink():删除一个文件


读取文件
------------
// 读取文件的方式有以下几种：
// 1.一次读取一个字节的数据 fgetc()，通过fgetc()获取单个字节
// 2.一次读取指定的字节数的数据 fread()
// 3.一次读取一行数据 fgets()/fgetcsv()
// 4.一次读完全部数据 fpassthru()/ file()


fgetc
------------
```php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
$fp = fopen("$DOCUMENT_ROOT/text.txt",'r');//打开文件
if(file_exists("$DOCUMENT_ROOT/text.txt")){//当文件存在时，才读取内容
	while (!feof($fp)) {//判断文件指针是否到达末尾
		$c = fgetc($fp);//每执行一次fgetc()，文件指针就向后移动一位
		echo $c;//输出获取到的字节
	}
}
fclose($fp);//关闭文件

// 因每次只读一个字节，所以如果文本中是中文时，像下面这样只读取一次会乱码
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
$fp = fopen("$DOCUMENT_ROOT/text.txt",'r');
echo fgetc($fp);	// 仅读一次乱码
// echo fgetc($fp);
// echo fgetc($fp); //连续做三次输出，才是一个完整的中文字
fclose($fp);
```



fread
----------------
// 一次读取指定个数的字节 ，通过fread()方法，此方法同样因中文存在时，会出现乱码问题.
```php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
$fp = fopen("$DOCUMENT_ROOT/text.txt",'r');
echo fread($fp, 3);//一次输出三个字节即一个汉字字符，一个汉字占3个字节（UTF-8）
fclose($fp);

// 当你想读一个文件时，又不知数据长度时，可以这样配合filesize来用
$fp = fopen('xxx/xx.txt'）
echo fread($fp, filesize("xxx/xx.txt")); 
fclose($fp);
```



fgets (推荐用)
----------------
// 一次读取一行——通过fgets()获取一行内容，这是推荐的方式
```php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT']
$fp = fopen("$DOCUMENT_ROOT/text.txt",'r');//打开文件
if(file_exists("$DOCUMENT_ROOT/text.txt")){//当文件存在时，才读取内容
	while(!feof($fp)){//判断文件指针是否到达末尾
		$line = fgets($fp);//返回一行文本，并将文件指针移动到下一行头部
		echo $line."<br/>";//输出获取到的一行文本
	}
}
fclose($fp);//关闭文件
```
注意：fgets也有第二个参数，即字每行读取的字节数，如果存在中文时，第二个参数不建议用。



fpassthru() 一次读完全部文件，并直接输出
----------------
```php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
$fp = fopen("$DOCUMENT_ROOT/text.txt",'r');
fpassthru($fp);
fclose($fp);
```
注意：我们并没有从fpassthru($fp)获取到返回值然后echo到页面上去，也就是说这个方法是会强制输出获取的内容的，而并不是像之前例子的方法那样返回文本，允许我们保存到变量中才将其输出



readfile() readfile函数打开文件，返回文件内容直接输出在游览器上
----------------
```php
readfile('xxx.txt');
// 注意：这个函数不需要写fopen和fclose
```


file() 将读取到的全部内容保存到一个数组，每个数组元素为一行的内容。 (小文本内容时可以用)
----------------
```php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
$file_array = file("$DOCUMENT_ROOT/text.txt");//取到文件数组
foreach ($file_array as $value) {//输出数组元素
	echo $value."<br/>";
}
// 注意：这里我们并不需要写fopen和fclose哦！也就是说file()方法已经帮我们做了这一步了
```




五．文件指针的移动
----------------
我们上面调用的读取文件的函数，其实都是基于文件指针去打印的，每读取一段字节内容，文件指针就向后移动一段字节长度，直到被读取的文件最大字节长度为止

示例文件内容
/*
我叫彭湖湾
国有二级膜法师
明年一级
后年特级
*/

示例代码
```php
$DOCUMENT_ROOT = $_SERVER['DOCUMENT_ROOT'];
function print_file_pointer($fp) {//定义一个打印文件指针位置的函数
	echo " <br/>//此时文件指针的位置：";
	echo ftell($fp)."<br/>";
}
$fp = fopen("$DOCUMENT_ROOT/text.txt",'r');
echo fgetc($fp);//通过fgetc连续输出三个字节
echo fgetc($fp);
echo fgetc($fp);
print_file_pointer($fp);//打印此刻文件指针的位置

echo fread($fp,6);//通过fread一次输出6字节
print_file_pointer($fp);//打印此刻文件指针的位置

echo fgets($fp); //通过fgets输出一整行
print_file_pointer($fp);//打印此刻文件指针的位置

fpassthru($fp); //一次性输出全部内容
print_file_pointer($fp);//打印此刻文件指针的位置

fseek($fp, 33);//使文件指针移动到33字节位置
print_file_pointer($fp);//打印此刻文件指针的位置

rewind($fp);//使文件指针移动到0字节位置（初始位置）
print_file_pointer($fp);//打印此刻文件指针的位置
$fclose($fp);
```
  
输出结果  
/*    
我  
//此时文件指针的位置：3  
叫彭  
//此时文件指针的位置：9  
湖湾  
//此时文件指针的位置：17  
国家二级膜法师 明年一级 后年特级  
//此时文件指针的位置：66  
  
//此时文件指针的位置：33  
  
//此时文件指针的位置：0  
*/  
  











