  
动态语言静态化  
  
  
  
什么是动态语言静态化？  
-------------
将现在php等动态语言的逻辑代码生成为静态html文件，用户访问动态脚本重定向到静态html文件的过程。    
  
注意：对实时性要求不高的页面，才适合做动态语言静态化。  
  
  
动态语言静态化原因  
-------------
动态脚本通常会做逻辑计算和数据查询，访问量越大，服务器压力越大    
访问量大时可能会造成cpu负载过高，数据库服务器压力过大    
静态化可以减低逻辑处理压力，降低数据库服务器查询压力  
  
  
  
静态化实现方式  
--------------
1 使用模板引擎  
  
可以使用smarty的缓存机制生成静态html缓存文件   
> $smarty->cache_dir = $ROOT."/cache"; // 缓存目录  
> $smarty->caching = true; // 是否开启缓存  
> $smarty->cache_lifetime = "3600"; // 缓存时间  
使用模板  
> $smarty->display(string template[, string cache_id[, string compile_id]])  
   
清理缓存相关方法  
> $smarty->clear_all_cache(); // 清除所有缓存  
> $smarty->clear_cache('file.html'); // 清除指定的缓存  
> $smarty->clear_cache('article.html', $art_id); // 清除同一个模板下的指定缓存号的缓存
    
  
  
2 利用ob系列的函数   
> ob_start(); // 打开输出控制缓冲  
> ob_get_contents(); // 返回输出缓冲区内容  
> ob_clean(); // 清空输出缓冲区  
> ob_end_flush(); // 冲刷出（送出）输出缓冲区内容并关闭缓冲  
  
使用：  
> ob_start(); // 先打开输出缓冲  
> 然后这里是相送输出到页面的信息  
> ...  
> ob_get_contents();  // 获取缓冲区内容  
> ob_end_flush();  
> fopen()写入  
  
可以判断文件的inode修改时间，判断是否过期  
使用filectime函数  
  
ob实例：  
```php
<?php
$id = !empty($_GET['id']) ? $_GET['id'] : '';

$cache_name = md5(__FILE__).'-'.$id.'.html';
$cache_life_time = 3600;
if (filectime(__FILE__) <= filectime($cache_name)
    && file_exists($cache_name) 
    && filectime($cache_name)+$cache_lift_time > time()) 
{
	include $cache_name;
	exit;
}

ob_start();

?>
<b>this is my script, id=<?php echo $id; ?></b>

<?php
$content = ob_get_contents();
//var_dump($content);

ob_end_flush();

$handle = fopen($cache_name, 'w');
fwrite($handle, $content);
flose($handle);

```
  






























