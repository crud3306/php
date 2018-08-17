<?php


// ================
// 创建目录
// ================
bool mkdir ( string $pathname [, int $mode = 0777 [, bool $recursive = FALSE [, resource $context ]]] )

// 创建多级目录
// ================

// 1.使用递归的思想
// ----------------
function mkdirs_2($path){
	if (!is_dir($path)) {
		mkdirs_2(dirname($path));

		if(!mkdir($path, 0777)){
			return false;
		}
	}

	return true;
}

$path2 = 'sdfs/sds/sds/s/s/sss';
var_dump(mkdirs_2($path2)); //true;


// 2 mkdir() 的第三个参数设为true
// ----------------
function mkdirs_1($path, $mode = 0777){
	if(is_dir($path)){
		echo 'dir is exist...';
		return true;

	} else {
		if (mkdir($path, $mode, true)) {
			return true;
		} else {
			return false;
		}
	}
}
$path1 = 'a/b/c/d/e';
var_dump(mkdirs_1($path1)); //string '创建成功' (length=12)


// ================
// 删除目录
// ================
bool rmdir ( string $dirname [, resource $context ] )
// 例：
rmdir('/data/tmp/aa');


// ================
// 更改文件、目录权限
// ================
bool chmod ( string $filename , int $mode )

// 例：
chmod('/data/tmp/aa', 755);






// ================
// 创建文件
// ================
bool touch ( string $filename [, int $time = time() [, int $atime ]] )
// 例：
touch('test.html');



// ================
// 删除文件
// ================
bool unlink ( string $filename [, resource $context ] )
// 例：
unlink('test.html');














