<?php

// 导出csv 如果要导出大数据，请见：export_csv_big_data.php
// =================
function createcsv($fileName, $keys,  $data)
{
    // 头部标题
    
    $header = implode(',', $keys) . PHP_EOL;
    $content = '';
    foreach ($data as $k => $v) {

    	foreach ($keys as $v1) {
    		if (isset($v[$v1])) {
    			$content .= $v[$v1].',';
    		}
    	}

        $content = rtrim($content, ',') . PHP_EOL;
    }

    $csvData = $header . $content;
    header("Content-type:text/csv;");
    header("Content-Disposition:attachment;filename=" . $fileName);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    echo $csvData;
}
 

$keys = ['id', 'user_name', 'score'];
// $keys = ['id', 'score'];

$data = [];
array_push($data, ['id'=>'1', 'user_name'=>'haha01', 'score'=>100]);
array_push($data, ['id'=>'2', 'user_name'=>'haha02', 'score'=>101]);
array_push($data, ['id'=>'3', 'user_name'=>'haha03', 'score'=>102]);

 
createcsv('1.csv', $keys, $data);





// 建立user表
// =================
// CREATE TABLE `user` (
//     `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT,
//     `name` varchar(32) NOT NULL,
//     `sex` char(10) NOT NULL,
//     `age` int(3) NOT NULL DEFAULT '0',
//     PRIMARY KEY (`id`)
// )ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



// 导入csv
// =================
public function import()
{
    if (!$_FILES['file']['name']){//判断是否为空
        echo '上传文件不能为空，请导入csv文件';exit;
    }

    $extension = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);//取后缀名

    if ($extension != 'csv') {//判断是否为csv文件
        echo '该文件不是csv文件，请导入csv文件';exit;
    }

    // 可以直接读
    // ---------------
    $file_path = $_FILES['file']['tmp_name'];

    // 也可以先把文件存下来，然后再读
    // ---------------
    // $file_path = './upload/user_'.time().'.csv';
    // $status = move_uploaded_file($_FILES['file']['tmp_name'], $file_path);

    // // 将文件保存到指定的路径
    // if (!$status) {
    //     echo "上传失败!";exit;
    // }


    $handle = fopen($file_path, "r");

    $sql_str = '';
    while (($fileop = fgetcsv($handle, 1000, ",")) !== false) {
        $name = mb_convert_encoding($fileop[0], "UTF-8", "GBK");
        $sex = mb_convert_encoding($fileop[1], "UTF-8", "GBK");
        $age = $fileop[2];
        $str = "('".$name."', '".$sex."', $age), ";
        $sql_str .= $str;
    }

    $sqlstr = substr($sql_str, 0, strlen($sql_str)-2);//去除最后的空格和逗号
    fclose($handle);

    $sql = "INSERT INTO `user` (`name`, `sex`, `age`) VALUES $sqlstr";//sql语句
    $query = mysql_query($sql);
    if (!$query) {
        echo "导入失败！";
    }

    echo "导入完成！";
}



//导出csv
// =================
function export()
{
    $str = '';
    $header = mb_convert_encoding('姓名，性别，年龄\n',"GBK","UTF-8");

    $sql = 'SELECT * FROM `user`';
    $result = mysql_query($sql);
    while ($row = mysql_fetch_array($result)) {
        $name = mb_convert_encoding($row['name'],"GBK","UTF-8");
        $sex = mb_convert_encoding($row['sex'],"GBK","UTF-8");
        $age = $row['age'];
        $str .= $name.$sex.$age."\n";
    }

    $data = $header.$str;
    $filename = 'user_'.time().'.csv';
    writeDataToCsv($filename, $data);//将数据导出
}

/*
*输出CSV文件
*$filename:文件名称 $data:要写入的数据
*/
function writeDataToCsv($filename, $data)
{
    header("Content-type:text/csv"); 
    header("Content-Disposition:attachment;filename=".$filename); 
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0'); 
    header('Expires:0'); 
    header('Pragma:public');
    echo $data;
}





