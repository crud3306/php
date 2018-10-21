

window（A）中用window.open打开了window（B），如何从窗口B调用窗口A中的内容？A、B仅仅是窗口的代号，不是窗口名字
-----------
window.opener.document.getElementById()


什么是ajax？ajax的原理是什么？ajax的核心技术是什么？ajax的优缺点是什么？
------------------
ajax是asynchronous javascript and xml的缩写，是javascript、xml、css、DOM等多个技术的组合。'$'是jQuery的别名.  

页面中用户的请求通过ajax引擎异步地与服务器进行通信，服务器将请求的结果返回给这个ajax引擎，
最后由这个ajax引擎来决定将返回的数据显示到页面中的指定位置。Ajax最终实现了在一个页面的指定位置可以加载另一个页面所有的输出内容。  
这样就实现了一个静态页面也能获取到数据库中的返回数据信息了。所以ajax技术实现了一个静态网页在不刷新整个页面的情况下与服务器通信，减少了用户等待时间，同时也从而降低了网络流量，增强了客户体验的友好程度。  

Ajax的优点是：  
1. 减轻了服务器端负担，将一部分以前由服务器负担的工作转移到客户端执行，利用客户端闲置的资源进行处理；  
2. 在只局部刷新的情况下更新页面，增加了页面反应速度，使用户体验更友好。  

Ajax的缺点是:  
不利于seo推广优化，因为搜索引擎无法直接访问到ajax请求的内容。  

ajax的核心技术是XMLHttpRequest，它是javascript中的一个对象。  



jquery是什么？jquery简化ajax后的方法有哪些？
----------------
```
jQuery是Javascript的一种框架。
$.get(),$.post(),$.ajax()。$是jQuery对象的别名。

代码如下：
$.post(异步访问的url地址 , {'参数名' : 参数值} , function(msg){
	$("#result").html(msg);
}, 'json');

$.get(异步访问的url地址 , {'参数名' : 参数值} , function(msg){
	$("#result").html(msg);
}, 'json');

$.ajax({
	type: "post",
	url: loadUrl,
	cache:false,
	data: "参数名=" + 参数值,
	success: function(msg) {
		$("#result").html(msg);
	}
});
```


NPM 、Yarn 概念
-------------
NPM 是 Node.js（一个基于 Google V8 引擎的 JavaScript 运行环境）的包管理和分发工具。

Yarn 是 Facebook 在 2016 年 10 月开源的一个新的包管理器，用于替代现有的 NPM 客户端或者其他兼容 NPM 仓库的包管理工具。Yarn 在保留 NPM 原有工作流特性的基础上，使之变得更快、更安全、更可靠。  






