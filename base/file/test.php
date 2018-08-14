<?php

$dir = '/data/my_git/my_open/php-start';

// if ($handle = opendir('/data/my_git/my_open/php-start')){
//   echo "Files:\n";
//   while (false !== ($file = readdir($handle))){
//     echo "$file\n";
//   }
//   closedir($handle);
// }


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
