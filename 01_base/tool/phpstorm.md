


phpstorm设置tab为4个空格缩进：
----------
进入：File -> Setting ->Editor-> Code Style -> PHP，进去就可以看到设置的地方，右侧不要勾选 "Use tab character"


显示空格编进提示小点
----------
菜单view - active editor -> 勾选show whitespaces  


显示行号
----------
菜单view - active editor -> 勾选show line numbers    


phpstorm更改编码
----------
1 设置全局的默认编码  
phpstorm默认的编码是utf-8，如果你在项目中需要其它编码可以这么改：File-->Setting-->Editor(左侧)-->File Encodings，然后就可以更改编码了。

2 单独更改某页面的编码  
在页面空白处右键 -- 选择file encoding，即可设置  

但是在php页面中就算编码设置了utf-8还是会乱码，这时候就需要添加下面这句话了：
```
 <?php
header('Content-Type:textml;charset=utf-8');         //没错就是这句！！要放在输出之前
```


PhpStorm换行符的设置方法
----------
最近一个项目，之前的换行都是\n，最近发现有些代码变成\r\n了。

找了一下原因，原来PhpStorm在默认情况下，新建文件都是使用系统换行符，所以很多文件都变成\r\n了。  
其设置方法如下：

在菜单 Setting -> Editor -> Code Style -> Line separator 里，把System-Development改成Unix and OS X (\n)就行。







