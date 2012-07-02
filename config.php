<?php
$runtime= new runtime;//开始计时类
$runtime->start();//计时

//设置时区，当前为北京时间
date_default_timezone_set('PRC');

//error_reporting(E_ALL ^ E_NOTICE);//不提示NOTICE消息
error_reporting(E_ALL);//提示所有消息

/** 论坛数据库的名称 */
define('DB_NAME', 'zxtd');

/** MySQL 数据库用户名 */
define('DB_USER', 'root');

/** MySQL 数据库密码 */
define('DB_PASSWORD', '123456');

/** MySQL 主机 */
define('DB_HOST', 'localhost');

/** 创建数据表时默认的文字编码 */
define('DB_CHARSET', 'utf8');

/** 数据库前缀 */
define('MYSQL_PR','zx_');

/** 密码KEY */
define('PASSWORD_KEY','&Ku4nWtf$V0xu^y*zfu4StTfjJQyYG8j');

/** cookie前缀 */
define('COOKIE_PR','ZXTD_');

define('HTML_CHARSET', 'UTF-8');//网页编码

//临时开启全部发送编码头文件
header("content-Type: text/html; charset=".HTML_CHARSET."");

class runtime
{
	var $StartTime = 0;
	var $StopTime = 0;
	function get_microtime(){
		list($usec, $sec) = explode(' ', microtime()); 
		return ((float)$usec + (float)$sec); 
	}
	function start(){
		$this->StartTime = $this->get_microtime(); 
	}
	function stop(){
		$this->StopTime = $this->get_microtime(); 
	}
	function spent(){
		return round(($this->StopTime - $this->StartTime), 5); //返回秒数
	}
}
?>