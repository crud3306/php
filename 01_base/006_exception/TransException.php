<?php

/**
 * 事务异常类 TransException.php
 */

class TransException extends Exception
{
    // 事务是否可以继续提交的依据： 0 事务需要回滚rollback 10 可以继续提交 commit
    public $type = 0;
    public $apiCode = 0;

    /**
     * 自定议一个$type参数，做特殊判断用
     * TransException constructor.
     * @param string $message
     * @param int $type
     * @param int $code
     */
    public function __construct($message = '', $type = 0, $apiCode = 0, $code = 0)
    {
        parent::__construct($message, $code);
        $this->type = $type;
        $this->apiCode = $apiCode;
    }

    /**
     * 输出特殊信息
     * @return string
     */
    public function getTransMessage()
    {
        return $this->message.'['.$this->type.']';
    }

    /**
     * 是否可提交事务
     * @return bool
     */
    public function ableCommit()
    {
        return 10 == $this->type;
    }

    /**
     * 为api接口返回的code
     */
    public function getApiCode()
    {
        return $this->apiCode;
        
        if ($this->apiCode) {
            return $this->apiCode;
        }
	
        //return api_config::$code_err_common;
    }
}
