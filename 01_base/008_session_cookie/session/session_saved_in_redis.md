



方式1：直接修改php.ini配文件的方式
-----------
session.save_handler = redis    # 默认的设置是file，这里改成redis  
session.save_path = "tcp://192.168.2.11:6379?auth=passwd" # 这里填redis的连接配置参数  

// ;session.save_path = "tcp://192.168.2.11:6379"  # 如果redis不带密码，则使用这种配置  

注意：此方式简单，但一般不这么用  






方式2：通过session_set_save_hanler()函数
-----------
// 主要使用函数 
bool session_set_save_hanler(callback open, callback close, callback read, callback write, callback destory, callback gc)  


session_set_save_handler 函数各参数作用如下：  
  
open    当session打开时调用此函数。接收两个参数，第一个参数是保持session的路径，第二个参数是session的名字  

close   当session操作完成时调用此函数。不接收参数。  

read    以session ID作为参数。通过session ID从数据存储方中取得数据，并返回此数据。如果数据为空，可以返回一个空字符串。此函数在调用session_start 前被触发  

write   当数据存储时调用。有两个参数，一个是session ID，另外一个是session的数据  

destroy 当调用session_destroy 函数时触发destroy函数。只有一个参数 session ID  

gc  当php执行session垃圾回收机制时触发  

注意：  
在使用该函数前，先把php.ini配置文件的session.save_handler选项设置为user，否则session_set_save_handle 不会生效。(但实际使用用时，发现这个不设置也可以。)  



```php
class SessionManager{

    private $redis;
    private $sessionExpireTime=30;//redis，session的过期时间为30s

    public function __construct(){
        $this->redis = new Redis();//创建phpredis实例
        $this->redis->connect('127.0.0.1', 6379);//连接redis
        // $this->redis->auth("107lab"); //授权

        $session_name = ini_get('session.name');
        // fix for swf ie.swfupload
        if (!empty($_POST[$session_name])) {
            self::$flash = true;
            session_id($_POST[$session_name]);
        }


        session_set_save_handler(
            array($this,"open"),
            array($this,"close"),
            array($this,"read"),
            array($this,"write"),
            array($this,"destroy"),
            array($this,"gc")
        );

        session_start();
    }

    public function open($path,$name){
        return true;
    }

    public function close(){
        return true;
    }

    public function read($id){
        $value = $this->redis->get($id);//获取redis中的指定记录
        if($value){
            return $value;
        }else{
            return '';
        }
    }

    public function write($id,$data){
        if($this->redis->set($id,$data)){//以session ID为键，存储
            $this->redis->expire($id, $this->sessionExpireTime);//设置redis中数据的过期时间，即session的过期时间
            return true;
        }

        return false;
    }

    public function destroy($id){
        if($this->redis->delete($id)){//删除redis中的指定记录
            return true;
        }
        return false;
    }

    public function gc($maxlifetime){
        return true;
    }

    public function __destruct(){
        session_write_close();
    }

}



// 使用
// ------------------
// a.php
include('SessionManager.php');
new SessionManager();
$_SESSION['username'] = 'captain';


// b.php
include('SessionManager.php');
new SessionManager();
echo $_SESSION['username'];
```


再看redis库
------------------
```
// 再查看redis数据库，如下所示
// 127.0.0.1:6379> keys *
// 1) "oe94eic337slnjv1bvlreoa574"

// 127.0.0.1:6379> get oe94eic337slnjv1bvlreoa574
// "username|s:7:\"captain\";"
```





