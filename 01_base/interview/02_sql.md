
mysql方面的：
===========

请说出mysql常用存储引擎？memory存储引擎的特点？  
-----------
Myisam、InnoDB、memory  
memory的特点是将表存到内存中，数度快，重启后数据丢失   

myisam
不支持事务，表锁
存储文件位置

InnoDB
支持事务，行锁
存储文件位置



索引算法Hash与BTree的区别
-----------
https://blog.csdn.net/u011305680/article/details/55520853  


select * from table where (ID = 10) or (ID = 32) or (ID = 22) or (ID = 76) or (ID = 13) or (ID = 44) 让结果按10，32，22，76，13，44的顺序检索出来,请问如何书写?
------------
```sql
select * from table
where id in (10,32,22,76,13,44)
order by charindex(id,'10,32,22,76,13,44') desc  
```

mysql中删除重复记录，并保留重复数据中的一条数据
------------
假设为admin表
```
# 保留的是id最小的记录  
DELETE FROM `admin` WHERE id NOT IN(SELECT * FROM(SELECT id FROM `admin` GROUP BY username)AS b)
```
```
# 保留的是id最大的记录  
DELETE FROM `admin` WHERE id NOT IN(SELECT * FROM(SELECT max(id) FROM `admin` GROUP BY username)AS b)
```

理解：  

先从里面的SQL开始看

1、SELECT id FROM `user` GROUP BY username  根据名字分组查询出每组的ID。  
2、SELECT * FROM(SELECT id FROM `user` GROUP BY username) AS b   

这句话中有2个疑问点：

第一、为什么要套这样一个select？因为更新数据时使用了查询，而查询的数据又做更新的条件，mysql不支持这种方式，如果不套上这个select查询，那么将会报1093 -  You can't specify target table 'user' for update in FROM clause错误。

第二、这句话中一定要取别名，不然会报1248 - Every derived table must have its own alias 错误

3、结合上面的分析来看一下整个的SQL语句理解，先将分组的ID查出来，然后删除USER表中ID 不在分组ID中的数据，那么就实现效果了。
```
delete from 表名 where  ID not in (select * from (select  id from 表名 group by 分组的列名) 别名)  
```


内存限制 5m, 文件有 50m, 存的都是整数, 让我求出前 10 个最大的数.
------------


当多表关联查询很慢的时候, 有什么办法加速查询..?
------------
进行多次单表查询, 再程序中合并结果.









nosql方面的：
===========

memcache、redis 区别，及应用场景  
-----------
相同点  
1 都是在内存中进行数据的存取
2 都支持k/v的方式存取数据
  
不同点  
1)、数据支持类型
memcache只有string类型的数据。  
Redis不仅仅支持简单的string类型数据，同时还提供list，set，hash等数据结构的存储。

2)、存储方式
Memecache把数据全部存在内存之中，断电后会数据会丢失。
Redis支持数据的持久化，可以将内存中的数据保持在磁盘中，重启的时候可以再次加载进行使用。  
3）value大小
redis最大可以达到1GB，而memcache只有1MB

4)、使用底层模型不同
它们之间底层实现方式 以及与客户端之间通信的应用协议不一样。
Redis直接自己构建了VM机制，因为一般的系统调用系统函数的话，会浪费一定的时间去移动和请求。

总结：
1.Redis使用最佳方式是全部数据in-memory。  
2.Redis更多场景是作为Memcached的替代者来使用。  
3.当需要除key/value之外的更多数据类型支持时，使用Redis更合适。  
4.当存储的数据不能被剔除时，使用Redis更合适。  





linux：
-----------




服务器
-----------
Apache与Nginx的优缺点比较
-----------
1、nginx相对于apache的优点：
轻量级，比apache 占用更少的内存及资源。高度模块化的设计，编写模块相对简单  
抗并发，nginx 处理请求是异步非阻塞，多个连接（万级别）可以对应一个进程，而apache 则是阻塞型的，是同步多进程模型，一个连接对应一个进程，在高并发下nginx   能保持低资源低消耗高性能  
nginx处理静态文件好，Nginx 静态处理性能比 Apache 高 3倍以上  
  
2、apache 相对于nginx 的优点：  
apache 的rewrite 比nginx 的rewrite 强大 ，模块非常多，基本想到的都可以找到 ，比较稳定，少bug ，nginx 的bug 相对较多  
  
3：原因：这得益于Nginx使用了最新的epoll（Linux 2.6内核）和kqueue（freebsd）网络I/O模型，而Apache则使用的是传统的select模型。目前Linux下能够承受高并发访问的 Squid、Memcached都采用的是epoll网络I/O模型。   处理大量的连接的读写，Apache所采用的select网络I/O模型非常低效。  