<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require_once(ROOT."/include/admin-init.php");
if(is_login())die(html_jump("./index.php"));
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $option->arr['sitename']?> - 用户登陆</title>
<link href="template/admin.css" rel="stylesheet" type="text/css">
</head>
<body>
<div class="login">
<form action="logining.php<?php
if(isset($_GET['url']))echo urlencode($_GET['url']);
else if(isset($_SERVER['HTTP_REFERER'])&& basename($_SERVER['HTTP_REFERER'])!='logining.php' &&!isset($_GET['act']))
	echo "?url=",urlencode($_SERVER['HTTP_REFERER']);
?>" method="post">
<h2><a href="<?php echo $option->arr['siteurl']?>"><?php echo $option->arr['sitename']?></a></h2>
<table border="0" cellspacing="0" cellpadding="0">
<?php if(isset($_GET['err']))echo "<tr>\n<td class=\"t_rigth red\">错误：</td>\n<td class=\"t_left red\">".$_GET['err']."</td></tr>\n";?>
<tr>
<td class="t_rigth">用户名:</td>
<td class="t_left"><input name="user" type="text"/></td>
</tr>
<tr>
<td class="t_rigth">密码:</td>
<td class="t_left"><input name="pwd" type="password" /></td>
</tr>
<tr>
<td class="t_rigth"><label><input type="checkbox" name="save" value="1" />记住密码</label></td>
<td class="t_center"><button type="submit">登陆</button></td>
</tr>
</table>
<input name="time" value="<?php echo $_SERVER['REQUEST_TIME'];?>" type="hidden">
</form>
</div>
</body>
</html>