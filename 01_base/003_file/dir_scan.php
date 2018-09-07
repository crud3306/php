<?php

// 5种方式，效率对比结果：
// 算法2 > 算法1 > 算法4 >  算法3 > 算法5
// 注：上面仅供了解，实际上 算法3 - 5，额外遍历了所有子级目录



// 算法1. 简短系
// ================

foreach(glob('*.*') as $filename)
{
  echo 'Filename:'.$filename.;
}



// 算法2. 规矩系
// ================
// 主要用到 opendir(string dir)， readdir(string dir)， closedir(string dir)
if ($handle = opendir('C:\\Inetpub\\wwwroot\\test\\')) {
  echo "Files:\n";
  while (false !== ($file = readdir($handle))){
    echo "$file\n";
  }
  closedir($handle);
}


// 算法3. 函数系
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


// 算法4. 递归输出某目录下的所有文件
// ================
function listDir($dir){
  if (!is_dir($dir)) {
    return false;
  }

  if ($handle = opendir($dir)) {
    while (($file= readdir($handle)) !== false){
      if ($file == "." || $file == "..") {
        continue;
      }

      $curr_file = $dir.DIRECTORY_SEPARATOR.$file;

      if (is_dir($curr_file)) {
        echo "目录：", $file;
        // 递归取下级目录
        listDir($curr_file);

      } else{
        echo "文件：", $curr_file;
      }
    }

    closedir($handle);
  }
}
listDir($dir);


// 算法5. 递归获取某目录下的所有文件
// ================
function listDir($dir){
  $arr = [];

  if (!is_dir($dir)) {
    return $arr;
  }

  if ($handle = opendir($dir)) {
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


// 算法6. 队列
// ==================
function listDirByQueue($dir){
  $files = [];
  $queue = [$dir];
  while ($data=each($queue)) {
    $path=$data['value'];

    if(is_dir($path) && $handle=opendir($path)){
      while(($file = readdir($handle)) !== false){
        if ($file=='.'||$file=='..') {
          continue;
        }

        $files[] = $real_path=$path.'/'.$file;

        if (is_dir($real_path)) {
          $queue[] = $real_path;
        }
      }

      closedir($handle);
    }
    
  }
   return $files;
}
$dir = '/data/my_web/test';
var_dump(listDirByQueue($dir));



// 测试方法
// ================
// 记录开始与结束时间，然后取差值。
// 测试多次取平均结果作为最终成绩。

$stime=microtime(true);
//测试代码
//......
//......
$etime=microtime(true);
$total=($etime-$stime)*1000;
echo "{$total} Millisecond(s)".PHP_EOL;


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



// 结束语：
// ================
// 如果我们需要指定扩展名的列举目录内所有文件的话，推荐使用算法1的模式，代码如下：
foreach(glob('*.需要的扩展名') as $filename)
{
  echo 'Filename:'.$filename.;
}
// 如果取具体某一个目录下的文件，推荐算法2，如果需要取出所有子级，用4或5


