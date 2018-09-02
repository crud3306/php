<?php

// 文档地址：https://wiki.swoole.com/wiki/page/326.html

// 注：swoole_http_server继承自swoole_server，是一个完整的http服务器实现。

$serv = new swoole_http_server("127.0.0.1", 9501);
// 默认上传2M尺寸的文件 或 POST 2M数据，可修改package_max_length调整最大POST尺寸限制。
$serv->set([
	'max_package_length'	=> 200000000,
	// 'upload_tmp_dir'		=> '',
	// 'http_parse_post'		=> false,
	// 'document_root'			=> '',
	// 'enable_static_handler'	=> true
]);

$serv->on('Request', function($request, $response) use ($serv) {
	if ($request->server['request_method'] == 'GET') {
		return;
	}

	var_dump($request->files);
	$file = $request->files['file'];
	$file_name = $file['name'];
	$file_tmp_path = $file['tmp_name'];

	$upload_path = __DIR__.'/uploader/';
	if ( !file_exists($upload_path)) {
		mkdir($upload_path);
	}
	move_uploaded_file($file_tmp_path, $upload_path.$file_name);

	$response->end("<h1>upload success</h1>");
});

$serv->start();