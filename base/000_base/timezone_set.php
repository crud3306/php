<?php


// 参考地址：
// ============
// https://blog.csdn.net/json_ligege/article/details/78538851
// https://blog.csdn.net/json_ligege/article/details/78563597




// 关于PHP的时区设置方法
// =======================
// 1、修改php.ini

// 在php.ini中找到data.timezone =去掉它前面的;号，
// 然后设置data.timezone ="Asia/Shanghai";即可。


// 2、在程序PHP 5以上版本的程序代码中使用函数

// 获取当前的时区
echo date_default_timezone_get();

// 使用前，可以先判断当前版本php是否支持date_default_timezone_set方法
// function_exists('date_default_timezone_set');

// date_default_timezone_set("Etc/GMT");//这是格林威治标准时间,得到的时间和默认时区是一样的
// date_default_timezone_set("Etc/GMT+8");//这里比林威治标准时间慢8小时
// date_default_timezone_set("Etc/GMT-8");//这里比林威治标准时间快8小时
// date_default_timezone_set('PRC'); //设置中国时区

date_default_timezone_set('Asia/Shanghai');
// 或者
ini_set('date.timezone','Asia/Shanghai');



// 再次获取当前的时区
echo date_default_timezone_get();

// 测试设置时区后的当前时间
echo date('Y-m-d H:i:s');


// 一些常用的时区标识符说明：
// ======================
// Asia/Shanghai – 上海GMT+8:00
// Asia/Chongqing – 重庆
// Asia/Urumqi – 乌鲁木齐
// Asia/Hong_Kong – 香港
// Asia/Macao – 澳门
// Asia/Taipei – 台北
// Asia/Singapore – 新加坡










