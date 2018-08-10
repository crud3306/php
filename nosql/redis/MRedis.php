<?php

/**
 * redis操作 QRedis.php
 */

define("REDIS_HOST", '127.0.0.1');
define("REDIS_PORT", '6379');
define("REDIS_PASS", '');
//define('REDIS_DBINDEX', '')


class MRedis
{
    public static $a = 1;
    public static $redis;

    public function __construct()
    {
        if (!self::$redis) {
            $this->connect();
        }

        return self::$redis;
    }

    /**
     * 连接
     */
    private function connect()
    {
        try {
            $redis = new Redis();         //创建Redis对象
            if (defined('REDIS_PORT')) {
                $redis->connect(REDIS_HOST, REDIS_PORT);  //连接服务
            } else {
                $redis->connect(REDIS_HOST);  //连接服务
            }
        } catch (Exception $e) {
            echo 'rediserr '.$e->getMessage();
            exit();
        }

        if (REDIS_PASS) {
            $redis->auth(REDIS_PASS);     //验证
        }

        if (defined('REDIS_DBINDEX') && REDIS_DBINDEX != 0) {
            $redis->select(REDIS_DBINDEX); //选择库
        }

        self::$redis = $redis;
    }

    /**
     * 重连
     */
    private function reconnect()
    {
        echo "reconnect Redis..".PHP_EOL;

        //清空原有的
        self::$redis = null;
        $this->connect();
    }

    /**
     * 代理方法，写入失败解决方式
     * @param unknown $action
     * @param array $params
     */
    public function __call($action, $params = array())
    {
        $result = false;

        //是否存在
        if (!is_callable([__CLASS__, $action])) {
            return false;
        }

        try {
            //执行
            $result = call_user_func_array([__CLASS__, $action], $params);
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }

        if ($result === false && $this->ping() != '+PONG') {
            echo 'redis 重连..';
            //重新连接
            $this->reconnect();
            //重新执行
            return call_user_func_array([__CLASS__, $action], $params);
        }

        return $result;
    }

    /**
     * 检测redis服务状态
     * @todo debug 待完善
     */
    private function ping()
    {
        return self::$redis->ping();
    }

    /**
     * 数据入队列
     * @param string $key KEY名称
     * @param string|array $value 获取得到的数据
     * @param string $direction r:右边 l:左边
     */
    private function push($key, $value, $direction = 'r')
    {
        $value = json_encode($value);
        return 'r' == $direction ? self::$redis->rPush($key, $value) : self::$redis->lPush($key, $value);
    }

    /**
     * 数据出列
     * @param string $key KEY名称
     * @param string $direction r:右边 l:左边
     */
    private function pop($key, $direction = 'l')
    {
        $val = 'l' == $direction ? self::$redis->lPop($key) : self::$redis->rPop($key);
        return json_decode($val);
    }

    private function lSize($key)
    {
        return self::$redis->lSize($key);
    }

    private function increment($key)
    {
        return self::$redis->incr($key);
    }

    private function decrement($key)
    {
        return self::$redis->decr($key);
    }
}