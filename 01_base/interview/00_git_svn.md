
svn、git 区别
-----------
1.GIT是分布式的，而SVN是集中式的

2.GIT把内容按元数据方式存储，而SVN是按文件：因为git目录是处于个人机器上的一个克隆版的版本库，它拥有中心版本库上所有的东西，例如标签，分支，版本记录等。​

3.GIT分支和SVN的分支不同：svn会发生分支遗漏的情况，而git可以同一个工作目录下快速的在几个分支间切换，很容易发现未被合并的分支，简单而快捷的合并这些文件。

4.GIT没有一个全局的版本号，而SVN有

5.GIT的内容完整性要优于SVN：GIT的内容存储使用的是SHA-1哈希算法。这能确保代码内容的完整性，确保在遇到磁盘故障和网络问题时降低对版本库的破坏。


集中式和分布式的区别：

 集中式版本控制系统：版本库是集中存放在中央服务器的，而干活的时候，用的都是自己的电脑，所以要先从中央服务器取得最新的版本，然后开始干活，干完活了，再把自己的活推送给中央服务器。​集中式版本控制系统最大的毛病就是必须联网才能工作。

 分布式版本控制系统：分布式版本控制系统根本没有“中央服务器”，每个人的电脑上都是一个完整的版本库，这样，你工作的时候，就不需要联网了，因为版本库就在你自己的电脑上。比方说你在自己电脑上改了文件A，你的同事也在他的电脑上改了文件A，这时，你们俩之间只需把各自的修改推送给对方，就可以互相看到对方的修改了。


SVN与Git优缺点比较(属面试常问提)
-------------
```
1．SVN优缺点
优点： 
1、 管理方便，逻辑明确，符合一般人思维习惯。 
2、 易于管理，集中式服务器更能保证安全性。 
3、 代码一致性非常高。 
4、 适合开发人数不多的项目开发。 

缺点： 
1、 服务器压力太大，数据库容量暴增。 
2、 如果不能连接到服务器上，基本上不可以工作，看上面第二步，如果服务器不能连接上，就不能提交，还原，对比等等。 
3、 不适合开源开发（开发人数非常非常多，但是Google app engine就是用svn的）。但是一般集中式管理的有非常明确的权限管理机制（例如分支访问限制），可以实现分层管理，从而很好的解决开发人数众多的问题。


2．Git优缺点

优点： 
1、适合分布式开发，强调个体。 
2、公共服务器压力和数据量都不会太大。 
3、速度快、灵活。 
4、任意两个开发者之间可以很容易的解决冲突。 
5、离线工作。 

缺点： 
1、学习周期相对而言比较长。 
2、不符合常规思维。 
3、代码保密性差，一旦开发者把整个库克隆下来就可以完全公开所有代码和版本信息。
```

