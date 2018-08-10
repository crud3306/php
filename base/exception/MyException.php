<?php

class MyException extends Exception
{
     public function errorMessage()
     {
          return 'Error line ' . $this->getLine().' in ' . $this->getFile().': <b>' . $this->getMessage() . '</b> Must in (0 - 60)';
     }
}

$age = 100;
try {
     $age = intval($age);
     if ($age > 60) {
          throw new MyException($age);
     }
} catch (MyException $e) {
     echo $e->errorMessage();
}
