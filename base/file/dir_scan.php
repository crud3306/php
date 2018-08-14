<?php

// 5种方式，效率对比结果：
// 算法2 > 算法1 > 算法4 >  算法3 > 算法5
// 注：上面仅供了解，实际上 算法3 - 5，额外遍历了所有子级目录



// 算法1.简短系
// ================

foreach(glob('*.*') as $filename)
{
  echo 'Filename:'.$filename.;
}



// 算法2.规矩系
// ================

if($handle = opendir('C:\\Inetpub\\wwwroot\\test\\')){
  echo "Files:\n";
  while (false !== ($file = readdir($handle))){
    echo "$file\n";
  }
  closedir($handle);
}


// 算法3.函数系
// ================

function tree($directory)
{
	$mydir=dir($directory);
	while($file=$mydir->read()){
		if((is_dir("$directory/$file")) 
			&& ($file != ".") 
			&& ($file != "..") 
			&& strpos($file, '.') !== 0)
		{
			echo "$directory/$file\n";
			tree("$directory/$file");
		} else {
			echo "$directory/$file\n";
		}
	}

	echo "\n";
	$mydir->close();
}
tree($dir);


// 算法4.函数系II
// ================
function listDir($dir){
  if(is_dir($dir)){
    if ($dh = opendir($dir)) {
      while (($file= readdir($dh)) !== false){
        if((is_dir($dir."/".$file)) && $file!="." && $file!=".."){
          echo "文件名：",$file;
          listDir($dir."/".$file."/");
        } else{
          if($file!="." && $file!=".."){
            echo $file;
          }
        }
      }
      closedir($dh);
    }
  }
}
listDir($dir);


// 算法5.递归系
// ================

function file_list($dir,$pattern="")
{
  $arr=array();
  $dir_handle=opendir($dir);
  if($dir_handle)
  {
    while(($file=readdir($dir_handle))!==false)
    {
      if($file==='.' || $file==='..')
      {
        continue;
      }
      $tmp=realpath($dir.'/'.$file);
      if(is_dir($tmp))
      {
        $retArr=file_list($tmp,$pattern);
        if(!emptyempty($retArr))
        {
          $arr[]=$retArr;
        }
      } else
      {
        if($pattern==="" || preg_match($pattern,$tmp))
        {
          $arr[]=$tmp;
        }
      }
    }
    closedir($dir_handle);
  }
  return $arr;
}
print_r(file_list("C:\\Inetpub\\wwwroot\\test\\"));


// 测试方法
// ================
我们采取在测试代码的头部和尾部添加如下的内容来检测执行时间，并测试5次取平均结果作为最终成绩。

$stime=microtime(true);
//测试代码
//......
//......
$etime=microtime(true);
$total=($etime-$stime)*1000;
echo "{$total} Millisecond(s)";


// 测试结果：
// ================

// 算法1
// ----------------
// 算法1在浏览器能正确输出所有项目，5次测验耗费的时间分别是：

// 平均用时=3803.618621824 毫秒


// 算法2
// ----------------
// 算法2在浏览器也能正确输出所有项目，但在开头会给出“..”（上级目录）的信息。5次测验耗费的时间分别是：

// 平均用时=381.0853481294 毫秒


// 算法3
// ----------------
// 算法3在浏览器能正确输出所有项目，也仍会给出“..”（上级目录）的信息。5次测验耗费的时间分别是：

// 平均用时=24299.2805485 毫秒


// 算法4
// ----------------
// 算法4和算法3类似，在浏览器能正确输出所有项目，5次测验耗费的时间分别是：

// 平均用时=24020.66812516 毫秒


// 算法5
// ----------------
// 算法5曾一度让我以为IIS又出问题了。虽说它在浏览器能正确输出所有项目，但数据的结果默认为数组。5次测验耗费的时间分别是：

// 平均用时=61411.31243706 毫秒



// 测试总结
// ================
// 根据测试结果，我们很容易得出下面的速度排名。

// 算法2 > 算法1 > 算法4 >  算法3 > 算法5



// 为什么算法2要比其他算法都高效一些呢？
// ----------------
// 实际上是因为算法中只使用了php中内置用来读取目录内容的函数“readdir()” 。除了算法1以外，其他算法在引用readdir()的时候，为了弥补函数的先天不足，干了很多其他的事情。



// 如果说，我们需要指定扩展名的列举目录内所有文件的话。Rt推荐使用算法1的模式，我们将代码写成这样就可以了。
foreach(glob('*.需要的扩展名') as $filename)
{
  echo 'Filename:'.$filename.;
}


