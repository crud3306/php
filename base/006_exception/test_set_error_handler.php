<?php

set_error_handler('zyferror');
function zyferror($type, $message, $file, $line)
{
     throw new \Exception($message . ' 错误当做异常');
}

$num = 0;
try {
     echo 1/$num;

} catch (Exception $e){
     echo $e->getMessage();
}
