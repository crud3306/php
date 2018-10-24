

常见攻击概述
============
SQL注入：就是通过把 SQL 命令插入到Web表单提交或输入域名或页面请求的查询字符串，最终达到欺骗服务器执行恶意的 SQL 命令。防御：过滤特殊符号特殊符号过滤或转义处理（addslashes函数）；绑定变量，使用预编译语句；

XSS：跨站脚本（Cross-site scripting，通常简称为 XSS）是一种网站应用程序的安全漏洞攻击，是代码注入的一种。它允许恶意用户将代码注入到网页上，其他用户在观看网页时就会受到影响。这类攻击通常包含了 HTML 以及用户端脚本语言。防御：页面上直接输出的所有不确定(用户输入)内容都进行 html 转义；对用户输入内容格式做校验；script 脚本中不要使用不确定的内容；

CSRF:跨站请求伪造（英语：Cross-site request forgery），也被称为 one-click attack 或者 session riding，通常缩写为 CSRF 或者 XSRF， 是一种挟制用户在当前已登录的 Web 应用程序上执行非本意的操作的攻击方法;防御：验证 HTTP Referer 字段；在请求地址中（或 HTTP 头中）添加 token 并验证；

SSRF：模拟服务器对其他服务器资源进行请求，没有做合法性验证。构造恶意内网IP做探测，或者使用其余所支持的协议对其余服务进行攻击。防御：禁止跳转，限制协议，内外网限制，URL 限制。绕过：使用不同协议，针对IP，IP 格式的绕过，针对 URL，恶意 URL 增添其他字符，@之类的。301跳转 + dns rebindding。




xss
============
XSS攻击全称跨站脚本攻击，是为不和层叠样式表(Cascading Style Sheets, CSS)的缩写混淆，故将跨站脚本攻击缩写为XSS，XSS是一种在web应用中的计算机安全漏洞，它允许恶意web用户将代码植入到提供给其它用户使用的页面中。

XSS是指恶意攻击者利用网站没有对用户提交数据进行转义处理或者过滤不足的缺点，进而添加一些代码，嵌入到web页面中去。使别的用户访问都会执行相应的嵌入代码。从而盗取用户资料、利用用户身份进行某种动作或者对访问者进行病毒侵害的一种攻击方式。


主要原因：  
过于信任客户端提交的数据！

解决办法：  
不信任任何客户端提交的数据，只要是客户端提交的数据就应该先进行相应的过滤处理然后方可进行下一步的操作。


PHP中的相应函数
这里可能不全，想了解更多的看手册。
strip_tags($str, [允许标签])  #从字符串中去除 HTML 和 PHP 标记
htmlentities($str)函数    #转义html实体
html_entity_decode($str)函数    #反转义html实体
addcslashes($str, ‘字符’)函数     #给某些字符加上反斜杠
stripcslashes($str)函数          #去掉反斜杠
addslashes ($str )函数          #单引号、双引号、反斜线与 NULL加反斜杠
stripslashes($str)函数           #去掉反斜杠
htmlspecialchars()              #特殊字符转换为HTML实体
htmlspecialchars_decode()       #将特殊的 HTML 实体转换回普通字符




csrf
============
CSRF（Cross Site Request Forgery, 跨站域请求伪造）是一种网络的攻击方式，它在 2007 年曾被列为互联网 20 大安全隐患之一。

CSRF 攻击实例

CSRF 攻击可以在受害者毫不知情的情况下以受害者名义伪造请求发送给受攻击站点，从而在并未授权的情况下执行在权限保护之下的操作。

比如说，受害者 Bob 在银行有一笔存款，通过对银行的网站发送请求 http://bank.example/withdraw?account=bob&amount=1000000&for=bob2 可以使 Bob 把 1000000 的存款转到 bob2 的账号下。通常情况下，该请求发送到网站后，服务器会先验证该请求是否来自一个合法的 session，并且该 session 的用户 Bob 已经成功登陆。黑客 Mallory 自己在该银行也有账户，他知道上文中的 URL 可以把钱进行转帐操作。Mallory 可以自己发送一个请求给银行：http://bank.example/withdraw?account=bob&amount=1000000&for=Mallory。但是这个请求来自 Mallory 而非 Bob，他不能通过安全认证，因此该请求不会起作用。这时，Mallory 想到使用 CSRF 的攻击方式，他先自己做一个网站，在网站中放入如下代码： src=”http://bank.example/withdraw?account=bob&amount=1000000&for=Mallory ”，并且通过广告等诱使 Bob 来访问他的网站。当 Bob 访问该网站时，上述 url 就会从 Bob 的浏览器发向银行，而这个请求会附带 Bob 浏览器中的 cookie 一起发向银行服务器。大多数情况下，该请求会失败，因为他要求 Bob 的认证信息。但是，如果 Bob 当时恰巧刚访问他的银行后不久，他的浏览器与银行网站之间的 session 尚未过期，浏览器的 cookie 之中含有 Bob 的认证信息。这时，悲剧发生了，这个 url 请求就会得到响应，钱将从 Bob 的账号转移到 Mallory 的账号，而 Bob 当时毫不知情。等以后 Bob 发现账户钱少了，即使他去银行查询日志，他也只能发现确实有一个来自于他本人的合法请求转移了资金，没有任何被攻击的痕迹。而 Mallory 则可以拿到钱后逍遥法外。

csrf 防御策略
---------
验证 HTTP Referer 字段；  
在请求地址中添加 token 并验证；  
在 HTTP 头中自定义属性并验证。  

对于用户修改删除等敏感操作最好都使用post 操作 。




sql注入
============
所谓SQL注入，就是通过把SQL命令插入到Web表单提交或输入域名或页面请求的查询字符串，最终达到欺骗服务器执行恶意的SQL命令。具体来说，它是利用现有应用程序，将（恶意的）SQL命令注入到后台数据库引擎执行的能力，它可以通过在Web表单中输入（恶意）SQL语句得到一个存在安全漏洞的网站上的数据库，而不是按照设计者意图去执行SQL语句。 [1]  比如先前的很多影视网站泄露VIP会员密码大多就是通过WEB表单递交查询字符暴出的，这类表单特别容易受到SQL注入式攻击．


防护：
1 永远不要信任用户的输入。对用户的输入进行校验，可以通过正则表达式，或限制长度；对单引号和
双"-"进行转换等。 php开启魔术引号。或用addslashes($str)。  

2 永远不要直接拼装sql，封装好db操作类，调用时参数化。  

3 使用mysql预处理。  
	mysql预处理的执行原理是：  
	先预发送一个sql模板过去，  
	再向mysql发送需要查询的参数，  
	就好像填空题一样，不管参数怎么注入，mysql都能知道这是变量，不会做语义解析，起到防注入的效果，这是在mysql中完成的。  


注入实例：
select count(*) from admin where username = 'test' and password = 'test' 
但是当输入username="'or 1=1--"时，在java程序中String类型变量sql 为 
" select count(*) from admin where username = ' 'or 1=1-- ' and password = ' ' " 
这句sql语句在执行时，"--" 将"and"及之后的语句都注释掉了，相当于执行了 
select count(*) from admin where username = ' 'or 1=1 
而1=1是永远为true的，所以该语句的执行结果实际上是admin表的行数，而不是符合输入的username和password的行数，从而顺利通过验证。 

这个例子中虽然注入的过程非常简单，但可能的危害却很大。如果在用户名处输入 
"' or 1=1; drop table admin --" 
由于SQL Server支持多语句执行，就可以把admin表drop掉了，后果不堪设想。











