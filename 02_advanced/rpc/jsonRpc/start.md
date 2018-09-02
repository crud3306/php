

https://www.cnblogs.com/clnchanpin/p/7058848.html
 
技术简单介绍  
------------
json-rpc是基于json的跨语言远程调用协议。比xml-rpc、webservice等基于文本的协议数据传输格小；相对hessian、java-rpc等二进制协议便于调试、实现、扩展，是很优秀的一种远程调用协议。眼下主流语言都已有json-rpc的实现框架，java语言中较好的json-rpc实现框架有jsonrpc4j、jpoxy、json-rpc。三者之中jsonrpc4j既可独立使用。又可与spring无缝集合，比較适合于基于spring的项目开发。  
  
一、JSON-RPC协议描写叙述  
------------
json-rpc协议很easy，发起远程调用时向服务端数据传输格式例如以下：  
> { "method": "sayHello", "params": ["Hello JSON-RPC"], "id": 1}  
  
參数说明：  
> method： 调用的方法名  
> params： 方法传入的參数。若无參数则传入 []  
> id ： 调用标识符。用于标示一次远程调用过程  
  
server其收到调用请求，处理方法调用，将方法效用结果效应给调用方；返回数据格式：
```json
{   
	"result": "Hello JSON-RPC",         
	"error": null,       
	"id": 1
}   
```                     
參数说明:
> result: 方法返回值。若无返回值。则返回null。若调用错误，返回null。  
> error ：调用时错误，无错误返回null。  
> id : 调用标识符，与调用方传入的标识符一致。  
  
以上就是json-rpc协议规范，很easy，小巧。便于各种语言实现。  
  

二、JSON-RPC简单演示样例
------------
2.3、PHPclient调用演示样例
基于json-rpc-php的PHPclient调用演示样例：
```php
include(dirname(__FILE__)."/lib/client/JsonRpcClient.php");

$client = new JsonRpcClient("http://10.13.49.234:8080/index.json");

$response = $client->getSystemProperties();
echo $response->result;
```
  
  
2.3、JavaScriptclient调用演示样例  
基于jsonrpcjs的JavaScriptclient调用演示样例：  
```javascript
var rpc = new jsonrpc.JsonRpc('http://127.0.0.1:8080/index.json');

rpc.call('getSystemProperties', function(result){
	alert(result);
});
```
  
   
  
2.4、直接GET请求进行调用  
无需不论什么client。仅仅需手工拼接參数进行远程调用，请求URL例如以下：  
> http://127.0.0.1:8080/index.json?method=getSystemProperties&id=3325235235235&params=JTViJTVk  
參数说明:

> method : 方法名  
> params ：调用參数。json的数组格式[], 将參数需先进行url编码，再进行base64编码  
> id : 调用标识符，随意值。  
  

三、JSON-RPC总结  
------------
json-rpc是一种很轻量级的跨语言远程调用协议。实现及使用简单。  
  
仅需几十行代码，就可以实现一个远程调用的client。方便语言扩展client的实现。  
  
server端有php、java、python、ruby、.net等语言实现，是很不错的及轻量级的远程调用协议。  
  
  
