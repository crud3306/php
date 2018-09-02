<?php

// 封装
class MRedis
{
	private static $instance = null;
	private $redis;

	private function __construct()
	{
		$this->connect();
	}

	private function __clone()
	{

	}

	// 获取本操作类的一个实例，保持一次请求中只实例化一次
	public static function getInstance()
	{
		if (!self::$instance) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function connect()
	{
		self::$instance = null;

		try {
            $redis = new Redis();         //创建Redis对象
            $redis->connect(REDIS_HOST, REDIS_PORT);  //连接服务

            if (REDIS_PASS) {
	            $redis->auth(REDIS_PASS);     //验证
	        }

	        if (defined('REDIS_DBINDEX') && REDIS_DBINDEX != 0) {
	            $redis->select(REDIS_DBINDEX); //选择库
	        }
            
        } catch (Exception $e) {
        	// todo log $e->getMessage();
            echo 'rediserr '.$e->getMessage();
            exit();
        } 

        $this->redis = $redis;
	}

	/**
     * 检测redis服务状态
     * @todo debug 待完善
     */
    private function ping()
    {
        return $this->redis->ping();
    }

	public function __call($action, $params = [])
	{
		$result = false;

        //是否存在
        if (!is_callable([$this->redis, $action])) {
            return false;
        }

        try {
            //执行
            $result = call_user_func_array([$this->redis, $action], $params);

        } catch (Exception $e) {
        	// todo log $e->getMessage();
            echo $e->getMessage();
            return false;
        }

        if ($result === false && $this->ping() != '+PONG') {
            // todo log 'redis 重连..';
            //重新连接
            self::$instance = null;
            //重新执行
            return call_user_func_array([$this->redis, $action], $params);
        }

        return $result;
	}
}



define('REDIS_HOST', '127.0.0.1');
define('REDIS_PORT', 6379);
define('REDIS_PASS', '123456');



// ===========
// string
// ===========
$redis = MRedis::getInstance();
// $key = 'haha:20180815';
// $res = $redis->setnx($key, 456);
// var_dump($res);

// $res = $redis->get($key);
// var_dump($res);

// $res = $redis->delete($key);
// var_dump($res);

// $res = $redis->get($key);
// var_dump($res);





// ===========
// hash
// ===========
$key = 'hash:08151548';
//hset/hget 存取hash表的数据
// $redis->hset($key,'key1','v1'); //将key为'key1' value为'v1'的元素存入hash1表
// $redis->hset($key,'key2','v2');
// $redis->hget($key,'key1'); //取出表$key中的key 'key1'的值,返回'v1'

//hexists 返回hash表中的指定key是否存在
// var_dump($redis->hexists($key,'key1')); //true or false

//hdel 删除hash表中指定key的元素
// var_dump($redis->hdel($key,'key2')); //true or false

//hlen 返回hash表元素个数
// var_dump($redis->hlen($key)); //1

//hsetnx 增加一个元素,但不能重复
// $redis->hsetnx($key,'key1','v2'); //false
// $redis->hsetnx($key,'key2','v2'); //true

//hmset/hmget 存取多个元素到hash表
// $redis->hmset($key,array('key3'=>'v3','key4'=>'v4'));
// $redis->hmget($key,array('key3','key4')); //返回相应的值 array('v3','v4')

//hincrby 对指定key进行累加
// $redis->hincrby($key,'key5',3); //返回3
// $redis->hincrby($key,'key5',10); //返回13

//hkeys 返回hash表中的所有key
// $redis->hkeys($key); //返回array('key1','key2','key3','key4','key5')

//hvals 返回hash表中的所有value
// $redis->hvals($key); //返回array('v1','v2','v3','v4',13)

//hgetall 返回整个hash表元素
// $redis->hgetall($key); 
//返回array('key1'=>'v1','key2'=>'v2','key3'=>'v3','key4'=>'v4','key5'=>13)





// ===========
// list
// ===========
$key = 'list:08151548';
//rpush/rpushx 有序列表操作,从队列后插入元素
//lpush/lpushx 和rpush/rpushx的区别是插入到队列的头部,同上,'x'含义是只对已存在的key进行操作
$redis->rpush($key, 'bar1'); //返回一个列表的长度1
$redis->lpush($key, 'bar0'); //返回一个列表的长度2
$redis->rpushx($key, 'bar2'); //返回3,rpushx只对已存在的队列做添加,否则返回0

//llen返回当前列表长度
// var_dump($redis->llen($key));//3
// lsize同llen作用一样
// var_dump($redis->lsize($key));//3

//lrange 返回队列中一个区间的元素
// $redis->lrange($key,0,1); // 返回数组包含第0个至第1个共2个元素
// $redis->lrange($key,0,-1);//返回第0个至倒数第一个,相当于返回所有元素,注意redis中很多时候会用到负数,下同

//lindex 返回指定顺序位置的list元素
// $redis->lindex($key,1); //返回'bar1'
//lset 修改队列中指定位置的value
// $redis->lset($key,1,'123');//修改位置1的元素,返回true

//lrem 删除队列中左起指定数量的字符
// $redis->lrem($key,1,'_'); //删除队列中左起(右起使用-1)1个字符'_'(若有)

//lpop/rpop 类似栈结构地弹出(并删除)最左或最右的一个元素
// $redis->lpop($key); //'bar0'
// $redis->rpop($key); //'bar2'

//ltrim 队列修改，保留左边起若干元素，其余删除
// $redis->ltrim($key, 0,1); //保留左边起第0个至第1个元素

//rpoplpush 从一个队列中pop出元素并push到另一个队列
// $redis->rpush('list1','ab0');
// $redis->rpush('list1','ab1');
// $redis->rpush('list2','ab2');
// $redis->rpush('list2','ab3');
// $redis->rpoplpush('list1','list2');//结果list1 =>array('ab0'),list2 =>array('ab1','ab2','ab3')
// $redis->rpoplpush('list2','list2');//也适用于同一个队列,把最后一个元素移到头部list2 =>array('ab3','ab1','ab2')

//linsert 在队列的中间指定元素前或后插入元素
// $redis->linsert('list2', 'before','ab1','123'); //表示在元素'ab1'之前插入'123'
// $redis->linsert('list2', 'after','ab1','456'); //表示在元素'ab1'之后插入'456'

//blpop/brpop 阻塞并等待一个列队不为空时，再pop出最左或最右的一个元素（这个功能在php以外可以说非常好用）
//brpoplpush 同样是阻塞并等待操作，结果同rpoplpush一样
// $redis->blpop('list3',10); //如果list3为空则一直等待,直到不为空时将第一元素弹出,10秒后超时





// ===========
// set
// ===========
$key = 'set:081515';
// $redis->sadd($key,'111');  
// $redis->sadd($key,'112');  
// $redis->sadd($key,'113');  
// $redis->sadd($key,'114');  
// $redis->sadd($key,'115');  

// 某set中是否有某值
// var_dump($redis->scontains($key, '111')); //结果：bool(true)  

// 取set长度
// var_dump($redis->ssize($key));

// 移除并返回集合中的一个随机元素。
// var_dump($redis->spop($key)); 

// 某个set的元素个数
// var_dump($redis->ssize($key));
// 某个set的元素个数，同ssize作用一样
// var_dump($redis->scard($key));

// $key1 = 'set:081516';
// $redis->sadd($key1,'aaa');  
// $redis->sadd($key1,'111');  
// $redis->sadd($key1,'112');  
// $redis->sadd($key1,'116');  

// var_dump($redis->ssize($key1));

// sismember 判断元素是否属于当前set
// var_dump($redis->sismember($key,'171')); //true or false

// 取某个set的全部数据
// var_dump($redis->smembers($key));

// 取多个set的交集，返回的是交集数组
// var_dump($redis->sinter($key, $key1));

// 取多个set的并集，返回数组
// var_dump($redis->sunion($key, $key1));

// 取多个set的差集，(返回在第一个集合中存在但在其他所有集合中不存在的)
// var_dump($redis->sdiff($key, $key1));

// 随机返回某个set中的一个元素
// var_dump($redis->srandmember($key));

//srem 删除set指定元素
// var_dump($redis->srem($key1,'113')); 

// smove 移动某个set表的指定元素到另一个set表
// $redis->smove($key, $key1,'115');//移动$key中的'115'到$key,返回true or false
// var_dump($redis->smembers($key));
// var_dump($redis->smembers($key1));



// ===========
// sorted set
// ===========
// 注意：以下说的索引实际上是指score

//sadd 增加元素,并设置序号,返回true,重复返回false
$key = 'zset:20180815';
// $redis->zadd($key,1,'ab');
// $redis->zadd($key,2,'cd');
// $redis->zadd($key,3,'ef');

//zincrby 对指定元素索引值的增减,改变元素排列次序
// var_dump($redis->zincrby($key,10,'ab'));//返回11

//zrem 移除指定元素
// var_dump($redis->zrem($key,'ef')); //true or false

//zrange 按位置次序返回表中指定区间的元素
// var_dump($redis->zrange($key,0,1)); //返回位置0和1之间(两个)的元素
// var_dump($redis->zrange($key,0,-1));//返回位置0和倒数第一个元素之间的元素(相当于所有元素)

//zrevrange 同上,返回表中指定区间的元素,按次序倒排
// var_dump($redis->zrevrange($key,0,-1)); //元素顺序和zrange相反

//zrangebyscore/zrevrangebyscore 按顺序/降序返回表中指定索引区间的元素
// var_dump($redis->zadd($key,3,'ef'));
// var_dump($redis->zadd($key,5,'gh'));

//返回索引值2-9之间的元素,且含2和9 array('ef','gh')
// var_dump($redis->zrangebyscore($key,2,9)); 

//参数形式
//返回索引值2-9之间的元素并包含索引值 array(array('cd',2), array('ef',3),array('gh',5))
// var_dump($redis->zrangebyscore($key,2,9,['withscores'=>true])); 

//返回索引值2-9之间的元素,'withscores' =>true表示包含索引值; 'limit'=>array(1, 2),表示最多返回2条,结果为array(array('ef',3),array('gh',5))
// var_dump($redis->zrangebyscore($key,2,9,array('withscores' =>true,'limit'=>array(1, 2)))); 

//zunionstore/zinterstore 将多个表的并集/交集存入另一个表中
// var_dump($redis->zunionstore('zset3', array($key,'zset2','zset0'))); //将$key,'zset2','zset0'的并集存入'zset3'
// var_dump($redis->zrange('zset3',0,-1));

//其它参数
// $redis->zunionstore('zset3',array($key,'zset2'),array('weights' => array(5,0)));//weights参数表示权重，其中表示并集后值大于5的元素排在前，大于0的排在后
// $redis->zunionstore('zset3',array($key,'zset2'),array('aggregate' => 'max'));//'aggregate' => 'max'或'min'表示并集后相同的元素是取大值或是取小值

// zcount 统计一个索引区间的元素个数
// var_dump($redis->zcount($key,3,5));//2
// var_dump($redis->zcount($key,'(3',5)); //'(3'表示索引值在3-5之间但不含3,同理也可以使用'(5'表示上限为5但不含5
// http://kmnk03.com/hxpfk/gx/113.html

//zcard 统计元素个数
// var_dump($redis->zcard($key));//4

//zscore 查询元素的索引
// var_dump($redis->zscore($key,'gh'));//5

//zremrangebyscore 删除一个索引区间的元素
// var_dump($redis->zremrangebyscore($key,0,2)); 
//删除索引在0-2之间的元素('ab','cd'),返回删除元素个数2

//zrank/zrevrank 返回元素所在表顺序/降序的位置(不是索引)
// var_dump($redis->zrank($key,'ef')); //2
// var_dump($redis->zrevrank($key,'ef')); //1

//zremrangebyrank 删除表中指定位置区间的元素
// var_dump($redis->zremrangebyrank($key,0,1)); //删除位置为0-10的元素,返回删除的元素个数2
// var_dump($redis->zrange($key,0,-1));





// 更多命令操作
// redis_by_php_more.php





