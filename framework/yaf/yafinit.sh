#!/bin/sh

# author:qianm
# use this shell can init yaf project dir
# 作用：初始化yaf框架基本目录


currDir=$(cd $(dirname $0); pwd)
#echo $currDir
cd $currDir

read -p "输入你的要创建的项目名字(字母和数字)： " projectName
#echo $projectName
# 如果输入的了项目名则会在当前目录下新建项目目录，然后进入该目录；
# 如果不输
if [ "$projectName" == "" ];then
	echo "执后该脚本后，需按要求输入新项目名称"
	exit
fi

mkdir "$projectName"
cd $projectName
pwd

#项目下需要创建的目录
projectDirs[0]="public"
projectDirs[1]="public/css"
projectDirs[2]="public/img"
projectDirs[3]="public/js"
projectDirs[4]="conf"

projectDirs[5]=application
projectDirs[6]=application/controllers
projectDirs[7]=application/views
projectDirs[8]=application/modules
projectDirs[9]=application/library
projectDirs[10]=application/models
projectDirs[11]=application/plugins
projectDirs[12]=application/views/index

for perDir in ${projectDirs[*]}
do
	mkdir $perDir
done

#创建入口文件
cat >> "${projectDirs[0]}/index.php" << EOF
<?php
define("APP_PATH",  realpath(dirname(__FILE__) . '/../')); /* 指向public的上一级 */
\$app  = new Yaf_Application(APP_PATH . "/conf/application.ini");
\$app->run();
EOF

#配置文件
cat >> "${projectDirs[4]}/application.ini" << EOF
[product]
;支持直接写PHP中的已定义常量
application.directory=APP_PATH"/application/" 
EOF

#创建默认控制器
cat >> "${projectDirs[6]}/Index.php" << EOF
<?php
class IndexController extends Yaf_Controller_Abstract {
   public function indexAction() {//默认Action
       \$this->getView()->assign("content", "Hello World");
   }
}
EOF

#创建默认模板
cat >> "${projectDirs[12]}/index.phtml" << EOF
<html>
 <head>
   <title>Hello World</title>
 </head>
 <body>
  <?php echo \$content; ?>
 </body>
</html>
EOF


#创建错误控制器
cat >> "${projectDirs[6]}/Error.php" << EOF
<?php
/**
 * 当有未捕获的异常, 则控制流会流到这里
 */
class ErrorController extends Yaf_Controller_Abstract {
	/**
	* 此时可通过\$request->getException()获取到发生的异常
	*/
	public function errorAction() {
	  \$exception = \$this->getRequest()->getException();
	  try {
	    throw \$exception;
	  } catch (Yaf_Exception_LoadFailed \$e) {
	    //加载失败
	  } catch (Yaf_Exception \$e) {
	    //其他错误
	  }
	}
}
EOF

