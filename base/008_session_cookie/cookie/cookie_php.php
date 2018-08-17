<?php


// 什么是 Cookie？
// =====================
// cookie 常用于识别用户。cookie 是服务器留在用户计算机中的小文件。每当相同的计算机通过浏览器请求页面时，它同时会发送 cookie。通过 PHP，您能够创建并取回 cookie 的值。



// 1 如何创建 cookie？
// =====================
// setcookie() 函数用于设置 cookie。

// 注释：setcookie() 函数前不能有输出

// 语法
setcookie(name, value, expire, path, domain);
// 例：
setcookie('a', '123', time()+3600, '/', 'xxxx.com');


// 例子

//在下面的例子中，我们将创建名为 "user" 的 cookie，把为它赋值 "Alex Porter"。我们也规定了此 cookie 在一小时后过期：

setcookie("user", "Alex Porter", time()+3600);

// 注释：在发送 cookie 时，cookie 的值会自动进行 URL 编码，在取回时进行自动解码（为防止 URL 编码，请使用 setrawcookie() 取而代之）。

// 如何取回 Cookie 的值？
// =====================
// PHP 的 $_COOKIE 变量用于取回 cookie 的值。


// 在下面的例子中，我们取回了名为 "user" 的 cookie 的值，并把它显示在了页面上：

// Print a cookie
echo $_COOKIE["user"];

// A way to view all cookies
print_r($_COOKIE);


// 在下面的例子中，我们使用 isset() 函数来确认是否已设置了 cookie：

if (isset($_COOKIE["user"]))
	echo "Welcome " . $_COOKIE["user"] . "!<br />";
else
	echo "Welcome guest!<br />";




// 如何删除 cookie？
// =====================
// 当删除 cookie 时，您应当使过期日期变更为过去的时间点。

// 删除的例子：
// set the expiration date to one hour ago
setcookie("user", "", time()-3600);





