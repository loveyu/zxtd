<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
if(isset($_GET['act'])){
	require(ROOT."/../config.php");
	setcookie(COOKIE_PR.'user','',0,'/');
	setcookie(COOKIE_PR.'pwd','',0,'/');
	die('<html><META HTTP-EQUIV="Refresh" CONTENT="0; URL=./login.php?act=logout"></html>');
}
require(ROOT."/include/admin-init.php");

if(is_login())die(html_jump("./index.php"));

if(isset($_GET['url']))$url="&url=".$_GET['url'];
$url=NULL;

if(!(isset($_POST['user']) && $_POST['user']!=NULL))die(html_jump("./login.php?err=用户名不能为空".$url));
if(!(isset($_POST['pwd']) && $_POST['pwd']!=NULL))die(html_jump("./login.php?err=密码不能为空".$url));
if(!(isset($_POST['save']) && $_POST['save']==1))$_POST['save']=0;

$login=new password($_POST['user'],$_POST['pwd']);
if(!$login->mysql_pwd())die(html_jump("./login.php?err=密码错误".$url));
if(!$GLOBALS['user']['active'])die(html_jump("./login.php?err=账户被禁止".$url));
$cookie=new mycookies($GLOBALS['user']['user'],$option->arr['cookie_key'],NULL);
$cookie->new_cookie();
$cookie->set($_POST['save']*$option->arr['cookie_time']*60*60);
if(!$cookie->up_data())die(html_jump("./login.php?err=登陆错误").$url);
else {
	if(isset($_GET['url']))die(html_jump(urldecode($_GET['url'])));
	die(html_jump("./index.php"));
}
?>