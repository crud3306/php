<?php

$zip = new ZipArchive();
$zip->open($zip_name);
$zip->extractTo($aim_dir, [$keep_file(string,array)]); // 从zip中提取一个或多个，或全部了文件到某个目录
$zip->deleteName($dir/$file Name); // 从zip中删除文件或文件夹
$zip->addFile($file_to_add); // 向zip中添加文件