

文件
```php
<? 
$file_name = "xxx.rar";     //下载文件名  
$file_dir = "./up/";        //下载文件存放目录  
//检查文件是否存在  
if (! file_exists ( $file_dir . $file_name )) {  
    echo "文件找不到";  
    exit ();  
} else {  
    //打开文件  
    $file = fopen ( $file_dir . $file_name, "r" );  
    //输入文件标签   
    Header ( "Content-type: application/octet-stream" );  
    Header ( "Accept-Ranges: bytes" );  
    Header ( "Accept-Length: " . filesize ( $file_dir . $file_name ) );  
    Header ( "Content-Disposition: attachment; filename=" . $file_name );  
    //输出文件内容   
    //读取文件内容并直接输出到浏览器  
    echo fread ( $file, filesize ( $file_dir . $file_name ) );  
    fclose ( $file );  
    exit ();  
}  
```
