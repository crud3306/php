<?php

// 基础
// ==========
set_time_limit(0);

header ( "Content-type:application/vnd.ms-excel" );  
header ( "Content-Disposition:filename=" . iconv ( "UTF-8", "GB18030", "query_user_info" ) . ".csv" );  
  
// 打开PHP文件句柄，php://output 表示直接输出到浏览器  
$fp = fopen('php://output', 'a');   
  
// 将中文标题转换编码，否则乱码  
foreach ($column_name as $i => $v) {    
       $column_name[$i] = iconv('utf-8', 'GB18030', $v);    
   }  
   // 将标题名称通过fputcsv写到文件句柄    
   fputcsv($fp, $column_name);  
  
$pre_count = 10000;  
for ($i=0;$i<intval($total_export_count/$pre_count)+1;$i++){  
    $export_data = $db->getAll($sql." limit ".strval($i*$pre_count).",{$pre_count}");  
    foreach ( $export_data as $item ) {  
        $rows = array();  
        foreach ( $item as $export_obj){  
            $rows[] = iconv('utf-8', 'GB18030', $export_obj);  
        }  
        fputcsv($fp, $rows);  
    }  
      
    // 将已经写到csv中的数据存储变量销毁，释放内存占用  
	unset($export_data);  
	ob_flush();  
	flush();  
}  





// 导出说明:因为EXCEL单表只能显示104W数据，同时使用PHPEXCEL容易因为数据量太大而导致占用内存过大，
// 因此，数据的输出用csv文件的格式输出，但是csv文件用EXCEL软件读取同样会存在只能显示104W的情况，所以将数据分割保存在多个csv文件中，并且最后压缩成zip文件提供下载

function putCsv(array $head, $data, $mark = 'attack_ip_info', $fileName = "test.csv")
{
    set_time_limit(0);

    $sqlCount = $data->count();

    // 输出Excel文件头，可把user.csv换成你要的文件名
    header('Content-Type: application/vnd.ms-excel;charset=utf-8');
    header('Content-Disposition: attachment;filename="' . $fileName . '"');
    header('Cache-Control: max-age=0');

    $sqlLimit = 100000; //每次只从数据库取100000条以防变量缓存太大
    // 每隔$limit行，刷新一下输出buffer，不要太大，也不要太小
    $limit = 100000;
    // buffer计数器
    $cnt = 0;
    $fileNameArr = array();

    // 逐行取出数据，不浪费内存
    for ($i = 0; $i < ceil($sqlCount / $sqlLimit); $i++) {

        $fp = fopen($mark . '_' . $i . '.csv', 'w'); //生成临时文件
  		//     chmod('attack_ip_info_' . $i . '.csv',777);//修改可执行权限
        $fileNameArr[] = $mark . '_' .  $i . '.csv';

    	// 将数据通过fputcsv写到文件句柄
        fputcsv($fp, $head);

        $dataArr = $data->offset($i * $sqlLimit)->limit($sqlLimit)->get()->toArray();
        foreach ($dataArr as $a) {
            $cnt++;
            if ($limit == $cnt) {
                //刷新一下输出buffer，防止由于数据过多造成问题
                ob_flush();
                flush();
                $cnt = 0;
            }
            fputcsv($fp, $a);
        }

        fclose($fp);  //每生成一个文件关闭
    }

    //进行多个文件压缩
    $zip = new ZipArchive();
    $filename = $mark . ".zip";
    $zip->open($filename, ZipArchive::CREATE);   //打开压缩包
    foreach ($fileNameArr as $file) {
        $zip->addFile($file, basename($file));   //向压缩包中添加文件
    }
    $zip->close();  //关闭压缩包
    foreach ($fileNameArr as $file) {
        unlink($file); //删除csv临时文件
    }

    //输出压缩文件提供下载
    header("Cache-Control: max-age=0");
    header("Content-Description: File Transfer");
    header('Content-disposition: attachment; filename=' . basename($filename)); // 文件名
    header("Content-Type: application/zip"); // zip格式的
    header("Content-Transfer-Encoding: binary"); //
    header('Content-Length: ' . filesize($filename)); //
    @readfile($filename);//输出文件;
    unlink($filename); //删除压缩包临时文件
}








// 一个封装好的例子
// ==========
class PHPCsv
{
    public $Title="Simple";
    public $SheetHeader=[];
    public $SheetBody=[];
    public $fp;

    public function __construct()
    {

    }

    public function setTile($title)
    {
        $this->$Title=$title;
    }

    public function init()
    {
        header("content-Type:text/html; charset=UTF-8");
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$this->Title.'.csv"');
        header('Cache-Control: max-age=0');

		// 打开PHP文件句柄，php://output 表示直接输出到浏览器  
        $this->fp = fopen('php://output',  'w');
        fwrite($this->fp,chr(0xEF).chr(0xBB).chr(0xBF));

        foreach($this->SheetHeader as $key => $item) {
            $this->SheetHeader[$key] = $item;
            // $this->SheetHeader[$key] = iconv('UTF-8', 'GBK', $item);
        }
        fputcsv($this->fp, $this->SheetHeader);
    }

    public function addSheetBody($datas)
    {
        foreach($datas as $key => $data){

            foreach($this->SheetHeader as $k => $cell){
                $row[$k] = $data[$k];
				//$row[$k] = iconv('UTF-8','GBK',$data[$k]);
            }

            fputcsv($this->fp, $row);
        }

    }
}

// 使用示例
$csv=new PHPCsv();


$csv->Title='导出用户数据';
$csv->SheetHeader = ['ID', '用户名', '用户年龄', '用户描述', '用户手机', '用户QQ', '用户邮箱', '用户地址'];

$csv->init();

$pages = 100;
$per_page = 10000;

for($s = 1; $s <= $pages; ++$s) {
    $start = ($s - 1) * $per_page;
    $rows = $res->rows("SELECT * FROM tb_users ORDER BY id LIMIT {$start},{$per_page}");

    $csv->addSheetBody($rows);
    //每1万条数据就刷新缓冲区
    ob_flush();
    flush();
}





