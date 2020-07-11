<?php

// 关于swoole_server 的平滑重启，执行台下命令即可
// ps aux |grep 某个server的master进程名字 | awk "{print $2}" | xargs kil -USR1

$serv = new swoole_http_server("127.0.0.1", 9501);
$serv->set([
	'worker_num'	=> 1
]);

$serv->on('Start', function(){
	swoole_set_process_name('base_route_master');
});

$serv->on('ManagerStart', function(){
	swoole_set_process_name('base_route_manager');
});

$serv->on('WorkerStart', function(){
	swoole_set_process_name('base_route_woker');

	spl_autoload_register(function($class){
		$baseClassPath = \str_replace('\\', DIRECTORY_SEPARATOR, $class).'.php';

		$classpath = __DIR__.'/'.$baseClassPath;
		if (is_file($classpath)) {
			require "{$classpath}";
			return;
		}
	});

});

$serv->on('Request', function($request, $response){
	$path_info = explode('/', $request->server['path_info']);

	// 拼装controller
	if (isset($path_info[1]) && !empty($path_info[1])) {
		$ctrl = 'ctrl\\'.$path_info[1];
	} else {
		$ctrl = 'ctrl\\Index';
	}

	// 拼装action
	if (isset($path_info[2])) {
		$acton = $path_info[2];
	} else {
		$action = 'index';
	}

	// 调用识别的controller的action
	$result = 'Ctrl not found';

	if (class_exists($ctrl)) {
		$class = new $ctrl();
		$result = "Action no found";

		if (methon_exists($class, $action)) {
			$result = $class->$action($request);
		}
	}

	$reponse->end($result);
});

