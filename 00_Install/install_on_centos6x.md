


安装PHP5.5.12
---------------
安装依赖关系   
libiconv库为需要做转换的应用提供了一个iconv()的函数，以实现一个字符编码到另一个字符编码的转换。 错误提示：configure: error: Please reinstall the iconv library.  
```shell
wget http://ftp.gnu.org/pub/gnu/libiconv/libiconv-1.14.tar.gz
tar zxvf libiconv-1.14.tar.gz
cd libiconv-1.14
./configure --prefix=/usr/local/libiconv
make && make install
cd ..
```
  
  
libmcrypt是加密算法扩展库。 错误提示：configure: error: Cannot find imap library (libc-client.a). Please check your c-client installation.  
```shell
wget http://iweb.dl.sourceforge.net/project/mcrypt/Libmcrypt/2.5.8/libmcrypt-2.5.8.tar.gz
tar zxvf libmcrypt-2.5.8.tar.gz
cd libmcrypt-2.5.8
./configure
make && make install
cd ..
```


Mhash是基于离散数学原理的不可逆向的php加密方式扩展库，其在默认情况下不开启。   mhash的可以用于创建校验数值，消息摘要，消息认证码，以及无需原文的关键信息保存 错误提示：configure: error: “You need at least libmhash 0.8.15 to compile this program. http://mhash.sf.net/”  
```shell
wget http://blog.s135.com/soft/linux/nginx_php/mhash/mhash-0.9.9.9.tar.gz
tar zxvf mhash-0.9.9.9.tar.gz
cd mhash-0.9.9.9
./configure
make && make install
cd ..
```
这个地址可能失败，可去网上再找  
  
  


mcrypt 是 php 里面重要的加密支持扩展库，Mcrypt扩展库可以实现加密解密功能，就是既能将明文加密，也可以密文还原。  
```shell
wget http://iweb.dl.sourceforge.net/project/mcrypt/MCrypt/2.6.8/mcrypt-2.6.8.tar.gz
tar zxvf mcrypt-2.6.8.tar.gz
cd mcrypt-2.6.8
./configure
make && make install
cd ..
```
  
编译mcrypt可能会报错：configure: error: *** libmcrypt was not found
有两种解决方法  
方法1:  
执行 export LD_LIBRARY_PATH=/usr/local/lib: LD_LIBRARY_PATH 
  
方法2:
vi /etc/ld.so.conf
最后一行添加
/usr/local/lib/
载入
ldconfig

编译mcrypt可能会报错：/bin/rm: cannot remove `libtoolT': No such file or directory
修改 configure 文件，
vi configure
把RM='$RM'改为RM='$RM -f' 这里的$RM后面一定有一个空格。 如果后面没有空格，直接连接减号，就依然会报错。

报错后，按说明调整后，重新./configure


正式开始编译php！
---------------------------
按照标准，给php-fpm创建一个指定的用户和组
创建群组
groupadd www
创建一个用户，不允许登陆和不创主目录
useradd -s /sbin/nologin -g www -M www

编译php
-----------
注：http://mirrors.sohu.com/php/下有许多php版本，选择下载一个
wget http://mirrors.sohu.com/php/php-5.6.9.tar.gz
tar zxvf php-5.6.9.tar.gz
cd php-5.6.9
./configure --prefix=/usr/local/php --with-config-file-path=/usr/local/php/etc --enable-fpm --with-fpm-user=www --with-fpm-group=www --with-mysql=mysqlnd --with-mysqli=mysqlnd --with-pdo-mysql=mysqlnd --with-iconv-dir --with-freetype-dir --with-jpeg-dir --with-png-dir --with-zlib --with-libxml-dir=/usr --enable-xml --disable-rpath --enable-bcmath --enable-shmop --enable-sysvsem --enable-inline-optimization --with-curl --with-curlwrappers --enable-mbregex --enable-mbstring --with-mcrypt --enable-ftp --with-gd --enable-gd-native-ttf --with-openssl --with-mhash --enable-pcntl --enable-sockets --with-xmlrpc --enable-zip --enable-soap --without-pear --with-gettext --disable-fileinfo --enable-opcache

注意：#--enable-maintainer-zts #启用线程安全, Zend Loader必须用noZTS，所以不能带上此参数

对比下面的，这个是公司内部编译时的参数：
-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=
./buildconf --force

export RE2C=/usr/local/bin/re2c

./configure --prefix=/usr/local/php \
--with-config-file-path=/usr/local/php/etc \
--with-iconv-dir=/usr/local/iconv \
--with-libxml-dir=/usr \
--with-freetype-dir --with-jpeg-dir --with-png-dir --with-zlib \
--enable-xml --disable-rpath --enable-discard-path --enable-safe-mode --enable-bcmath --enable-shmop \
--enable-sysvsem --enable-inline-optimization \
--with-curl=/usr/local/curl/ --with-curlwrappers \
--enable-mbregex --enable-fastcgi --enable-fpm --enable-force-cgi-redirect --enable-mbstring \
--with-mcrypt --with-gd --enable-gd-native-ttf --with-openssl --with-mhash \
--enable-pcntl --enable-sockets \
--with-ldap --with-ldap-sasl --with-xmlrpc \
--enable-zip --enable-soap --enable-suhosin --enable-ftp \
--enable-dom --disable-ipv6 --enable-calendar --enable-opcache \
--with-gettext --with-bz2 --with-libdir=lib64 \
--with-pdo-mysql=mysqlnd --with-mysqli=mysqlnd --with-mysql=mysqlnd \
--enable-exif

#--enable-maintainer-zts #启用线程安全,Zend Loader必须用noZTS
make clean
make "ZEND_EXTRA_LIBS='-liconv -L/usr/local/lib' "
-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=

make && make install


修改fpm配置php-fpm.conf.default文件名称
cp /usr/local/php/etc/php-fpm.conf.default /usr/local/php/etc/php-fpm.conf

复制php.ini配置文件
cp php.ini-production /usr/local/php/etc/php.ini

复制php-fpm启动脚本到init.d
cp sapi/fpm/init.d.php-fpm /etc/init.d/php-fpm

赋予执行权限
chmod +x /etc/init.d/php-fpm

添加为启动项
chkconfig --add php-fpm

设置开机启动
chkconfig php-fpm on

立即启动php-fpm
service php-fpm start
#或者
/etc/init.d/php-fpm start


配置php错误日志
-----------------
1.修改php-fpm.conf中配置 没有则增加
catch_workers_output = yes
error_log = log/error_log

2.修改php.ini中配置，没有则增加
display_errors = Off
log_errors = On
error_log = "/usr/local/php/var/log/error_log"
error_reporting=E_ALL&~E_NOTICE

3.重启php-fpm，
当PHP执行错误时就能看到错误日志在"/usr/local/php/var/log/error_log"中了

请注意：

1. php-fpm.conf 中的php_admin_value[error_log] 参数 会覆盖php.ini中的 error_log 参数
所以确保你在phpinfo()中看到的最终error_log文件具有可写权限并且没有设置php_admin_value[error_log] 参数，否则错误日志会输出到php-fpm的错误日志里。

2.找不到php.ini位置，使用php的phpinfo()结果查看

