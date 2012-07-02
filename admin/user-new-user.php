<?php
define('ROOT',dirname($_SERVER['SCRIPT_FILENAME']));
require(ROOT."/include/admin-init.php");
if(!is_login())die(html_jump('login.php'));

set_page_type('user','new_user');
set_page_power(array(1));

get_admin_header();
?>
<?php
if($GLOBALS['user']['power']!=1){die("Forbidden");get_admin_footer();}
if(isset($_GET['status']))echo "<p>状态：".$_GET['status']."</p>";?>
<form action="user-action.php" method="post">
用户名:<input name="user" type="text"><br>
密码:<input name="pwd" type="password"><br>
密码（再一次）:<input name="pwd2" type="password"><br>
邮箱：<input name="email" type="text"><br>
电话:<input name="tel" type="text"><br>
<input type="hidden" name="act" value="new_user">
<button type="submit">提交</button><br>
</form>
<?php get_admin_footer();?>