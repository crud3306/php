
在php程序编写中，养成写入log文件的编程习惯，是一个很好的编程习惯，程序员都应该学会这种编程思想，不要太浮躁。前期编程的不严谨，往往会带来后期维护和调式的困难，付出的时间和精力将会更多。

error_log() 是发送错误信息到某个地方的一个函数，在程序编程中比较常见，尤其是在程序调试阶段。



格式
------------
bool error_log ( string $message [, int $message_type = 0 [, string $destination [, string $extra_headers ]]] )
把错误信息发送到 web 服务器的错误日志，或者到一个文件里。

message  
应该被记录的错误信息。  

message_type   
设置错误应该发送到何处。使用 操作系统的日志机制或者一个文件，取决于 error_log 指令设置了什么。  
可能的信息类型有以下几个：
```
0	message 发送到 PHP 的系统日志。 这是个默认的选项。
iis服务器运行调试php程序错误信息生成log文件在哪里
1	message 发送到参数 destination 设置的邮件地址。 第四个参数 extra_headers 只有在这个类型里才会被用到。
2	不再是一个选项。
3	message 被发送到位置为 destination 的文件里。 字符 message 不会默认被当做新的一行，而是追加到行末。
4	message 直接发送到 SAPI 的日志处理程序中。
destination 
目标。它的含义描述于以上，由 message_type 参数所决定。
```

extra_headers 
额外的头。当 message_type 设置为 1 的时候使用。 该信息类型使用了 mail() 的同一个内置函数。


返回值
成功时返回 TRUE， 或者在失败时返回 FALSE。


error_log()可能出现的问题
-----------
问题一：  
Warning: error_log() [function.error-log]: failed to open stream: Permission denied in ...on line ...

上述错误的出现，是因为文件没有写权限，开启该目录的文件写权限即可。

问题二：  
log文件为什么不能换行？  

使用error_log()写入log文件，会发现文字是没有换行的，这给阅读带来很大的困难，需要改进下。经研究，使用如下代码，可以写入换行的信息。 
```
$str = "这是条错误信息。".PHP_EOL;
//$str = "这是条错误信息。\r\n";
error_log($str, 3, 'errors.log');

```


