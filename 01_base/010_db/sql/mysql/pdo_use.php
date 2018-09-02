<?php


// pdo连接
// ======================
$pdo = new PDO($dsn, $db_user, $db_pwd);
// 注意 第一个参数的格式：$dsn = "mysql:host=localhost;dbname=jikexueyuan"

// 注意pdo预处理绑定参数的两种方式：
// 1 问号 
// 2 别名 
// 绑定时也有两种方式：
// 1 $stmt->bindParam('xx', 'xx')逐条绑定每个参数
// 2 直接在execute时，同时传入所有参数。推荐用这种，操作方便。
// 下面有具体例子



// 数据源相关信息
$db_host = '127.0.0.1';
$db_name = 'student';
$db_user = 'root';
$db_pwd = '';
$charset = 'utf8';
$dsn = "mysql:host=$db_host;dbname=$db_name";

$options = [
				\PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.$charset,
           ];

try {
	// 连接数据源
	$pdo = new PDO($dsn, $db_user, $db_pwd, $options);
	// 如果option里已指定了set names utf8，这里不用再次写了
	// $pdo->query('set names utf8');

} catch(PDOException $e) {
  	die("数据库连接失败".$e->getMessage());
}


// 更新
// ======================
// 通过别名的方式绑定参数
$query = "UPDATE tb_chengji SET score=:score WHERE name=:name";
$stmt = $pdo->prepare($query);
 
$name = '赵明明';
$score = 98;
$stmt->bindParam(':name',$name);
$stmt->bindParam(':score',$score);
$stmt->execute();
echo $stmt->rowCount();
 
$name = '王大大';
$score = 120;
$stmt->bindParam(':name',$name);
$stmt->bindParam(':score',$score);
$stmt->execute();
echo $stmt->rowCount();




// 添加
// ======================
// 可以用另外一种绑定方式
// 下面例子通过用 name 和 value 取代 ? 占位符的位置来执行一条插入查询。
$stmt = $pdo->prepare("INSERT INTO test (name, value) VALUES (?, ?)");
$stmt->bindParam(1, $name);
$stmt->bindParam(2, $value);
 
// 插入一行
$name = 'one';
$value = 1;
$stmt->execute();
echo $stmt->rowCount();
echo $pdo->lastInsertId();




// 获取查询结果
// ======================
try{
	$pdo = new PDO("mysql:host=localhost;dbname=jikexueyuan","root","");
} catch(PDOException $e) {
  	die("数据库连接失败".$e->getMessage());
}
 
//2.预处理的SQL语句
$sql = 'select catid,catname,catdir from cy_category where parentid = :parentid';
$stmt = $pdo->prepare($sql);

// 这里没有用$stmt->bindParam(xx, xx)，直接给$stmt->execute传参数，推荐用这种，方便。
// 注意：两种方式均可。
$params = [
	//'parentid' => 9
	':parentid' => 9
];
$stmt->execute($params); 


// PDO::FETCH_ASSOC 关联数组形式
// PDO::FETCH_NUM 数字索引数组形式
// $row = $stm->fetchAll(PDO::FETCH_ASSOC);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	var_dump($row);
}



// 事务
// ===============
// 开启
$pdo->beginTransaction();
// 回滚
$pdo->rollBack();
// 提交
$pdo->commit();
  
  




