<?php

/**
 * 文件上传类
 */
class Upload{

    private $allowExt = array('gif','jpg','jpeg','bmp','png','swf');//限制文件上传的后缀名
    private $maxSize = 1;//限制最大文件上传1M

    /**
     * 获取文件的信息
     * @param  str $flag 上传文件的标识
     * @return arr       上传文件的信息数组
     */
    public function getInfo($flag){
        return $_FILES[$flag];
    }

    /**
     * 获取文件的扩展名 
     * @param str $filename 文件名
     * @return str 文件扩展名
     */
    public function getExt($filename){
        return pathinfo($filename,PATHINFO_EXTENSION);
    }

    /**
     * 检测文件扩展名是否合法
     * @param str $filename 文件名
     * @return bool 文件扩展名是否合法
     */
    private function checkExt($filename){
        $ext = $this->getExt($filename);
        return in_array($ext,$this->allowExt);
    }

    /**
     * 检测文件大小是否超过限制
     * @param int size 文件大小
     * @return bool 文件大小是否超过限制
     */
    public function checkSize($size){
        return $size < $this->maxSize * 1024 * 1024;
    }

    /**
     * 随机的文件名
     * @param int $len 随机文件名的长度
     * @return str 随机字符串
     */
    public function randName($len=6){
        return substr(str_shuffle('abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ234565789'),0,$len);
    }

    /**
     * 创建文件上传到的路径
     * @return str 文件上传的路径
     */ 
    public function createDir(){
        $dir = './upload/'.date('Y/m/d',time());
        if(is_dir($dir) || mkdir($dir,0777,true)){
            return $dir;
        }
    }

    /**
     * 文件上传
     * @param str $flag 文件上传标识
     * @return arr 文件上传信息
     */
    public function uploadFile($flag){
        if($_FILES[$flag]['name'] === '' || $_FILES[$flag]['error'] !== 0){
            echo "没有上传文件";
            return;
        }
        $info = $this->getInfo($flag);
        if(!$this->checkExt($info['name'])){
            echo "不支持的文件类型";
            return;
        }
        if(!$this->checkSize($info['size'])){
            echo "文件大小超过限制";
            return;
        }
        $filename = $this->randName().'.'.$this->getExt($info['name']);
        $dir = $this->createDir();
        if(!move_uploaded_file($info['tmp_name'], $dir.'/'.$filename)){
            echo "文件上传失败";
        }else{
            return array('filename'=>$filename,'dir'=>$dir);
        }
    }

}

