<?php

try {
    // 自定义的transaction_helper类
    transaction_helper::begin();

    //@todo somethings

    $a = 1;
    if ($a < 9) {
    	// TransException是自定义的异常类
        throw new TransException("我抛出事务异常了");
    }

    //@todo somethings

    transaction_helper::commit();

} catch (TransException $e) {
    if (!$e->ableCommit()) {
        transaction_helper::roll_back();

    } else {
        //@todo somethings
        //一般不需要走这，除极特殊情况

        transaction_helper::commit();
    }

    return api_helper_return($e->getApiCode(), $e->getTransMessage());
}


