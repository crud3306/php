<?php

set_exception_handler('my_exception_handler');
function my_exception_handler($exception)
{
     var_dump("<b>set_exception_handler: Exception: " . $exception->getMessage() . '</b>');
}


//throw new Exception("my exception 123");

try {

     throw new Exception("my exception");

} catch (Exception $e) {
     echo $e->getMessage();
     //echo $e; 
}


throw new Exception("my exception 123");
