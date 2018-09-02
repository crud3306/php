<?php

$mysqli = new Mysqli($db_host, $db_user, $db_psw, $db_name);
// 或者
// $mysqli = new mysqli();  
// $mysqli->connect($db_host, $db_user, $db_psw, $db_name);  

if ($mysqli->connect_errno) {
    die("Connect Error:".$mysqli->connect_error);
}

$mysqli->set_charset('utf8');

// 如果上面没有选择数据库，这里需通过该方法选择
// $select_db = $mysqli->select_db($mysql_conf['db']);
// if (!$select_db) {
//     die("could not connect to the db:\n" .  $mysqli->error);
// }



// 新增数据
// ==================
$id    = '';
$title = 'title4';

//用？代替 变量
$sql = "INSERT test VALUES (?,?)";

//获得$mysqli_stmt对象，一定要记住传$sql，预处理是对sql语句的预处理。
$mysqli_stmt = $mysqli->prepare($sql);

//第一个参数表明变量类型，有i(int),d(double),s(string),b(blob)
$mysqli_stmt->bind_param('is', $id, $title);

//执行预处理语句
if ($mysqli_stmt->execute()) {
    echo $mysqli_stmt->insert_id."\n";
    echo $mysqli_stmt->affected_rows;
} else {
    echo $mysqli_stmt->error;
}

$mysqli_stmt->close();
$mysqli->close();



// 查询数据
// ==================
// ... 此处省略库的连接

$id = '4';
$age = '20';
$sql = "SELECT * FROM test WHERE id=? AND age=?";

$mysqli_stmt = $mysqli->prepare($sql);
//第一个参数表明变量类型，有i(int),d(double),s(string),b(blob)
$mysqli_stmt->bind_param('ii', $id, $age);

if ($mysqli_stmt->execute()) {


	// 处理返回结果的两种方式
	// fetch
	// -----------------------
	//为准备语句绑定实际变量 
	$stmt->bind_result($id, $content); 
	//显示绑定结果的变量 
	while ($stmt->fetch()) { 
		echo "第".$id."条： ".$content."<br />"; 
	} 



	// store_result
	// -----------------------
	$mysqli_stmt -> store_result();
	if ($mysqli_stmt->num_rows() > 0) {
		$mysqli_stmt -> bind_result('is', $id, $name);
		while($row = $mysqli_stmt -> fetch()) {
		    var_dump($row);
		}	
	}

	
}

$mysqli_stmt->free_result();
$mysqli_stmt->close();
$mysqli->close();



// 删除数据
// ==================
// ... 此处省略库的连接
$id    = '';

//用？代替 变量
$sql = "DELETE FROM test WHERE id = ?";

//获得$mysqli_stmt对象，一定要记住传$sql，预处理是对sql语句的预处理。
$mysqli_stmt = $mysqli->prepare($sql);

//第一个参数表明变量类型，有i(int),d(double),s(string),b(blob)
$mysqli_stmt->bind_param('i', $id);

// 执行预处理语句
if ($mysqli_stmt->execute()) {
    echo $mysqli_stmt->affected_rows;
} else {
    echo $mysqli_stmt->error;
}

$mysqli_stmt->close();
$mysqli->close();



// 事务
// ===============
// 方式1
// begin_transaction可以传参，事务级别
$mysqli->begin_transaction();
$msyqli->commit(); //提交事务
$mysqli->rollback();

// 方式2
$mysqli->autocommit(FALSE);
$msyqli->commit(); 
$mysqli->rollback();
$mysqli->autocommit(TRUE); //开启自动提交功能








