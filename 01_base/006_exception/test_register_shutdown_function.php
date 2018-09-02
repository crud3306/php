<?php


register_shutdown_function('zyfshutdownfunc');
function zyfshutdownfunc()
{
     if ($error = error_get_last()) {
          var_dump('<b>register_shutdown_function: Type:' . $error['type'] . ' Msg: ' . $error['message'] . ' in ' . $error['file'] . ' on line ' . $error['line'] . '</b>');
     }
}

test();
