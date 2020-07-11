

下载安装
-----------
官网下载：http://pecl.php.net/package/yar 最新的版本 yar-1.2.4.tgz  
```shell
tar -zxvf yar-1.2.4.tgz
cd yar-1.2.4
/usr/local/php/bin/phpize
./configure --with-php-config=/usr/local/php/bin/php-config
make && make install
```
  
成功之后，提示我们 yar.so 扩展在已经在/usr/local/php/lib/php/extensions/no-debug-zts-20100525/ 下了。  

我们vi编辑一下 php.ini ,最后面加上yar.so扩展，然后重启一下 apache 或者php-pfm就可以了。  
  
> vi /usr/local/php/etc/php.ini   
> [yar]   
> extension=yar.so   
  
  
  
开始使用  
-----------
和其他的rpc框架一样，yar也是server/client模式，所以，我们也一样，开始写一个简单的例子来说下如何调用。  
  
yar_server.php表示服务器端  
```php
class API {
	public function api($parameter, $option = "foo") {
		return $parameter;
	}

	protected function client_can_not_see() {

	}
}
$service = new Yar_Server(new API());
$service->handle();
```
好，我们在浏览器里运行一下，就会出现如下图所示的输出。很高端啊！！！鸟哥说这样做的用途是可以一目了然的知道我这个rpc提供了多少接口，把api文档都可以省略了。  
  
好，我们开始写yar_client.php 这个是客户端：  
```php
$client = new Yar_Client("http://127.0.0.1/yar_server.php");
echo $client->api('helo word');
```





