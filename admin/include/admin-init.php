<?php
require_once(ROOT."/../config.php");
require(ROOT."/include/functions.php");
require(ROOT."/../include/class/mysql-class.php");
require(ROOT."/../include/class/options-class.php");
require(ROOT."/../include/class/user-class.php");
require(ROOT."/../include/class/library-class.php");
$mysql=new mysql(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME,DB_CHARSET,MYSQL_PR);
$option=new option();
$cookie;
check_login();
?>